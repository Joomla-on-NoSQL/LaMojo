<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * Comment table object for JXtended Comments
 *
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @version		1.0
 */
class CommentsTableComment extends JTable
{
	/** @var int */
	var $id = null;
	/** @var int unsigned */
	var $user_id = null;
	/** @var int */
	var $thread_id = null;
	/** @var varchar */
	var $context = null;
	/** @var int */
	var $context_id = null;
	/** @var int */
	var $trackback = null;
	/** @var int */
	var $notify = null;
	/** @var int */
	var $score = null;
	/** @var varchar */
	var $referer = null;
	/** @var varchar */
	var $page = null;
	/** @var varchar */
	var $name = null;
	/** @var varchar */
	var $url = null;
	/** @var varchar */
	var $email = null;
	/** @var varchar */
	var $subject = null;
	/** @var text */
	var $body = null;
	/** @var datetime */
	var $created_date = null;
	/** @var int unsigned */
	var $published = null;
	/** @var varchar */
	var $address = null;
	/** @var varchar */
	var $link = null;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	object	Database object
	 * @return	void
	 * @since	1.0
	 */
	function __construct(&$db)
	{
		parent::__construct('#__social_comments', 'id', $db);
	}

	/**
	 * Method to check the current record to save
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function check()
	{
		// Get the JXtended Comments configuration object.
		$config = &JComponentHelper::getParams('com_comments');

		// Import library dependencies.
		jimport('joomla.mail.helper');
		jimport('joomla.filter.input');

		// Validate the comment data.
		$result	= false;

		if ($this->trackback)
		{
			if (empty($this->thread_id)) {
				$this->setError('Comments_Trackback_Thread_Empty');
			} else if (empty($this->subject)) {
				$this->setError('Comments_Trackback_Subject_Is_Empty');
			} else if (empty($this->url) || JFilterInput::checkAttribute(array('href', $this->url))) {
				$this->setError('Comments_Trackback_URL_Invalid');
			} else {
				$result = true;
			}
		}
		else
		{
			if (empty($this->thread_id)) {
				$this->setError('Comments_Comment_Thread_Empty');
			} else if (empty($this->name)) {
				$this->setError('Comments_Comment_Name_Is_Empty');
			} else if (strlen($this->body) < $config->get('minlength')) {
				$this->setError('Comments_Comment_Is_Too_Short');
			} else if (strlen($this->body) > $config->get('maxlength')) {
				$this->setError('Comments_Comment_Is_Too_Long');
			} else if (!JMailHelper::isEmailAddress($this->email)) {
				$this->setError('Comments_Comment_Email_Invalid');
			} else if ($this->url && JFilterInput::checkAttribute(array('href', $this->url))) {
				$this->setError('Comments_Comment_URL_Invalid');
			} else {
				$result = true;
			}
		}

		// Check for URI scheme on webpage.
		if (strlen($this->url) > 0 && (!(eregi('http://', $this->url) or (eregi('https://', $this->url)) or (eregi('ftp://', $this->url))))) {
			$this->url = 'http://'.$this->url;
		}

		// Clean the various mail fields.
		$this->subject	= JMailHelper::cleanSubject($this->subject);
		$this->email	= JMailHelper::cleanAddress($this->email);
		$this->body		= JMailHelper::cleanBody($this->body);

		// Strip out bad words.
		$badWords		= explode(',', $config->get('censorwords'));
		$this->subject	= str_replace($badWords, '', $this->subject);
		$this->name		= str_replace($badWords, '', $this->name);
		$this->url		= str_replace($badWords, '', $this->url);
		$this->email	= str_replace($badWords, '', $this->email);
		$this->body		= str_replace($badWords, '', $this->body);

		return $result;
	}
}
