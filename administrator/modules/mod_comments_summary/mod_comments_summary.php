<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_comments_summary
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_comments'));

// if the JXtended Libraries are not present exit gracefully
if (!defined('JXVERSION')) {
	JError::raiseNotice(500, JText::_('JX_LIBRARIES_MISSING'));
	return false;
}

// import library dependencies
require_once(dirname(__FILE__).DS.'helper.php');

// get the user object
$user = &JFactory::getUser();

// get the document object
$document = &JFactory::getDocument();

// get the base url
$baseurl = JURI::base();

// get the item list
$list = modCommentsSummaryHelper::getList($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_comments_summary', $params->get('layout', 'default')));
