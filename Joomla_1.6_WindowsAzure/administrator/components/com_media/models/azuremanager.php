<?php
/**
 * @version		$Id: manager.php 20228 2011-01-10 00:52:54Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
ini_set('include_path', 'components/com_media/includes');
require_once 'components\com_media\helpers\winazure.php';
jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');

/**
 * Media Component Manager Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since 1.5
 */
class MediaModelAzureManager extends JModel
{
	function getState($property = null, $default = null)
	{
		static $set;

		if (!$set) {
			$folder = JRequest::getVar('folder', '', '', 'path');
			$this->setState('folder', $folder);

			$fieldid = JRequest::getCmd('fieldid', '');
			$this->setState('field.id', $fieldid);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}

		return parent::getState($property, $default);
	}

	/**
	 * Image Manager Popup
	 *
	 * @param string $listFolder The image directory to display
	 * @since 1.5
	 */
	function getFolderList($base = null)
	{
		// Get some paths from the request
		if (empty($base)) {
			$base = COM_MEDIA_BASE;
		}

		// Get the list of folders
		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($base, '.', true, true);

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_MEDIA_INSERT_IMAGE'));

		// Build the array of select options for the folder list
		$options[] = JHtml::_('select.option', "","/");

		foreach ($folders as $folder)
		{
			$folder		= str_replace(COM_MEDIA_BASE, "", $folder);
			$value		= substr($folder, 1);
			$text		= str_replace(DS, "/", $folder);
			$options[]	= JHtml::_('select.option', $value, $text);
		}

		// Sort the folder list array
		if (is_array($options)) {
			sort($options);
		}

		// Create the drop-down folder select list
		$asset = JRequest::getVar('asset');
		$author = JRequest::getVar('author');
		$list = JHtml::_('select.genericlist',  $options, 'folderlist', "class=\"inputbox\" size=\"1\" onchange=\"ImageManager.setFolder(this.options[this.selectedIndex].value,'".$asset."','$author'".")\" ", 'value', 'text', $base);

		return $list;
	}

	function getFolderTree($base = null)
	{
		// Get some paths from the request
		if (empty($base)) {
			$base = COM_MEDIA_BASE;
		}
		
		WinAzureHelper::initialize();

		//WinAzureHelper::createFolder('sachin'); 
		
		//WinAzureHelper::deleteContainer('karthik');
		$mediaBase = str_replace(DS, '/', COM_MEDIA_BASE.'/');

		// Get the list of folders
		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($base, '.', true, true);
		//$folders = $this->getFolders($mediaBase);

		$tree = array();
		
		foreach ($folders as $folder)
		{
			$folder		= str_replace(DS, '/', $folder);
			$name		= substr($folder, strrpos($folder, '/') + 1);
			$relative	= str_replace($mediaBase, '', $folder);
			$absolute	= $folder;
			$path		= explode('/', $relative);
			$node		= (object) array('name' => $name, 'relative' => $relative, 'absolute' => $absolute);

			$tmp = &$tree;
			for ($i=0,$n=count($path); $i<$n; $i++)
			{
				if (!isset($tmp['children'])) {
					$tmp['children'] = array();
				}

				if ($i == $n-1) {
					// We need to place the node
					$tmp['children'][$relative] = array('data' =>$node, 'children' => array());
					break;
				}

				if (array_key_exists($key = implode('/', array_slice($path, 0, $i+1)), $tmp['children'])) {
					$tmp = &$tmp['children'][$key];
				}
			}
		}
		$tree['data'] = (object) array('name' => JText::_('COM_MEDIA_MEDIA'), 'relative' => '', 'absolute' => $base);

		return $tree;
	}
	
	public function getFolders($path = null)
	{
		// Check to make sure the path valid and clean
		$path = JPath::clean($path);

		// Is the path a folder?
		if (!is_dir($path))
		{
			JError::raiseWarning(21, JText::sprintf('JLIB_FILESYSTEM_ERROR_PATH_IS_NOT_A_FOLDER_FOLDER', $path));
			return false;
		}
		$containers = WinAzureHelper::listContainers();
		foreach($containers as $container)
		{
			$folders[] = $path.$container->name;
		}
		return $folders;
	}
	
	public function syncLocaltoAzure()
	{
		$base = str_replace(DS, '/', COM_MEDIA_BASE);
		
		// Get the list of folders
		$folders = JFolder::folders($base, '.', true, true);
		//echo '<pre>';
		$root_folders = $this->getRootFolders($folders, $base);
				
		foreach($folders as $folder)
		{
			$folder		= str_replace(DS, '/', $folder);
			//$container_names[]	= substr($folder, strrpos($folder, '/') + 1);
			$file_list[$folder] = JFolder::files($folder);
		}
		//print_r($file_list);die();
		WinAzureHelper::initialize();
		
		//Check any files in the base folder
		$base_files = JFolder::files($base);
		if(!empty($base_files))
			WinAzureHelper::createFolder(substr($base, strrpos($base, '/') + 1));
		$def_file_list[$base] = $base_files;
		
		$this->createBaseFolderFiles($def_file_list);
		
		//create the nested folders and their files
		$this->createFolders($root_folders);
		$this->createFolderFiles($file_list, $root_folders, $base);
	}
	
	public function createFolders($container_names)
	{
		foreach($container_names as $name)
		{
			WinAzureHelper::createFolder($name); 
		}
	}
	
	public function createBaseFolderFiles($file_list)
	{
		foreach($file_list as $path=>$files)
		{
			$folder_name = substr($path, strrpos($path, '/') + 1);
			foreach($files as $file)
			{
				if (is_file($path.'/'.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
					WinAzureHelper::createBlob($folder_name, $file, $path.'/'.$file); 
				}
			}
		}
	}
	
	public function createFolderFiles($file_list, $root_folders, $base)
	{
		foreach($file_list as $path=>$files)
		{
			//$folder_name = substr($path, strrpos($path, '/') + 1);
			$folder_name = str_replace($base.'/', '', $path);
			if(in_array($folder_name,  $root_folders))
			{
				foreach($files as $file)
				{
					if (is_file($path.'/'.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
						WinAzureHelper::createBlob($folder_name, $file, $path.'/'.$file); 
					}
				}
			}else{
				foreach($root_folders as $root)
				{
					if(strstr($path, $base.'/'.$root))
					{
						$folder_name = str_replace($base.'/'.$root.'/', '', $path);
						if($folder_name != '')
						{
							foreach($files as $file)
							{
								if (is_file($path.'/'.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
									WinAzureHelper::createBlob($root, $folder_name.'/'.$file, $path.'/'.$file); 
								}
							}
						}
					}
				}
			}
		}
	}
	
	public function getRootFolders($folders, $base)
	{
		foreach($folders as $folder)
		{
			$folder		= str_replace(DS, '/', $folder);
			$folder		= str_replace($base.'/', '', $folder);
			if(!strstr($folder, '/'))
				$root_folder[] = $folder;
		}
		return $root_folder;
	}
}