<?php
/**
 * @version		$Id: weblinks.php 17267 2010-05-25 19:16:30Z chdemko $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Weblinks helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 * @since		1.6
 */
class ProjectsHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu($vName)
	{
		$extension	= JRequest::getCmd('extension');
		$option		= JRequest::getCmd('option');
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_PROJECTS'),
			'index.php?option=com_projects&view=projects',
			$vName == 'projects'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_PORTFOLIOS'),
			'index.php?option=com_categories&extension=com_projects',
			$extension == 'com_projects'
		);
				
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_DOCUMENT_CATEGORIES'),
			'index.php?option=com_categories&extension=com_projects.document',
			$extension == 'com_projects.document'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_TASK_CATEGORIES'),
			'index.php?option=com_categories&extension=com_projects.task',
			$extension == 'com_projects.task'
		);				
	}
}
