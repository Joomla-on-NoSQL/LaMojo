<?php
	/**
	 * Validates some inputs based on component configuration
	 *
	 * @param Object	$contact	JTable Object
	 * @param String	$email		Email address
	 * @param String	$subject	Email subject
	 * @param String	$body		Email body
	 * @return Boolean
	 * @access protected
	 * @since 1.5
	 */
	function _validateInputs($contact, $email, $subject, $body)
	{
		$app	= JFactory::getApplication();
		$session = JFactory::getSession();

		// Get params and component configurations
		$params = new JRegistry;
		$params->loadJSON($contact->params);
		$pparams	= $app->getParams('com_contact');

		// check for session cookie
	//	$sessionCheck	= $pparams->get('validate_session', 1);
	//	$sessionName	= $session->getName();
	/*	if  ($sessionCheck) {
			if (!isset($_COOKIE[$sessionName])) {
				$this->setError(JText::_('JERROR_ALERTNOAUTHOR'));
				return false;
			}
		}
*/
		// Determine banned emails
		//$configEmail	= $pparams->get('banned_email', '');
		//$paramsEmail	= $params->get('banned_mail', '');
		//$bannedEmail	= $configEmail . ($paramsEmail ? ';'.$paramsEmail : '');

		// Prevent form submission if one of the banned text is discovered in the email field
	//	if (false === $this->_checkText($email, $bannedEmail)) {
	//		$this->setError(JText::sprintf('COM_CONTACT_EMAIL_BANNEDTEXT', JText::_('JGLOBAL_EMAIL')));
	//		return false;
	//	}

		// Determine banned subjects
	/*	$configSubject	= $pparams->get('banned_subject', '');
		$paramsSubject	= $params->get('banned_subject', '');
		$bannedSubject	= $configSubject . ($paramsSubject ? ';'.$paramsSubject : '');

		// Prevent form submission if one of the banned text is discovered in the subject field
		if (false === $this->_checkText($subject, $bannedSubject)) {
			$this->setError(JText::sprintf('COM_CONTACT_EMAIL_BANNEDTEXT',JText::_('COM_CONTACT_CONTACT_MESSAGE_SUBJECT')));
			return false;
		}

		// Determine banned Text
		$configText		= $pparams->get('banned_text', '');
		$paramsText		= $params->get('banned_text', '');
		$bannedText	= $configText . ($paramsText ? ';'.$paramsText : '');

		// Prevent form submission if one of the banned text is discovered in the text field
		if (false === $this->_checkText($body, $bannedText)) {
			$this->setError(JText::sprintf('COM_CONTACT_EMAIL_BANNEDTEXT', JText::_('COM_CONTACT_CONTACT_ENTER_MESSAGE')));
			return false;
		}

		// test to ensure that only one email address is entered
		$check = explode('@', $email);
		if (strpos($email, ';') || strpos($email, ',') || strpos($email, ' ') || count($check) > 2) {
			$this->setError(JText::_('COM_CONTACT_NOT_MORE_THAN_ONE_EMAIL_ADDRESS', true));
			return false;
		}

		return true;*/
	}