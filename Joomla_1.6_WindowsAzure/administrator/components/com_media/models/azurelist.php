<?php
/**
 * @version		$Id: list.php 20228 2011-01-10 00:52:54Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
ini_set('include_path', 'components/com_media/includes');
require_once 'components\com_media\helpers\winazure.php';
/**
 * Media Component List Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since 1.5
 */
class MediaModelAzureList extends JModel
{
	function getState($property = null, $default = null)
	{
		static $set;

		if (!$set) {
			$folder = JRequest::getVar('folder', '', '', 'path');
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}

		return parent::getState($property, $default);
	}

	function getImages()
	{
		$list = $this->getList();

		return $list['images'];
	}

	function getFolders()
	{
		$list = $this->getList();

		return $list['folders'];
	}

	function getDocuments()
	{
		$list = $this->getList();

		return $list['docs'];
	}

	/**
	 * Build imagelist
	 *
	 * @param string $listFolder The image directory to display
	 * @since 1.5
	 */
	function getList()
	{
		static $list;

		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}
		WinAzureHelper::initialize();
		// Initialise variables.
		
		if (strlen($current) > 0) {
			$basePath = WinAzureHelper::getBaseUrl().'/'.$current;
		}else{
			$basePath = WinAzureHelper::getBaseUrl();
		}
		$mediaBase = str_replace(DS, '/', WinAzureHelper::getBaseUrl().'/');
		
		$images		= array ();
		$folders	= array ();
		$docs		= array ();

		// Get the list of files and folders from the given folder
		//$fileList	= JFolder::files($basePath);
		//$folderList = JFolder::folders($basePath);
		if (strlen($current) > 0) {
			if(strstr($current, '/'))
			{
				$fileList = $this->getNestedFileList($current);
				//$fileList = array();
				$folderList = $this->getNestedFolderList($current);
			}else{
				$fileList = $this->getFileList($current);
				$folderList = $this->getFolderListFiles($current);
			}
		}else{
			$folderList = $this->getFolderList();
			$fileList = $this->getFileList('images');
		}
		
		// Iterate over the files if they exist
		if ($fileList !== false) {
			foreach ($fileList as $file)
			{
				if (substr($file['name'], 0, 1) != '.' && strtolower($file['name']) !== 'index.html') {
					$tmp = new JObject();
					$tmp->name = $file['name'];
					$tmp->title = $file['name'];
					$tmp->path = str_replace(DS, '/', $file['path']);
					$tmp->path_relative = $tmp->path;
					$tmp->size = $file['size'];

					$ext = strtolower(JFile::getExt($file['name']));
					switch ($ext)
					{
						// Image
						case 'jpg':
						case 'png':
						case 'gif':
						case 'xcf':
						case 'odg':
						case 'bmp':
						case 'jpeg':
							$info = @getimagesize($file['path']);
							$tmp->width		= @$info[0];
							$tmp->height	= @$info[1];
							$tmp->type		= @$info[2];
							$tmp->mime		= @$info['mime'];

							if (($info[0] > 60) || ($info[1] > 60)) {
								$dimensions = MediaHelper::imageResize($info[0], $info[1], 60);
								$tmp->width_60 = $dimensions[0];
								$tmp->height_60 = $dimensions[1];
							}
							else {
								$tmp->width_60 = $tmp->width;
								$tmp->height_60 = $tmp->height;
							}

							if (($info[0] > 16) || ($info[1] > 16)) {
								$dimensions = MediaHelper::imageResize($info[0], $info[1], 16);
								$tmp->width_16 = $dimensions[0];
								$tmp->height_16 = $dimensions[1];
							}
							else {
								$tmp->width_16 = $tmp->width;
								$tmp->height_16 = $tmp->height;
							}

							$images[] = $tmp;
							break;

						// Non-image document
						default:
							$tmp->icon_32 = "media/mime-icon-32/".$ext.".png";
							$tmp->icon_16 = "media/mime-icon-16/".$ext.".png";
							$docs[] = $tmp;
							break;
					}
				}
			}
		}

		// Iterate over the folders if they exist
		if ($folderList !== false) {
			foreach ($folderList as $folder)
			{
				$tmp = new JObject();
				$tmp->name = basename($folder);
				$tmp->path = str_replace(DS, '/', $basePath.DS.$folder);
				$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
				$count = MediaHelper::countFiles($tmp->path);
				$tmp->files = $count[0];
				$tmp->folders = $count[1];

				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'docs' => $docs, 'images' => $images);

		return $list;
	}
	
	public function getFolderList()
	{
		$containers = WinAzureHelper::listContainers();
		foreach($containers as $container)
		{
			if($container->name != 'images')
				$folders[] = $container->name;
		}
		return $folders;
	}
	
	public function getFileList($container)
	{
		$file_list = array();
		$files = WinAzureHelper::listBlobs($container);
		$count = 0;
		
		foreach($files as $file)
		{
			if(!strstr($file->name, '/'))
			{
				$file_list[$count]['name'] = $file->name;
				$size = WinAzureHelper::getBlobProperties($container, $file->name);
				$file_list[$count]['size'] = $size->size;
				$file_list[$count]['path'] = $size->url;
			    $count++;
			}
		}
		return $file_list;
	}
	
	public function getFolderListFiles($container)
	{
		$folders = array();
		$files = WinAzureHelper::listBlobs($container);
		foreach($files as $file)
		{
			if(strstr($file->name, '/'))
			{
				$list = explode('/', $file->name);
				$folders[] = $list[0];
			}
		}
		return array_unique($folders);
	}
	
	public function getNestedFolderList($container)
	{
		$container_split = explode('/', $container);
		$container_main = $container_split['0'];
		$container_other = str_replace($container_main.'/', '', $container);
		$files = WinAzureHelper::listBlobs($container_main);
		$folders = array();
		foreach($files as $file)
		{
			if(strstr($file->name, $container_other.'/'))
			{
				$file_name = str_replace($container_other.'/', '', $file->name);
				$list = explode('/', $file_name);
				if(count($list) > 1)
					$folders[] = $list[0];
			}
		}
		return array_unique($folders);
	}
	
	public function getNestedFileList($container)
	{
		$container_split = explode('/', $container);
		$container_main = $container_split['0'];
		$container_other = str_replace($container_main.'/', '', $container);
		$files = WinAzureHelper::listBlobs($container_main);
		$file_list = array();
		$count = 0;
		foreach($files as $file)
		{
			if(strstr($file->name, $container_other.'/'))
			{
				$file_name = str_replace($container_other.'/', '', $file->name);
				$list = explode('/', $file_name);
				if(count($list) == 1){
					$file_list[$count]['name'] = $list[0];
					$size = WinAzureHelper::getBlobProperties($container_main, $container_other.'/'.$list[0]);
					$file_list[$count]['size'] = $size->size;
					$file_list[$count]['path'] = $size->url;
				    $count++;
				}
			}
		}
		return $file_list;
	}
}