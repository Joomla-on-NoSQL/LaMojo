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

class JContentArticleHelper
{
	function showItem( &$parent, &$article, &$access, $showImages = false)
	{
		global $mainframe;

		// Initialize some variables
		$user		=& JFactory::getUser();
		$linkOn		= null;
		$linkText	= null;

		// These will come from a request object at some point
		$task   = JRequest::getVar('task');
		$noJS   = JRequest::getVar('hide_js', 0, '', 'int');
		$Itemid = JRequest::getVar('Itemid');

		// Get the paramaters of the active menu item
		$menus  =& JMenu::getInstance();
		$params =& $menus->getParams($Itemid);

		// TODO: clean this part up
		$SiteName = $mainframe->getCfg('sitename');
		$gid = $user->get('gid');

		// Get some global parameters
		$params->def('link_titles', $mainframe->getCfg('link_titles'));
		$params->def('author', !$mainframe->getCfg('hideAuthor'));
		$params->def('createdate', !$mainframe->getCfg('hideCreateDate'));
		$params->def('modifydate', !$mainframe->getCfg('hideModifyDate'));
		$params->def('print', !$mainframe->getCfg('hidePrint'));
		$params->def('pdf', !$mainframe->getCfg('hidePdf'));
		$params->def('email', !$mainframe->getCfg('hideEmail'));
		$params->def('rating', $mainframe->getCfg('vote'));
		$params->def('icons', $mainframe->getCfg('icons'));
		$params->def('readmore', $mainframe->getCfg('readmore'));
		$params->def('back_button', $mainframe->getCfg('back_button'));
		$params->set('intro_only', 1);

		// Get some article specific parameters
		$params->def('image', 1);
		$params->def('section', 0);
		$params->def('section_link', 0);
		$params->def('category', 0);
		$params->def('category_link', 0);
		$params->def('introtext', 1);
		$params->def('pageclass_sfx', '');
		$params->def('item_title', 1);
		$params->def('url', 1);

		if (!$showImages) {
			$params->set('image', 0);
		}

		// Process the content plugins
		$article->text = $article->introtext;
		JPluginHelper::importPlugin('content');
		$results = $mainframe->triggerEvent('onPrepareContent', array (& $article, & $params, 0));

		// Build the link and text of the readmore button
		if (($params->get('readmore') && @ $article->readmore) || $params->get('link_titles')) {
			if ($params->get('intro_only')) {
				// Check to see if the user has access to view the full article
				if ($article->access <= $gid) {
					$Itemid = JContentHelper::getItemid($article->id);
					$linkOn = sefRelToAbs("index.php?option=com_content&amp;task=view&amp;id=".$article->id."&amp;Itemid=".$Itemid);
					$linkText = JText::_('Read more...');
				} else {
					$linkOn = sefRelToAbs("index.php?option=com_registration&amp;task=register");
					$linkText = JText::_('Register to read more...');
				}
			}
		}

		// Display the edit icon if appropriate
		if ($access->canEdit) {
			?>
			<div class="contentpaneopen_edit<?php echo $params->get( 'pageclass_sfx' ); ?>" style="float: left;">
				<?php JContentHTMLHelper::editIcon($article, $params, $access); ?>
			</div>
			<?php
		}

		if ($params->get('item_title') || $params->get('pdf') || $params->get('print') || $params->get('email')) {
			// link used by print button
			$printLink = JURI::base().'index2.php?option=com_content&amp;task=view&amp;id='.$article->id.'&amp;Itemid='.$Itemid.'&amp;pop=1';
			?>
			<table class="contentpaneopen<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<tr>
			<?php

			// displays Item Title
			JContentHTMLHelper::title($article, $params, $linkOn, $access);

			// displays PDF Icon
			JContentHTMLHelper::pdfIcon($article, $params, $linkOn, $noJS);

			// displays Print Icon
			mosHTML::PrintIcon($article, $params, $noJS, $printLink);

			// displays Email Icon
			JContentHTMLHelper::emailIcon($article, $params, $noJS);
			?>
			</tr>
			</table>
			<?php
		}

		// If only displaying intro, display the output from the onAfterDisplayTitle event
		if (!$params->get('intro_only')) {
			$results = $mainframe->triggerEvent('onAfterDisplayTitle', array (& $article, & $params, 0));
			echo trim(implode("\n", $results));
		}

		// Display the output from the onBeforeDisplayContent event
		$onBeforeDisplayContent = $mainframe->triggerEvent('onBeforeDisplayContent', array (& $article, & $params, 0));
		echo trim(implode("\n", $onBeforeDisplayContent));
		?>

		<table class="contentpaneopen<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<?php

		// displays Section & Category
		JContentHTMLHelper::sectionCategory($article, $params);

		// displays Author Name
		JContentHTMLHelper::author($article, $params);

		// displays Created Date
		JContentHTMLHelper::createDate($article, $params);

		// displays Urls
		JContentHTMLHelper::url($article, $params);
		?>
		<tr>
			<td valign="top" colspan="2">
		<?php

		// displays Table of Contents
		JContentHTMLHelper::toc($article);

		// displays Item Text
		echo ampReplace($article->text);
		?>
			</td>
		</tr>
		<?php

		// displays Modified Date
		JContentHTMLHelper::modifiedDate($article, $params);

		// displays Readmore button
		JContentHTMLHelper::readMore($params, $linkOn, $linkText);
		?>
		</table>
		<span class="article_seperator">&nbsp;</span>

		<?php
		// Fire the after display content event
		$onAfterDisplayContent = $mainframe->triggerEvent('onAfterDisplayContent', array (& $article, & $params, 0));
		echo trim(implode("\n", $onAfterDisplayContent));
	}
}
?>