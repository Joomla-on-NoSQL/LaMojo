<?php
 /*
 * @version		$Id: email.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formrule');

/**
 * Form Rule class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormRuleEmailContact extends JFormRuleEmail
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $regex = '^[\w.-]+(\+[\w.-]+)*@\w+[\w.-]*?\.\w{2,4}$';

	/**
	 * Method to test the email address and optionally check for uniqueness.
	 *
	 * @param	object	$element	The JXMLElement object representing the <field /> tag for the
	 * 								form field object.
	 * @param	mixed	$value		The form field value to validate.
	 * @param	string	$group		The field name group control value. This acts as as an array
	 * 								container for the field. For example if the field has name="foo"
	 * 								and the group value is set to "bar" then the full field name
	 * 								would end up being "bar[foo]".
	 * @param	object	$input		An optional JRegistry object with the entire data set to validate
	 * 								against the entire form.
	 * @param	object	$form		The form object for which the field is being tested.
	 *
	 * @return	boolean	True if the value is valid, false otherwise.
	 * @since	1.6
	 * @throws	JException on invalid rule.
	 */
	public function test(& $element, $value, $group = null, & $input = null, & $form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');
		if (!$required && empty($value)) {
			return true;
		}

		// Test the value against the regular expression.
		if (!parent::test($element, $value, $group, $input, $form)) {
			return false;
		}

		// Get params and component configurations
		$params = new JRegistry;
		$params->loadJSON($contact->params);
		$pparams	= $app->getParams('com_contact');
		
			// Determine banned emails
		$configEmail	= $pparams->get('banned_email', '');
		$paramsEmail	= $params->get('banned_mail', '');
		$bannedEmail	= $configEmail . ($paramsEmail ? ';'.$paramsEmail : '');

		// Prevent form submission if one of the banned text is discovered in the email field
		if (false === $this->_checkText($email, $bannedEmail)) {
			$this->setError(JText::sprintf('COM_CONTACT_EMAIL_BANNEDTEXT', JText::_('JGLOBAL_EMAIL')));
			return false;
		}

		return true;
	}
}