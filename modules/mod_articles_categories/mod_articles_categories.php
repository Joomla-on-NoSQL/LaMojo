<?php
/**
 * @version		$Id: mod_articles_categories.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the helper functions only once
require_once dirname(__FILE__).DS.'helper.php';

$list = modArticlesCategoriesHelper::getList($params);
if (!empty($list)) {
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	$startLevel = reset($list)->getParent()->level;
	require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default'));
}
