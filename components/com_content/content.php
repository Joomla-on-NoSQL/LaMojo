<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.DS.'router.php';
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'query.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'route.php');

$controller = JController::getInstance('Content');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
