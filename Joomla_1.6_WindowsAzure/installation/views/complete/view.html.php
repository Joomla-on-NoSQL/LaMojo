<?php
/**
 * @version		$Id: view.html.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.html.html');
ini_set('include_path', JPATH_LIBRARIES);
require_once JPATH_ADMINISTRATOR.'\components\com_media\helpers\winazure.php';
/**
 * The HTML Joomla Core Install Complete View
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationViewComplete extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state = $this->get('State');
		$options = $this->get('Options');

		// Get the config string from the session.
		$session = JFactory::getSession();
		$config = $session->get('setup.config', null);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		if($options['storage_type'] == 'winazure')
			$this->backupConfiguration($options);
		$this->assignRef('state', $state);
		$this->assignRef('options', $options);
		$this->assignRef('config', $config);

		parent::display($tpl);
	}
	
	private function backupConfiguration($options)
	{
		if(file_exists(JPATH_SITE.'/configuration.php'))
		{
			WinAzureHelper::initialize($options['acc_name'], $options['access_key']);
			WinAzureHelper::createFolder('config');
			WinAzureHelper::createBlob('config', 'configuration.php', JPATH_SITE.'/configuration.php');
		}
	}
}