<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formrule');

class JFormRuleContactEmailSubject extends JFormRule
{
	public function test(& $element, $value, $group = null, & $input = null, & $form = null)
	{
		$params = JComponentHelper::getParams('com_contact');
		$banned = $params->get('banned_subject');

		if (false === $this->_checkText($value, $banned)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Checks $text for values contained in the array $array, and sets error message if true...
	 *
	 * @param String	$text		Text to search against
	 * @param String	$list		semicolon (;) seperated list of banned values
	 * @return Boolean
	 * @access protected
	 * @since 1.5.4
	 */
	private function _checkText($text, $list) {
		if (empty($list) || empty($text)) return true;
		$array = explode(';', $list);
		foreach ($array as $value) {
			$value = trim($value);
			if (empty($value)) continue;
			if (JString::stristr($text, $value) !== false) {
				return false;
			}
		}
		return true;
	}
}