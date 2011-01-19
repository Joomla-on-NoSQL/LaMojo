<?php
/**
 * @version		$Id: article.json.php 20265 2011-01-10 23:49:25Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * - JSON Protocol -
 * 
 * @package		Joomla.Site
 * @subpackage	com_content
 */
class ContentControllerArticle extends JControllerForm
{
	/**
	 * Method to save a vote.
	 *
	 * @return	void
	 * @since	1.7
	 */
	function vote()
	{
		$user_rating = JRequest::getInt('user_rating', -1);

		if ( $user_rating > -1 ) {
			$id = JRequest::getInt('id', 0);
			$viewName = JRequest::getString('view', $this->default_view);
			$model = $this->getModel($viewName);

			$r = new JObject();
			if ($model->storeVote($id, $user_rating)) {
				$item = &$model->getItem($id);
				$r->rating = intval(@$item->rating);
				$r->rating_count = intval(@$item->rating_count);
				$r->message = JText::_('COM_CONTENT_ARTICLE_VOTE_SUCCESS');
			} else {
				$error = JError::getError();
				if ($error) {
					$this->sendJsonResponse($error, 500);
				} else {
					$this->sendJsonResponse(new JException(JText::_('COM_CONTENT_ARTICLE_VOTE_FAILURE'), 500));
				}
			}
			$this->sendJsonResponse($r);
		}
	}
}