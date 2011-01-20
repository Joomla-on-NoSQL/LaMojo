<?php
/**
 * @version		$Id: mod_weblinks.php 19979 2010-12-25 04:08:13Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	mod_related_items
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the weblinks functions only once
require_once dirname(__FILE__).DS.'helper.php';

$list = modWeblinksHelper::getList($params);

if (!count($list)) {
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_weblinks',$params->get('layout', 'default'));
