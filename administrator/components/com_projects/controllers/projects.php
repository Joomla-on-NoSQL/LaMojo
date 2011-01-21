<?php

/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerProjects extends JControllerAdmin
{
	protected $view_item = 'project';
	protected $view_list = 'projects';
	protected $text_prefix = 'COM_PROJECTS_PROJECTS';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 * @since	1.6
	 */
	public function getModel($name = 'Project', $prefix = 'ProjectsModel', $config = array()) {
		return parent::getModel($name, $prefix, $config);
	}
}