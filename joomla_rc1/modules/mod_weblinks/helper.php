<?php
/**
 * @version		$Id: helper.php 19068 2010-10-09 13:29:01Z chdemko $
 * @package		Joomla.Site
 * @subpackage	mod_related_items
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.DS.'components'.DS.'com_weblinks'.DS.'helpers'.DS.'route.php';
JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_weblinks'.DS.'models');

class modWeblinksHelper
{
	function getList($params)
	{

		// Get an instance of the generic articles model
		$model = JModel::getInstance('Category', 'WeblinksModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));

		$model->setState('filter.state', 1);
		$model->setState('filter.archived', 0);
		$model->setState('filter.approved', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_weblinks')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		$model->setState('list.ordering', 'title');
		$model->setState('list.direction', 'asc');

		$catid	= (int) $params->get('catid', 0);
		$model->setState('category.id', $catid);

    $db = &JFactory::getDbo();
    $query = $db->getQuery(true);
    //sqlsrv changes
    $case_when = ' CASE WHEN ';
    $case_when .= $query->charLength('a.alias');
    $case_when .= ' THEN ';
    $a_id = $query->castToChar('a.id');
    $case_when .= $query->concat(array($a_id, 'a.alias'), ':');
    $case_when .= ' ELSE ';
    $case_when .= $a_id.' END as slug'; 
    
    $case_when1 = ' CASE WHEN ';
    $case_when1 .= $query->charLength('c.alias');
    $case_when1 .= ' THEN ';
    $c_id = $query->castToChar('c.id');
    $case_when1 .= $query->concat(array($c_id, 'c.alias'), ':');
    $case_when1 .= ' ELSE ';
    $case_when1 .= $c_id.' END as catslug'; 
    
		$model->setState('list.select', 'a.*, c.published AS c_published,
		'.$case_when.','.$case_when1.',
		DATE_FORMAT(a.date, "%Y-%m-%d") AS created');

		$model->setState('filter.c.published', 1);

		// Filter by language
		$model->setState('filter.language',$app->getLanguageFilter());

		$items = $model->getItems();

		/*
		 * This was in the previous code before we changed over to using the
		 * weblinkscategory model but I don't see any models using checked_out filters
		 * in their getListQuery() methods so I believe we should not be adding this now
		 */

		/*
		 $query->where('(a.checked_out = 0 OR a.checked_out = '.$user->id.')');
		 */
		for ($i =0; $i < count($items); $i++) {
			$item = &$items[$i];
			if ($item->params->get('count_clicks', $params->get('count_clicks')) == 1) {
				$item->link	= JRoute::_('index.php?task=weblink.go&catid='.$item->catslug.'&id='. $item->slug);
			} else {
				$item->link = $item->url;
			}
		}
		return $items;

	}
}