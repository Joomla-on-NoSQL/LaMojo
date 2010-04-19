<?php defined('_JEXEC') or die;

/**
 * @version		$Id: manage.php 14276 2010-01-18 14:20:28Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.form.formfield');

/**
 * Form Field Place class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.6
 */
class JFormFieldClient extends JFormField
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Client';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function getInput() 
	{
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		$options = array();
		foreach ($this->element->children() as $option) {
			$options[] = JHtml::_('select.option', $option->attributes('value'), JText::_(trim($option->data())));
		}
		$options[] = JHtml::_('select.option', '0', JText::sprintf('JSITE'));
		$options[] = JHtml::_('select.option', '1', JText::sprintf('JADMINISTRATOR'));
		$return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $this->value, $this->id);
		return $return;
	}
}
