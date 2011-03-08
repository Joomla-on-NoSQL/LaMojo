<?php
/*
# ------------------------------------------------------------------------
# JA Amazon S3 installation Package (component & Plugin)
# ------------------------------------------------------------------------
# Copyright (C) 2004-2010 JoomlArt.com. All Rights Reserved.
# @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
# Author: JoomlArt.com
# Websites: http://www.joomlart.com - http://www.joomlancers.com.
# ------------------------------------------------------------------------
*/

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );
require_once JPATH_ADMINISTRATOR.DS.'components\com_media\helpers\winazure.php';

class plgSystemPlg_azure extends JPlugin
{
	var $_component = "azurePlugin";


	function plgSystemPlg_azure(&$subject, $config)
	{
		
		parent::__construct ( $subject, $config );

		$this->plugin = &JPluginHelper::getPlugin ( 'system', 'plg_azure' );
		$result = new JRegistry;
		$result->loadJSON($this->plugin->params);
		$this->plgParams = $result;
		
	}
	
	
	
	function onAfterRender() {
		global $app;
		global $option;

		$apply_admin = (int) $this->plgParams->get('apply_admin', 0);
		if ($app->isAdmin() && !$apply_admin) {
			return;
		}

		
			$body = JResponse::getBody();
			
			/***********************************/
			//CONVERT ALL URLs TO ABSOLUTE FORMAT 
			/***********************************/
			// from format: /abc/def/xyz.jpg (absolute path without domain)
			$path = JURI::root(true);
			if(!empty($path)) {
				$host = substr(JURI::root(), 0, - (strlen($path)));
			} else {
				$host = JURI::root();
			}
			//remove last slashes
			$host = preg_replace('#[/\\\\]+$#', "", $host);
			
			//find and replace urls
			$pattern = "/(href|src)\s*=\s*(\'|\")\/+([^\'\"]*)(\'|\")/i";
			$body = preg_replace($pattern, '$1=$2'.$host.'/$3$4', $body);
			
			
			//from format: abc/def/xyz.jpg (relative path)
			$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
			$path = JPath::clean($path, "/");
			
			
			$currUrl = dirname($host ."/". $path)."/";
			
			if (!WinAzureHelper::initialize())
			{
				JError::raiseWarning(100, JText::sprintf('Error while connecting to azure'));
				return false;
			}
			
			$currUrl =WinAzureHelper::getBaseUrl().'/';
			
			$body = preg_replace("/(href|src)\s*=\s*(\'|\")((?!(?:\w+)\:\/*)[^\/\#][^\'\"]*)(\'|\")/i", '$1=$2'.$currUrl.'$3$4', $body);
			
			JResponse::setBody($body);
		
	}
	
}
?>