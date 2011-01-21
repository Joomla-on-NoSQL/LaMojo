<?php
/**
 * @version		$Id: view.html.php 18740 2010-08-31 19:21:09Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of projects.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 * @since		1.5
 */
class ProjectsViewProjects extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $canDo;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->canDo	= ProjectsHelperACL::getActions($this->state->get('filter.category_id'));
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$state	= $this->state;
		$canDo	= $this->canDo;

		JToolBarHelper::title(JText::_('COM_PROJECTS_MANAGER_PROJECTS'), 'article');
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('project.add','JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('project.edit','JTOOLBAR_EDIT');

			JToolBarHelper::divider();
			JToolBarHelper::custom('projects.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('projects.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);

			JToolBarHelper::divider();
			JToolBarHelper::archiveList('projects.archive','JTOOLBAR_ARCHIVE');
			JToolBarHelper::custom('projects.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'projects.delete','JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit')) {
			JToolBarHelper::trash('projects.trash','JTOOLBAR_TRASH');
		}
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_projects');
		JToolBarHelper::divider();
		JToolBarHelper::help('COM_PROJECTS_HELP_PATH', false, 'http://jcamp.3den.org/');	
	}
}
