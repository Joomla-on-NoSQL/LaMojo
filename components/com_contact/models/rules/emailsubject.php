<?php
	/**
	 * Validates some inputs based on component configuration
	 *
	 * @param Object	$contact	JTable Object
	 * @param String	$subject	Email subject
	 * @return Boolean
	 * @access protected
	 * @since 1.5
	 */
	function _validateInputs( $contact, $subject)
	{
		$app	= JFactory::getApplication();
		$session = JFactory::getSession();

		// Get params and component configurations
		$params = new JRegistry;
		$params->loadJSON($contact->params);
		$pparams	= $app->getParams('com_contact');


		// Determine banned subjects
		$configSubject	= $pparams->get('banned_subject', '');
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


		return true;
	}