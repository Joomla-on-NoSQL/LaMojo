<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Messages
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once(JApplicationHelper::getPath('admin_html'));

$task	= JRequest::getCmd('task');
$cid	= JRequest::getVar('cid', array(0), '', 'array');
JArrayHelper::toInteger($cid, array(0));

switch ($task)
{
	case 'view':
		viewMessage($cid[0], $option);
		break;

	case 'add':
		newMessage($option, NULL, NULL);
		break;

	case 'reply':
		newMessage(
			$option,
			JRequest::getVar('userid', 0, '', 'int'),
			JRequest::getString('subject')
		);
		break;

	case 'save':
		saveMessage($option);
		break;

	case 'remove':
		removeMessage($cid, $option);
		break;

	case 'config':
		editConfig($option);
		break;

	case 'saveconfig':
		saveConfig($option);
		break;

	default:
		showMessages($option);
		break;
}

function showMessages($option)
{
	global $mainframe;

	$db					= &JFactory::getDbo();
	$user 				= &JFactory::getUser();

	$context			= 'com_messages.list';
	$filter_order		= $mainframe->getUserStateFromRequest($context.'.filter_order',	'filter_order',		'a.date_time',	'cmd');
	$filter_order_Dir	= $mainframe->getUserStateFromRequest($context.'.filter_order_Dir','filter_order_Dir',	'DESC',			'word');
	$filter_state		= $mainframe->getUserStateFromRequest($context.'.filter_state',	'filter_state',		'',				'word');
	$limit				= $mainframe->getUserStateFromRequest('global.list.limit',			'limit',			$mainframe->getCfg('list_limit'), 'int');
	$limitstart			= $mainframe->getUserStateFromRequest($context.'.limitstart',		'limitstart',		0,				'int');
	$search				= $mainframe->getUserStateFromRequest($context.'search',			'search',			'',				'string');
	$search				= JString::strtolower($search);

	$where = array();
	$where[] = ' a.user_id_to='.(int) $user->get('id');

	if ($search != '') {
		$searchEscaped = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
		$where[] = '(a.subject LIKE '.$searchEscaped.' OR a.message LIKE '.$searchEscaped.')';
	}
	if ($filter_state) {
		if ($filter_state == 'P') {
			$where[] = 'a.state = 1';
		} else if ($filter_state == 'U') {
			$where[] = 'a.state = 0';
		}
	}

	$where 		= (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
	$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', a.date_time DESC';

	$query = 'SELECT COUNT(*)'
	. ' FROM #__messages AS a'
	. ' INNER JOIN #__users AS u ON u.id = a.user_id_from'
	. $where
	;
	$db->setQuery($query);
	$total = $db->loadResult();

	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = 'SELECT a.*, u.name AS user_from'
	. ' FROM #__messages AS a'
	. ' INNER JOIN #__users AS u ON u.id = a.user_id_from'
	. $where
	. $orderby
	;
	$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
	$rows = $db->loadObjectList();
	if ($db->getErrorNum()) {
		echo $db->stderr();
		return false;
	}

	// state filter
	$lists['state']	= JHtml::_('grid.state',  $filter_state, 'Read', 'Unread');

	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;

	// search filter
	$lists['search']= $search;

	HTML_messages::showMessages($rows, $pageNav, $option, $lists);
}

function editConfig($option)
{
	$db		= &JFactory::getDbo();
	$user	= &JFactory::getUser();

	$query = 'SELECT cfg_name, cfg_value'
	. ' FROM #__messages_cfg'
	. ' WHERE user_id = '.(int) $user->get('id')
	;
	$db->setQuery($query);
	$data = $db->loadObjectList('cfg_name');

	// initialize values if they do not exist
	if (!isset($data['lock']->cfg_value)) {
		$data['lock']->cfg_value 		= 0;
	}
	if (!isset($data['mail_on_new']->cfg_value)) {
		$data['mail_on_new']->cfg_value = 0;
	}
	if (!isset($data['auto_purge']->cfg_value)) {
		$data['auto_purge']->cfg_value 	= 7;
	}

	$vars 					= array();
	$vars['lock'] 			= JHtml::_('select.booleanlist',  "vars[lock]", '', $data['lock']->cfg_value, 'yes', 'no', 'varslock');
	$vars['mail_on_new'] 	= JHtml::_('select.booleanlist',  "vars[mail_on_new]", '', $data['mail_on_new']->cfg_value, 'yes', 'no', 'varsmail_on_new');
	$vars['auto_purge'] 	= $data['auto_purge']->cfg_value;

	HTML_messages::editConfig($vars, $option);

}

function saveConfig($option)
{
	global $mainframe;

	// Check for request forgeries
	JRequest::checkToken() or jexit('Invalid Token');

	$db		= &JFactory::getDbo();
	$user	= &JFactory::getUser();

	$query = 'DELETE FROM #__messages_cfg'
	. ' WHERE user_id = '.(int) $user->get('id')
	;
	$db->setQuery($query);
	$db->query();

	$vars = JRequest::getVar('vars', array(), 'post', 'array');
	foreach ($vars as $k=>$v) {
		$v = $db->getEscaped($v);
		$query = 'INSERT INTO #__messages_cfg'
		. ' (user_id, cfg_name, cfg_value)'
		. ' VALUES ('.(int) $user->get('id').', '.$db->Quote($k).', '.$db->Quote($v).')'
		;
		$db->setQuery($query);
		$db->query();
	}
	$mainframe->redirect("index.php?option=$option");
}

function newMessage($option, $user, $subject)
{
	$access	= &JFactory::getACL();
	$groups	= array();

	// Include user in groups that have access to log in to the administrator.
	$return = $access->getAuthorisedUsergroups('core.administrator.login', true);
	if (count($return)) {
		$groups = array_merge($groups, $return);
	}

	// Remove duplicate entries and serialize.
	JArrayHelper::toInteger($groups);
	$groups = implode(',', array_unique($groups));

	// Build the query to get the users.
	$query = new JQuery();
	$query->select('u.id AS value');
	$query->select('u.name AS text');
	$query->from('#__users AS u');
	$query->join('INNER', '#__user_usergroup_map AS m ON m.user_id = u.id');
	$query->where('u.block = 0');
	$query->where('m.group_id IN ('.$groups.')');

	// Get the users.
	$db = &JFactory::getDBO();
	$db->setQuery($query->toString());
	$users = $db->loadObjectList();

	// Check for a database error.
	if ($db->getErrorNum()) {
		JError::raiseNotice(500, $db->getErrorMsg());
		return false;
	}

	// Build the options.
	$options = array(JHtml::_('select.option',  '0', '- '. JText::_('Select User') .' -'));

	if (count($users)) {
		$options = array_merge($options, $users);
	}

	$list = JHtml::_('select.genericlist', $options, 'user_id_to', 'class="inputbox" size="1"', 'value', 'text', $user);

	HTML_messages::newMessage($option, $list, $subject);
}

function saveMessage($option)
{
	global $mainframe;

	// Check for request forgeries
	JRequest::checkToken() or jexit('Invalid Token');

	require_once(dirname(__FILE__).DS.'tables'.DS.'message.php');

	$db = &JFactory::getDbo();
	$row = new TableMessage($db);

	if (!$row->bind(JRequest::get('post'))) {
		JError::raiseError(500, $row->getError());
	}

	if (!$row->check()) {
		JError::raiseError(500, $row->getError());
	}

	if (!$row->send()) {
		$mainframe->redirect("index.php?option=com_messages", $row->getError());
	}
	$mainframe->redirect("index.php?option=com_messages");
}

function viewMessage($uid='0', $option)
{
	$db	= &JFactory::getDbo();

	$query = 'SELECT a.*, u.name AS user_from'
	. ' FROM #__messages AS a'
	. ' INNER JOIN #__users AS u ON u.id = a.user_id_from'
	. ' WHERE a.message_id = '.(int) $uid
	. ' ORDER BY date_time DESC'
	;
	$db->setQuery($query);
	$row = $db->loadObject();

	$query = 'UPDATE #__messages'
	. ' SET state = 1'
	. ' WHERE message_id = '.(int) $uid
	;
	$db->setQuery($query);
	$db->query();

	HTML_messages::viewMessage($row, $option);
}

function removeMessage($cid, $option)
{
	global $mainframe;

	// Check for request forgeries
	JRequest::checkToken() or jexit('Invalid Token');

	$db = &JFactory::getDbo();

	JArrayHelper::toInteger($cid);

	if (count($cid) < 1) {
		JError::raiseError(500, JText::_('Select an item to delete'));
	}

	if (count($cid))
	{
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__messages'
		. ' WHERE message_id IN ('. $cids .')'
		;
		$db->setQuery($query);
		if (!$db->query()) {
			echo "<script> alert('".$db->getErrorMsg(true)."'); window.history.go(-1); </script>\n";
		}
	}

	$limit 		= JRequest::getVar('limit', 10, '', 'int');
	$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

	$mainframe->redirect('index.php?option='.$option.'&limit='.$limit.'&limitstart='.$limitstart);
}
