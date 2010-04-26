<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Users list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class UsersControllerUsers extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_USERS_USERS';

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('block',		'changeBlock');
		$this->registerTask('unblock',		'changeBlock');
	}
	/**
	 * Proxy for getModel.
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'User', $prefix = 'UsersModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	/**
	 * Method to remove a record.
	 *
	 * @since	1.6
	 */
	public function changeBlock()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JError_No_items_selected'));
		} else {
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->block($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			} else {
				$this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to remove a record.
	 *
	 * @since	1.6
	 */
	public function activate()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JError_No_items_selected'));
		} else {
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids)) {
				JError::raiseWarning(500, $model->getError());
			} else {
				$this->setMessage(JText::sprintf('Users_N_Users_Activated', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to run batch opterations.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function batch()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= &$this->getModel('User');
		$vars	= JRequest::getVar('batch', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');

		// Sanitize user ids.
		$cid = array_unique($cid);
		JArrayHelper::toInteger($cid);

		// Remove any values of zero.
		if (array_search(0, $cid, true)) {
			unset($cid[array_search(0, $cid, true)]);
		}

		// Attempt to run the batch operation.
		if (!$model->batch($vars, $cid)) {
			// Batch operation failed, go back to the users list and display a notice.
			$message = JText::sprintf('USERS_USER_BATCH_FAILED', $model->getError());
			$this->setRedirect('index.php?option=com_users&view=users', $message, 'error');
			return false;
		}

		$message = JText::_('USERS_USER_BATCH_SUCCESS');
		$this->setRedirect('index.php?option=com_users&view=users', $message);
		return true;
	}
}