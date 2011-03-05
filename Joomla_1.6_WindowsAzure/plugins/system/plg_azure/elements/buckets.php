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

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.filesystem.file');

class JElementBuckets extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'buckets';
	
	function fetchElement ( $name, $value, &$node, $control_name ){
		
		$db = &JFactory::getDBO();
		$query = '
			SELECT 
				c.acc_id,
				s.acc_label,
				c.id AS bucket_id,
				c.bucket_name 
			FROM #__jaamazons3_account AS s
			INNER JOIN #__jaamazons3_bucket c ON c.acc_id = s.id
			WHERE 1
			ORDER BY c.acc_id, c.bucket_name
			';
		$db->setQuery( $query );
		$cats = $db->loadObjectList();
		
		$HTMLCats=array();
		$HTMLCats[0]->id = '';
		$HTMLCats[0]->title = JText::_("Select Bucket");
		
		if(is_array($cats) && count($cats)) {
			$acc_id = 0;
			foreach ($cats as $cat) {
				if($acc_id != $cat->acc_id) {
					$acc_id = $cat->acc_id;
					
					$cat->id = $cat->acc_id;
					$cat->title = $cat->acc_label;
					$optgroup = JHTML::_('select.optgroup', $cat->title, 'id', 'title');
					array_push($HTMLCats, $optgroup);
				}
				$cat->id = $cat->bucket_id;
				$cat->title = $cat->bucket_name;
				array_push($HTMLCats, $cat);
			}
		} else {
			$comS3 = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jaamazons3'.DS.'admin.jaamazons3.php';
			if(!JFile::exists($comS3)) {
				$warning = "- This plugin requires the component JA Amazon S3 is installed.";
			} else {
				$warning = "- Please make sure you have got at least one bucket. You can create a bucket by component JA Amazon S3.";
			}
			JError::raiseWarning(400, JText::_($warning));
		}
		
		return JHTML::_('select.genericlist',  $HTMLCats, ''.$control_name.'['.$name.'][]', 'class="inputbox"', 'id', 'title', $value );
	}
}
?>