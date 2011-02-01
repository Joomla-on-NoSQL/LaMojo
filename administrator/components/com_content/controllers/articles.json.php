<?php
/**
 * @version		$Id: articles.php 20228 2011-01-10 00:52:54Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Articles list controller class-
 * - JSON protocol =
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @since		1.7
 */
class ContentControllerArticles extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	ContentControllerArticles
	 * @see		JController
	 * @since	1.7
	 */
	public function __construct($config = array())
	{
		// Articles default form can come from the articles or featured view.
		// Adjust the redirect view on the value of 'view' in the request.
		if (JRequest::getCmd('view') == 'featured') {
			$this->view_list = 'featured';
		}
		parent::__construct($config);

		$this->registerTask('unfeatured',	'featured');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.7
	 */
	public function getModel($name = 'Article', $prefix = 'ContentModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	/**
	 * Check in of one or more records.
	 *
	 * @since	1.7
	 */
	public function checkin()
	{
		// Check for request forgeries.
		JRequest::checkToken() or $this->sendJsonResponse(new JException(JText::_('JINVALID_TOKEN'), 403));

		// Initialise variables.
		$user	= JFactory::getUser();
		$ids	= JRequest::getVar('cid', null, 'post', 'array');

		$model = $this->getModel();
		$return = $model->checkin($ids);
		
		$r = new JObject();
		if ($return === false) {
			// Checkin failed.
			$this->sendJsonResponse(new JException(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 500));
			return false;
		} else {
			// Checkin succeeded.
			$r = new JObject();
			$r->message = JText::plural($this->text_prefix.'_N_ITEMS_CHECKED_IN', count($ids));
			$this->sendJsonResponse($r);
			return true;
		}
	}
}
