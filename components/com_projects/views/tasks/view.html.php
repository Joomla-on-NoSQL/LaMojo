<?php

/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Projects component
 *
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @since		1.6
 */
class ProjectsViewTasks extends JView {

    protected $items;
    protected $project;
    protected $state;
    protected $maxLevel;
    protected $params;
    protected $pagination;
    protected $canDo;
    protected $prefix;

    /**
     * Display View
     * @param $tpl
     */
    function display($tpl = null) {
        $app = JFactory::getApplication();
        $model = $this->getModel();

        // Get some data from the models
        $this->items = $model->getItems();
        $this->state = $this->get('State');
        $this->project = $model->getProject();
        $this->pagination = $model->getPagination();
        $this->params = $app->getParams();
        $this->canDo = ProjectsHelper::getActions(
                        $app->getUserState('portfolio.id'),
                        $app->getUserState('project.id'),
                        $this->project);

        $layout = $this->getLayout();
        switch ($layout) {
            default:
                $layout = 'default';
                // Get project
                if (empty($this->project)) {
                    return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
                }
                break;
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            return JError::raiseError(500, implode("\n", $errors));
        }

        // add 'home page' of our component breadcrumb
        $bc = $app->getPathway();
        $bc->addItem($this->project->title, 'index.php?option=com_projects&view=project&id=' . $this->project->id);
        $bc->addItem(JText::_('COM_PROJECTS_TASKS'));

        // set a correct prefix
        $this->loadHelper('tasks');
        $this->type = TasksHelper::getPrefix($model->getState('type'));

        // TMLP
        $this->setLayout($layout);
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        $this->loadHelper('toolbar');
        switch ($this->state->get('type')) {
            case 3:
                $title = JText::sprintf('COM_PROJECTS_TASKS_LIST_TICKETS_TITLE', $this->project->title);
                $icon = 'archive';
                if ($this->canDo->get('ticket.create')) {
                    ToolBar::addNew('task.add');
                }
                break;

            case 1:
            case 2:
                $title = JText::sprintf('COM_PROJECTS_TASKS_LIST_TASKS_TITLE', $this->project->title);
                $icon = 'archive';

                if ($this->canDo->get('task.edit')) {
                    ToolBar::custom('tasks.archive', 'checkin', 'apply', JText::_('COM_PROJECTS_STATE_FINISHED'));
                    ToolBar::custom('tasks.publish', 'unpublish', 'unpublish', JText::_('COM_PROJECTS_STATE_PENDING'));
                    ToolBar::spacer();
                }

                break;
        }

        if ($this->canDo->get('task.create')) {
            ToolBar::addNew('task.add');
        }
        if ($this->canDo->get('task.edit')) {
            ToolBar::editList('task.edit');
        }
        if ($this->canDo->get('task.delete')) {
            ToolBar::deleteList('task.delete');
        }

        if ($this->params->get('show_back_button')) {
            ToolBar::spacer();
            ToolBar::back();
        }
        ToolBar::title($title, $icon);

        echo ToolBar::render();
    }

}