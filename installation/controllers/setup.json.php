<?php
/**
 * @version		$Id$
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Setup controller for the Joomla Core Installer.
 * - JSON Protocol -
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationControllerSetup extends JController
{
	function loadSampleData()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JRequest::checkToken('request') or $this->sendJsonResponse(new JException(JText::_('JINVALID_TOKEN'), 403));

		// Get the posted config options.
		$vars = JRequest::getVar('jform', array());

		// Get the setup model.
		$model = $this->getModel('Setup', 'JInstallationModel', array('dbo' => null));

		// Get the options from the session.
		$vars = $model->storeOptions($vars);

		// Get the database model.
		$database = $this->getModel('Database', 'JInstallationModel', array('dbo' => null));

		// Attempt to load the database sample data.
		$return = $database->installSampleData($vars);

		// If an error was encountered return an error.
		if (!$return) {
			$this->sendJsonResponse(new JException($database->getError(), 500));
		} else {
			// Mark sample content as installed
			$data = array(
				'sample_installed' => '1'
			);
			$dummy = $model->storeOptions($data);
		}

		// Create a response body.
		$r = new JObject();
		$r->text = JText::_('INSTL_SITE_SAMPLE_LOADED');

		// Send the response.
		$this->sendJsonResponse($r);
	}

	function detectFtpRoot()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JRequest::checkToken('request') or $this->sendJsonResponse(new JException(JText::_('JINVALID_TOKEN'), 403));

		// Get the posted config options.
		$vars = JRequest::getVar('jform', array());

		// Get the setup model.
		$model = $this->getModel('Setup', 'JInstallationModel', array('dbo' => null));

		// Store the options in the session.
		$vars = $model->storeOptions($vars);

		// Get the database model.
		$filesystem = $this->getModel('Filesystem', 'JInstallationModel', array('dbo' => null));

		// Attempt to detect the Joomla root from the ftp account.
		$return = $filesystem->detectFtpRoot($vars);

		// If an error was encountered return an error.
		if (!$return) {
			$this->sendJsonResponse(new JException($filesystem->getError(), 500));
		}

		// Create a response body.
		$r = new JObject();
		$r->root = $return;

		// Send the response.
		$this->sendJsonResponse($r);
	}

	function verifyFtpSettings()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JRequest::checkToken('request') or $this->sendJsonResponse(new JException(JText::_('JINVALID_TOKEN'), 403));

		// Get the posted config options.
		$vars = JRequest::getVar('jform', array());

		// Get the setup model.
		$model = $this->getModel('Setup', 'JInstallationModel', array('dbo' => null));

		// Store the options in the session.
		$vars = $model->storeOptions($vars);

		// Get the database model.
		$filesystem = $this->getModel('Filesystem', 'JInstallationModel', array('dbo' => null));

		// Verify the FTP settings.
		$return = $filesystem->verifyFtpSettings($vars);

		// If an error was encountered return an error.
		if (!$return) {
			$this->sendJsonResponse(new JException($filesystem->getError(), 500));
		}

		// Create a response body.
		$r = new JObject();
		$r->valid = $return;

		// Send the response.
		$this->sendJsonResponse($r);
	}
}

// Set the error handler.
//JError::setErrorHandling(E_ALL, 'callback', array('JInstallationControllerSetup', 'sendJsonResponse'));
