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
//require_once JPATH_ADMINISTRATOR.DS.'components\com_media\helpers\winazure.php';
class plgSystemPlg_azure extends JPlugin
{
	var $_component = "com_jaamazons3";
	var $s3url = "%s://%s.s3.amazonaws.com/";
	//format 2
	//var $s3url = "%s://s3.amazonaws.com/%s/";
	var $plgParams = null;
	var $aParams = array();
	
	var $distribute_url = '';
	var $active_bucket_id = '';

	function plgSystemPlg_azure(&$subject, $config)
	{
		global $mainframe;
		parent::__construct ( $subject, $config );

		$this->plugin = &JPluginHelper::getPlugin ( 'system', 'plg_azure' );
		$result = new JRegistry;
		$result->loadJSON($this->plugin->params);
		$this->plgParams = $result;
		//$this->plgParams = new JParameter ( $this->plugin->params );
		//WinAzureHelper::initialize();
		//echo WinAzureHelper::getBaseUrl();die();

	}
	
	function cronJobUpload() {
		global $mainframe;
		if ($mainframe->isAdmin()) {
			return;
		}
		// run cron job
		$params = JComponentHelper::getParams($this->_component);
		$cron_mode = $params->get('cron_mode');
		$uploadKey = $params->get('upload_secret_key', '');
		
		if($cron_mode == "pseudo" && !empty($uploadKey)) {
    	
			$cronFile = JPATH_ROOT.DS."administrator".DS."components".DS.$this->_component.DS."cron.php";
			if(is_file($cronFile)) {
				$key = "action=upload&uploadKey={$uploadKey}&checkTime=".time();
				$key = urlencode($this->jakey_encrypt($key, md5('1218787810')));
				
				$url = JURI::root()."administrator/components/{$this->_component}/cron.php?key=".$key;
				
				
				$body = JResponse::getBody();
				$scriptUpload = '
				<script type="text/javascript" language="javascript">
				  /*<![CDATA[*/
				  window.addEvent("load", function(){
				  	var myAjax = new Ajax("'.$url.'", {method: "get"}).request();
				  });
				  /*]]>*/
				</script>
				';
				
				$body = JResponse::getBody();
				$body = str_replace('</body>', $scriptUpload . "\r\n". '</body>', $body);
				JResponse::setBody($body);
			}
		}
	}
	
	function onAfterRender() {
		global $app;
		global $option;

		$apply_admin = (int) $this->plgParams->get('apply_admin', 0);
		if ($app->isAdmin() && !$apply_admin) {
			return;
		}

		//$listProfiles = $this->getActiveProfiles();
		
		//if(is_array($listProfiles) && count($listProfiles)) {
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
			
			$currUrl = 'http://hoodukutest.blob.core.windows.net/';
			$body = preg_replace("/(href|src)\s*=\s*(\'|\")((?!(?:\w+)\:\/*)[^\/\#][^\'\"]*)(\'|\")/i", '$1=$2'.$currUrl.'$3$4', $body);
			//$body = str_replace('http://hoodukutest.blob.core.windows.net/images/', 'http://hoodukutest.blob.core.windows.net/', $body);
			//foreach ($listProfiles as $profile) {
			//	$body = $this->_replaceDistributeUrls($profile, $body);
			//}
			JResponse::setBody($body);
		//}
		
		//$this->uploadFrontend();
		//$this->cronJobUpload();
	}
	
	function _replaceDistributeUrls($profile, $body) {
		
		if(is_object($profile) && isset($profile->bucket_id) && isset($profile->bucket_acl) && ($profile->bucket_acl != 'private')) {
			
			$protocol = $this->plgParams->get('protocol', 'http');
			if(isset($profile->bucket_cloudfront_domain) && !empty($profile->bucket_cloudfront_domain)) {
				//is integrated cloud front service
				$distributeUrl = $profile->bucket_cloudfront_domain;
			} elseif(isset($profile->bucket_vhost) && !empty($profile->bucket_vhost)) {
				$distributeUrl = $profile->bucket_vhost;
			} else {
				$distributeUrl = sprintf($this->s3url, $protocol, $profile->bucket_name);
			}
			//correct distribute url
			if(!preg_match("/^(?:http|https)\:/", $distributeUrl)) {
				$distributeUrl = $protocol."://".$distributeUrl;
			}
			$distributeUrl = rtrim($distributeUrl, '/') . '/';
			
			//
			$this->distribute_url = rtrim($distributeUrl, '\\/');
			$this->active_bucket_id = $profile->bucket_id;
			$findUrls = $profile->site_url;
			
			/***********************************/			
			//UPDATE LINKS FOR EXCEPTION LIST
			/***********************************/
			$exceptionList = $this->getListItemDisabled($profile);
			//print_r($exceptionList);
			if(count($exceptionList)) {
				foreach ($exceptionList as $url) {
					$body = str_replace($url, $this->lockUrl($url), $body);
				}
			}
			/***********************************/
			//MAKE REPLACE PATTERN
			/***********************************/
			//get allowed extensions from component' configuration
			$exts = $profile->allowed_extension;
			$aExts = explode(",", $exts);
			if(count($aExts) >= 1) {
				$exts= "(?:".implode("|", $aExts).")";
				
				//support multi urls to replaced
				//each url is separte on one line
				$aFindUrl = preg_split("/\r*\n/", $findUrls);
				if(count($aFindUrl)) {
					foreach ($aFindUrl as $findUrl) {
						//remove white spaces
						$findUrl = preg_replace("/\s*/", '', $findUrl);
						if(empty($findUrl)) {
							continue;
						}
						$findUrl = $this->cleanUrl($findUrl);
						//escape Regex characters
						$findUrl = preg_replace("/([\:\-\/\.\?\(\)\[\]\{\}])/", "\\\\$1", $findUrl);
						/**
						 * support urls is inputed like simple regex format:
						 * http://*.joomlart.com
						 * '*' is can be a sub domain, www, www2, ...
						 */
						$findUrl = str_replace('*', '[a-zA-Z0-9\.\_\-]*?', $findUrl);
						//filter urls by allowed extensions
						$pattern = "/{$findUrl}([^\'\"\)\?]*?\.{$exts})/";
						//echo "<pre>$pattern</pre>";
						
						/*preg_match_all($pattern, $body, $matches);
						print_r($matches);
						die();*/
						$body = preg_replace_callback($pattern, array($this, 'convertUrl'), $body);
						
					}
					/***********************************/			
					//CORRECT LINKS FOR EXCEPTION LIST
					/***********************************/
					$body = $this->unlockUrl($body);
				}
			}
		}
		return $body;
	}

	function getActiveProfiles() {
		$db = &JFactory::getDBO();
		$query = "
			SELECT 
				a.acc_label,
				a.acc_name,
				a.acc_accesskey,
				a.acc_secretkey,
				b.acc_id, 
				b.bucket_name, 
				b.bucket_acl, 
				b.bucket_cloudfront_domain,
				p.* 
			FROM #__jaamazons3_profile AS p
			INNER JOIN #__jaamazons3_bucket b ON b.id = p.bucket_id 
			INNER JOIN #__jaamazons3_account a ON b.acc_id = a.id 
			WHERE p.profile_status = 1
			";
		$db->setQuery( $query );
		$list = $db->loadObjectList();
		return $list;
	}
	
	function getListItemDisabled($profile) {
		$list = array();
			
		if($profile !== false) {
			$db = JFactory::getDBO();
			//order by path to save efforts when reduce list
			$query = "
				SELECT `path` FROM #__jaamazons3_disabled
				WHERE `profile_id` = '{$profile->id}'
				ORDER BY `path`
				";
			$db->setQuery($query);
			$listDisabled = $db->loadObjectList();
			
			$baseUrl = $profile->site_url;
			$aFindUrl = preg_split("/\r*\n/", $baseUrl);
			if(count($listDisabled)) {
				foreach ($listDisabled as $item) {
					foreach ($aFindUrl as $findUrl) {
						$url = $this->cleanUrl($findUrl."/".$item->path);
						$list = $this->reduceDisabledItems($list, $url);
					}
				}
			}
		}
		return $list;
	}
	
	/**
	 * if one folder is disabled,
	 * we don't need take care to all items on this folder
	 *
	 */
	function reduceDisabledItems($list, $addUrl) {
		$found = 0;
		if(count($list)) {
			foreach ($list as $id => $url) {
				if(strpos($url, $addUrl) === 0) {
					// $url is a file on $addUrl
					$list[$id] = $addUrl;
					$found = 1;
					break;
				} elseif (strpos($addUrl, $url) === 0) {
					// $addUrl is a file on $url
					$found = 1;
					break;
				}
			}
		}
		if(!$found) {
			$list[] = $addUrl;
		}
		return $list;
	}
	
	function cleanUrl($url) {
		$url = trim($url);
		$url = str_replace("{juri_root}", rtrim(JURI::root(), '\\/')."/", $url);
		$url = preg_replace("/([^\:]{1})[\/\\\\]+/", "$1/", $url);
		return $url;
	}
	
	/**
	 * lock a disabled urls to not replace them with s3 url
	 *
	 * @param (string) $url
	 */
	function lockUrl($url) {
		return preg_replace("/((?:\w+))\:\/+/i", "$1://[locked]", $url);
	}
	
	function unlockUrl($url) {
		return preg_replace("/((?:\w+))\:\/+\[locked\]/i", "$1://", $url);
	}
	
	function convertUrl($matches) {
		static $aCheck = array();
		static $db;
		if(!is_object($db)) {
			$db = JFactory::getDBO();
		}
		$item = ltrim(JPath::clean($matches[1], '/'), '\\/');
		
		if(!isset($aCheck[$item])) {
			$sql = "SELECT last_update FROM #__jaamazons3_file WHERE bucket_id = '".$this->active_bucket_id."' AND path = '{$item}'";
			$db->setQuery($sql);
			$log = $db->loadObject();
			$aCheck[$item] = is_object($log);
		}
		
		//if item is not uploaded to s3 => using original url
		$url = ($aCheck[$item]) ? $this->distribute_url.'/'.$item : $matches[0];
		
		return $url;
	}

	function jakey_encrypt($string, $key) {
		if(function_exists('mcrypt_module_open')) {
			$td = mcrypt_module_open('des', '', 'ecb', '');
			$iv_size = mcrypt_enc_get_iv_size($td);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	
			$ks = mcrypt_enc_get_key_size($td);
	
			/* Create key */
			$key = substr($key, 0, $ks);
	
			/* Intialize encryption */
			mcrypt_generic_init($td, $key, $iv);
	
			/* Encrypt data */
			$encrypted = mcrypt_generic($td, $string);
	
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
	
			return base64_encode($encrypted);
		} else {
			return base64_encode($string);
		}
	}
}
?>