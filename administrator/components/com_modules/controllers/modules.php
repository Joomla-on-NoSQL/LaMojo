<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Modules list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @since		1.6
 */
class ModulesControllerModules extends JControllerAdmin
{
	/**
	 * Override the execute method to clear the modules cache for non-display tasks.
	 *
	 * @param	string		The task to perform.
	 * @return	mixed|false	The value returned by the called method, false in error case.
	 * @since	1.6
	 */
	public function execute($task)
	{
		parent::execute($task);

		// Clear the component's cache
		if ($task != 'display') {
			$cache = JFactory::getCache('com_modules');
			$cache->clean();
		}
	}

	/**
	 * Method to clone an existing module.
	 * @since	1.6
	 */
	public function duplicate()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$pks = JRequest::getVar('cid', array(), 'post', 'array');

		try {
			if (empty($pks)) {
				throw new Exception(JText::_('COM_MODULES_ERROR_NO_MODULES_SELECTED'));
			}
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(JText::plural('COM_MODULES_N_MODULES_DUPLICATED', count($pks)));
		} catch (Exception $e) {
			JError::raiseWarning(500, $e->getMessage());
		}

		$this->setRedirect('index.php?option=com_modules&view=modules');
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Module', $prefix = 'ModulesModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}