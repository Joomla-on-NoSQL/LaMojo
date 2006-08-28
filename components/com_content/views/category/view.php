<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Content
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

jimport( 'joomla.application.view');

/**
 * HTML View class for the Content component
 *
 * @package Joomla
 * @subpackage Content
 * @since 1.5
 */
class ContentViewCategory extends JView
{
	/**
	 * Name of the view.
	 *
	 * @access	private
	 * @var		string
	 */
	var $_viewName = 'Category';

	/**
	 * Display the document
	 */
	function display($layout)
	{
		$document	= & JFactory::getDocument();
		switch ($document->getType())
		{
			case 'feed':
				$this->_displayFeed();
				break;
			default:
				$this->_displayHTML($layout);
				break;
		}
	}

	/**
	 * Name of the view.
	 *
	 * @access	private
	 * @var		string
	 */
	function _displayHTML($layout)
	{
		global $mainframe, $option, $Itemid;

		// Initialize some variables
		$user	  =& JFactory::getUser();
		$document =& JFactory::getDocument();
		$pathway  = & $mainframe->getPathWay();

		// Get the menu object of the active menu item
		$menus	 =& JMenu::getInstance();
		$menu	 =& $menus->getItem($Itemid);
		$params  =& $menus->getParams($Itemid);
		
		// Request variables
		$task 	    = JRequest::getVar('task');
		$limit		= JRequest::getVar('limit', $params->get('display_num'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		// Get some data from the model
		$items	  = & $this->get( 'Content' );
		$category = & $this->get( 'Category' );
		$category->total = count($items);

		//add alternate feed link
		$link    = JURI::base() .'feed.php?option=com_content&task='.$task.'&id='.$category->id.'&Itemid='.$Itemid;
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
		$document->addHeadLink($link.'&format=rss', 'alternate', 'rel', $attribs);
		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
		$document->addHeadLink($link.'&format=atom', 'alternate', 'rel', $attribs);

		// Create a user access object for the user
		$access					= new stdClass();
		$access->canEdit		= $user->authorize('action', 'edit', 'content', 'all');
		$access->canEditOwn		= $user->authorize('action', 'edit', 'content', 'own');
		$access->canPublish		= $user->authorize('action', 'publish', 'content', 'all');
		
		// Section
		$pathway->addItem($category->sectiontitle, sefRelToAbs('index.php?option=com_content&amp;task=section&amp;id='.$category->sectionid.'&amp;Itemid='.$Itemid));
		// Category
		$pathway->addItem($category->title, '');

		$mainframe->setPageTitle($menu->name);
		
		$intro		= $params->def('intro', 	4);
		$leading	= $params->def('leading', 	1);
		$links		= $params->def('link', 		4);
		
		$params->def('title',			1);
		$params->def('hits',			$mainframe->getCfg('hits'));
		$params->def('author',			!$mainframe->getCfg('hideAuthor'));
		$params->def('date',			!$mainframe->getCfg('hideCreateDate'));
		$params->def('date_format',		JText::_('DATE_FORMAT_LC'));
		$params->def('navigation',		2);
		$params->def('display',			1);
		$params->def('display_num',		$mainframe->getCfg('list_limit'));
		$params->def('empty_cat',		0);
		$params->def('cat_items',		1);
		$params->def('cat_description',0);
		$params->def('pageclass_sfx',	'');
		$params->def('headings',		1);
		$params->def('filter',			1);
		$params->def('filter_type',		'title');
		$params->set('intro_only', 		1);
		
		if ($params->def('page_title', 1)) {
			$params->def('header', $menu->name);
		}
		
		$limit	= $intro + $leading + $links;
		$i		= $limitstart;
		
		jimport('joomla.presentation.pagination');
		$pagination = new JPagination(count($items), $limitstart, $limit);
		$link = 'index.php?option=com_content&amp;task=category&amp;sectionid='.$category->sectionid.'&amp;id='.$category->id.'&amp;Itemid='.$Itemid;
		
		$request = new stdClass();
		$request->limit	 		= $limit;
		$request->limitstart	= $limitstart;
		
		$data = new stdClass();
		$data->link = $link;
		
		$this->set('data'      , $data);
		$this->set('items'     , $items);
		$this->set('request'   , $request);
		$this->set('category'  , $category);
		$this->set('params'    , $params);
		$this->set('user'      , $user);
		$this->set('access'    , $access);
		$this->set('pagination', $pagination);

		$this->_loadTemplate($layout);
	}
	
	function icon(&$article, $type, $attribs = array())
	{	
		global $Itemid, $mainframe;
		 
		$url  = '';
		$text = '';
		
		switch($type)
		{
			case 'new' : 
			{
				$url = 'index.php?option=com_content&amp;task=new&amp;sectionid='.$article->sectionid.'&amp;Itemid='.$Itemid;

				if ($this->params->get('icons')) {
					$text = mosAdminMenus::ImageCheck('new.png', '/images/M_images/', NULL, NULL, JText::_('New'), JText::_('New'). $article->id );
				} else {
					$text = JText::_('New').'&nbsp;';
				}
				
				$attribs['title']   = JText::_( 'New' );
				
			} break;
			
			case 'edit' :
			{
				if ($this->params->get('popup')) {
					return;
				}
				if ($article->state < 0) {
					return;
				}
				if (!$this->access->canEdit && !($this->access->canEditOwn && $article->created_by == $this->user->get('id'))) {
					return;
				}

				mosCommonHTML::loadOverlib();

				$url = 'index.php?option=com_content&amp;task=edit&amp;id='.$article->id.'&amp;Itemid='.$Itemid.'&amp;Returnid='.$Itemid;
				$text = mosAdminMenus::ImageCheck('edit.png', '/images/M_images/', NULL, NULL, JText::_('Edit'), JText::_('Edit'). $article->id );

				if ($item->state == 0) {
					$overlib = JText::_('Unpublished');
				} else {
					$overlib = JText::_('Published');
				}
				$date = mosFormatDate($article->created);
				$author = $item->created_by_alias ? $article->created_by_alias : $article->author;

				$overlib .= '<br />';
				$overlib .= $article->groups;
				$overlib .= '<br />';
				$overlib .= $date;
				$overlib .= '<br />';
				$overlib .= $author;
				
				$attribs['onmouseover'] = "return overlib('".$overlib."', CAPTION, '".JText::_( 'Edit Item' )."', BELOW, RIGHT)";
				$attribs['onmouseover'] = "return nd();";		
				
			} break;
		}
		
		
		echo mosHTML::Link($url, $text, $attribs);
	}

	function items()
	{
		global $mainframe, $Itemid;
		
		if (!count( $this->items ) ) {
			return;
		}
		
		//create select lists
		$lists	= $this->_buildSortLists();

		//create paginatiion
		if ($lists['filter']) {
			$this->data->link .= '&amp;filter='.$lists['filter'];
		}
	
		$k = 0;
		for($i = 0; $i <  count($this->items); $i++)
		{
			$item =& $this->items[$i];

			$item->link    = sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.$item->id.'&amp;Itemid='.$Itemid);
			$item->created = mosFormatDate($item->created, $this->params->get('date_format'));
			
			$item->odd   = $k;
			$item->count = $i;
			$k = 1 - $k;
		}
		
		$this->set('lists'     , $lists);
		
		$this->_loadTemplate('table_items');
	}

	function _buildSortLists()
	{
		/*
		 * Table ordering values
		 */
		$filter				= JRequest::getVar('filter');
		$filter_order		= JRequest::getVar('filter_order');
		$filter_order_Dir	= JRequest::getVar('filter_order_Dir');
		$lists['task'] = 'category';
		$lists['filter'] = $filter;
		if ($filter_order_Dir == 'DESC')
		{
			$lists['order_Dir'] = 'ASC';
		}
		else
		{
			$lists['order_Dir'] = 'DESC';
		}
		$lists['order'] = $filter_order;

		return $lists;
	}

	function item( $i )
	{
		require_once( JPATH_COMPONENT . '/helpers/article.php' );
		JContentArticleHelper::showItem( $this, $this->items[$i], $this->access, true );
	}

	function links( $index )
	{
		global $Itemid;

		$this->links = array_splice($this->items, $index);

		for($i = 0; $i < count($this->links); $i++)
		{
			$link =& $this->links[$i];

			$Itemid	    = JContentHelper::getItemid($link->id);
			$link->link	= sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id='.$link->id.'&amp;Itemid='.$Itemid);
		}


		$this->_loadTemplate('blog_links');
	}

	/**
	 * Name of the view.
	 *
	 * @access	private
	 * @var		string
	 */
	function _displayFeed()
	{
		global $mainframe;

		$doc =& JFactory::getDocument();

		// Get some data from the model
		$rows  = & $this->get( 'Content' );
		$limit = '10';

		JRequest::setVar('limit', $limit);
		$category = & $this->get( 'Category' );
		$rows 	  = & $this->get( 'Content' );

		foreach ( $rows as $row )
		{
			// strip html from feed item title
			$title = htmlspecialchars( $row->title );
			$title = html_entity_decode( $title );

			// url link to article
			// & used instead of &amp; as this is converted by feed creator
			$itemid = JContentHelper::getItemid( $row->id );
			if ($itemid) {
				$_Itemid = '&Itemid='. $itemid;
			}

			$link = 'index.php?option=com_content&task=view&id='. $row->id . $_Itemid;
			$link = sefRelToAbs( $link );

			// strip html from feed item description text
			$description = $row->introtext;
			@$date = ( $row->created ? date( 'r', $row->created ) : '' );

			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= $title;
			$item->link 		= $link;
			$item->description 	= $description;
			$item->date			= $date;
			$item->category   	= $category->title;

			// loads item info into rss array
			$doc->addItem( $item );
		}
	}
}
?>