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
class ProjectsViewPortfolios extends JView
{
	protected $items;
	protected $state;
	protected $params;
	protected $pagination;
	protected $canDo;
	protected $user;
	
	/**
	 * Display View
	 * @param $tpl
	 */
	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$model 		= $this->getModel('Portfolios');
		$bc 		= $app->getPathway();
			
		// Get some data from the models
		$this->state		= $this->get('State');
		$this->items		= $model->getItems();
		$this->pagination	= $model->getPagination();
		$this->parent		= $model->getParent();
		$this->params		= $app->getParams();
		$this->canDo		= ProjectsHelper::getActions();
		$this->user 		= JFactory::getUser();
		$this->parent		= $model->getParent();
		
		$c = count($this->items);
		for($i = 0; $i < $c;$i++) {
				$this->items[$i]->description = JHtml::_('content.prepare', $this->items[$i]->description);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			return JError::raiseError(500, implode("\n", $errors));
		}
		
		// Get pathway
		$bc->addItem(JText::_('COM_PROJECTS_PORTFOLIOS'));
		
		parent::display($tpl);
	}
}