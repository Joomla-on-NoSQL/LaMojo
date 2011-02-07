<?php
/**
 * @version		$Id: mod_whosonline.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	mod_whosonline
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the whosonline functions only once
require_once dirname(__FILE__).DS.'helper.php';

$showmode = $params->get('showmode', 0);

if ($showmode == 0 || $showmode == 2) {
	$count	= modWhosonlineHelper::getOnlineCount();
}

if ($showmode > 0) {
	$names	= modWhosonlineHelper::getOnlineUserNames();
}
$linknames = $params->get('linknames', 0);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_whosonline', $params->get('layout', 'default'));
