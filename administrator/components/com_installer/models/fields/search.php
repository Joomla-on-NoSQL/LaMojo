<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.form.formfield');

/**
 * Form Field Search class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.6
 */
class JFormFieldSearch extends JFormField
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Search';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function getInput()
	{
		$html = '';
		$html.= '<input type="text" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" title="' . JText::_('JSEARCH_FILTER') . '" onchange="this.form.submit();" />';
		$html.= '<button type="submit" class="btn">' . JText::_('JSEARCH_FILTER_SUBMIT') . '</button>';
		$html.= '<button type="button" class="btn" onclick="document.id(\'' . $this->id . '\').value=\'\';this.form.submit();">' . JText::_('JSEARCH_FILTER_CLEAR') . '</button>';
		return $html;
	}
}