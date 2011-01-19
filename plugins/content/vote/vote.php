<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Vote plugin.
 *
 * @package		Joomla
 * @subpackage	plg_vote
 */
class plgContentVote extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	* @since	1.6
	*/
	public function onContentBeforeDisplay($context, &$row, &$params, $page=0)
	{
		$html = '';

		if ($params->get('show_vote')) {
			$rating = intval(@$row->rating);
			$rating_count = intval(@$row->rating_count);

			$view = JRequest::getString('view', '');
			$img = '';
			$buttons = '';

			// look for images in template if available
			$starImageOn = JHTML::_('image','system/rating_star.png', NULL, NULL, true);
			$starImageOff = JHTML::_('image','system/rating_star_blank.png', NULL, NULL, true);

			for ($i=0; $i < $rating; $i++) {
				$img .= $starImageOn;
				$value = $i+1;
				$buttons .= '<input type="submit" title="'.JText::sprintf('PLG_VOTE_VOTE', $value).'" name="user_rating" class="vote-button star-on" value="'.$value.'" />';
			}
			for ($i=$rating; $i < 5; $i++) {
				$img .= $starImageOff;
				$value = $i+1;
				$buttons .= '<input type="submit" title="'.JText::sprintf('PLG_VOTE_VOTE', $value).'" name="user_rating" class="vote-button star-off" value="'.$value.'" />';
			}

			if ( $view == 'article' && $row->state == 1) {
				JFactory::getDocument()->addScript(JURI::base().'/media/plg_content_vote/vote.js');
				$uri = JFactory::getURI();
				$uri->setQuery($uri->getQuery().'&hitcount=0');

				$html .= '<form method="post" action="' . $uri->toString() . '">';
				$html .= '<div id="content-vote">';
				$html .= '<span id="content-rating">';
				$html .= JText::_( 'PLG_VOTE_USER_RATING' ) .':&#160;'. $buttons .'&#160;/&#160;';
				$html .= '<span id="rating-count">'.$rating_count.'</span>';
				$html .= "</span>\n";
				$html .= '<input type="hidden" name="task" value="article.vote" />';
				$html .= '<input type="hidden" name="hitcount" value="0" />';
				$html .= '<input type="hidden" name="url" value="'.  $uri->toString() .'" />';
				$html .= JHtml::_('form.token');
				$html .= '</div>';
				$html .= '</form>';
			} else {
				$html .= '<span class="content_rating">';
				$html .= JText::_( 'PLG_VOTE_USER_RATING' ) .':&#160;'. $img .'&#160;/&#160;';
				$html .= $rating_count;
				$html .= "</span>\n<br />\n";
			}
		}

		return $html;
	}
}
