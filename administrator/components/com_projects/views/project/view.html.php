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
 * View to edit a project.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 * @since		1.5
 */
class ProjectsViewProject extends JView
{
	protected $state;
	protected $item;
	protected $form;
	protected $canDo;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->canDo	= ProjectsHelperACL::getActions();

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
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= $this->canDo;

		JToolBarHelper::title(JText::_('COM_PROJECTS_MANAGER_PROJECT'), 'article');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('project.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('project.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->id)) {
			JToolBarHelper::cancel('project.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
