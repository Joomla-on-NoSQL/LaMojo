<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Cache component
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	Cache
 * @since 1.6
 */
class CacheViewPurge extends JView
{
	public function display($tpl = null)
	{
		$this->_setToolbar();
		parent::display($tpl);
	}

	protected function _setToolbar()
	{
		JSubMenuHelper::addEntry(JText::_('COM_CACHE_BACK_CACHE_MANAGER'), 'index.php?option=com_cache', false);

		JToolBarHelper::title(JText::_('COM_CACHE_MANAGER').' - '.JText::_('COM_CACHE_PURGE_CACHE_ADMIN'), 'purge.png');
		JToolBarHelper::custom('purge', 'delete.png', 'delete_f2.png', 'COM_CACHE_PURGE_EXPIRED', false);
		JToolBarHelper::divider();
		if (JFactory::getUser()->authorise('core.admin', 'com_cache'))
		{
			JToolBarHelper::preferences('com_cache');
			JToolBarHelper::divider();
		}
		JToolBarHelper::help('screen.cache');
	}
}