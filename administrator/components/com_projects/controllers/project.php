<?php

/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	Projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	Projects
 * @since		1.6
 */
class ProjectsControllerProject extends JControllerForm {
	protected $text_prefix = 'COM_PROJECTS_PROJECT';
	protected $view_item = 'project';
	protected $view_list = 'projects';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Project', $prefix = 'ProjectsModel', $config = array()) {
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Assigns members to a project
	 *
	 * @since	1.6
	 */
	public function assignMembers() {
		// Check for request forgeries
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel();
		$members = JRequest::getVar('cid', array(), '', 'array');

		$id = JRequest::getInt('id', 0);

		if (!$model->addMembers($id, $members)) {
			JError::raiseWarning(500, JText::_('JERROR_AN_ERROR_HAS_OCCURRED'));
			return false;
		}

		$this->setRedirect(
		ProjectsHelper::getLink('members.assign', $id),
		JText::_('COM_PROJECTS_MEMBERS_ASSIGN_SUCCESSFUL'));

		return true;
	}

	/**
	 * deletes members from a project
	 *
	 * @since	1.6
	 */
	public function unassignMembers() {
		// Check for request forgeries
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel();
		$members = JRequest::getVar('cid', array(), '', 'array');
		$id = JRequest::getInt('id', 0);

		if (!$model->removeMembers($id, $members)) {
			JError::raiseWarning(500, JText::_('JERROR_AN_ERROR_HAS_OCCURRED'));
			return false;
		}

		$this->setRedirect(
		ProjectsHelper::getLink('members.unassign', $id),
		JText::_('COM_PROJECTS_MEMBERS_DELETE_SUCCESSFUL'));
		return true;
	}

}