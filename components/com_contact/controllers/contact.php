<?php
/**
 * @version
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.application.component.controllerform');
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 */
class ContactControllerContact extends JControllerForm
{
	/**
	 * @since	1.6
	 */
	protected $view_item = 'contact';
	
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	public function submit(){
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('contact');
		//$id 	= (int) $app->getUserState('com_contact.contact.id');
		$id		= JRequest::getVar('id');
		
		// Get the data from POST
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		
		$contact = $model->getItem($id);
		if ($contact->email_to == '' && $contact->user_id != 0)
		{
			$contact_user = JUser::getInstance($contact->user_id);
			$contact->email_to = $contact_user->get('email');
		}
		
		// Contact plugins
		JPluginHelper::importPlugin('contact');
		$dispatcher	= JDispatcher::getInstance();

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Validate the posted data.
		$validate = $model->validate($form,$data);
				
		if ($validate === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			
			// Save the data in the session.
			$app->setUserState('com_contact.contact.data', $data);
			
			// Redirect back to the contact form.
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id='.$id, false));
			return false;
		}
		
		// Validation succeeded, continue with custom handlers
		$results	= $dispatcher->trigger('onValidateContact', array(&$contact, &$data));
		foreach ($results as $result)
		{
			if (JError::isError($result)) {
				return false;
			}
		}
		
		// Passed Validation: Process the contact plugins to integrate with other applications
		$results	= $dispatcher->trigger('onSubmitContact', array(&$contact, &$post));
		
		$default	= JText::sprintf('MAILENQUIRY', $SiteName);
		$name		= $data['contact_name'];
		$email		= $data['contact_email'];
		$subject	= $data['contact_subject'];
		$body		= $data['contact_message'];
		$emailCopy	= (int)$data['contact_email_copy'];
		
		// Send the email
		$pparams = $app->getParams('com_contact');
		if (!$pparams->get('custom_reply'))
		{
			$MailFrom	= $app->getCfg('mailfrom');
			$FromName	= $app->getCfg('fromname');

			// Prepare email body
			$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JURI::base());
			$body	= $prefix."\n".$name.' <'.$email.'>'."\r\n\r\n".stripslashes();

			$mail = JFactory::getMailer();
			$mail->addRecipient($contact->email_to);
			$mail->setSender(array($email, $name));
			$mail->setSubject($FromName.': '.$subject);
			$mail->setBody($body);
			$sent = $mail->Send();

			//If we are supposed to copy the admin, do so.
			$params = new JRegistry($contact->params);
			$emailcopyCheck = $params->get('show_email_copy', 0);

			// check whether email copy function activated
			if ($emailCopy && $emailcopyCheck)
			{
				$copyText		= JText::sprintf('COM_CONTACT_COPYTEXT_OF', $contact->name, $SiteName);
				$copyText		.= "\r\n\r\n".$body;
				$copySubject	= JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

				$mail = JFactory::getMailer();

				$mail->addRecipient($email);
				$mail->setSender(array($MailFrom, $FromName));
				$mail->setSubject($copySubject);
				$mail->setBody($copyText);

				$sent = $mail->Send();
			}
		}

		if (!JError::isError($sent)) {
			$msg = JText::_('COM_CONTACT_EMAIL_THANKS');
		}
		
		// Flush the data from the session
		$app->setUserState('com_contact.contact.data', null);
		
		//redirect if it is set
		if ($contact->params->get('redirect')){
			$link=$contact->params->get('redirect');
			$this->setRedirect($link, $msg);
		} else {
			// stay on the same  contact page
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id='.$id, false), $msg);
		}

		return true;
	}
}
