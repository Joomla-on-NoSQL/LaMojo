<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

// Imports
jimport('joomla.application.component.view');

/**
 * Display project view
 * @author eden & elf
 */
class ProjectsViewProject extends JView
{
	protected $item;
	protected $form;
	protected $params;
	protected $catid;
	protected $canDo;
	protected $category;
	
	/**
	 * Display project
	 */
	public function display($tpl = null) 
	{	
		$app		= &JFactory::getApplication();
		$model		= &$this->getModel();

		//Get Model data
		$this->item 	= &$model->getItem();
		$this->params	= &$app->getParams();
		$this->canDo	= &ProjectsHelper::getActions();
		
		// Layout
		$layout = $this->getLayout();
		switch($layout){
			case 'edit':
			case 'form':
				$layout = 'form';
				$this->form	= &$model->getForm();
				if (empty($this->item)) {
					$this->catid = $app->getUserState('portfolio.id', 0);
				}
				break;
			
			default:
				$layout = 'default';
				if (!$this->canDo->get('project.view')){
					return JError::raiseError(505, JText::_('JERROR_ALERTNOAUTHOR'));				
				}
				if (empty($this->item->id)){
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
				}
				
				// Get Category
				$this->category = &$model->getCategory($this->item->catid);
				
				// add 'potfolio' and 'project' of our component breadcrumb
			  	$bc = $app->getPathway();
		  		$bc->addItem($this->category->title, 'index.php?option=com_projects&view=projects&layout=gallery&id='.$this->item->catid);
		  		$bc->addItem($this->item->title);
		}
		
		// Display the view
		$this->setLayout($layout);
		parent::display($tpl);
	}
}