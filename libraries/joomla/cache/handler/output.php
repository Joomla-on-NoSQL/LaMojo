<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Joomla! Cache output type object
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCacheHandlerOutput extends JCacheHandler
{	
	private $_id;
	private $_group;
	
	/**
	 * Cache lock test
	 *
	 * @var		object
	 * @since	1.6
	 */
	private $_locktest = null;
	
	/**
	* Constructor
	*
	* @access protected
	* @param array $options optional parameters
	*/
	function __construct($options = array())
	{
		parent::__construct($options);
	}
	
	/**
	 * Start the cache
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True if the cache is hit (false else)
	 * @since	1.5
	 */
	function start($id, $group=null)
	{
		// If we have data in cache use that...
		$data = $this->cache->get($id, $group);
		
		$this->_locktest = new stdClass;
		$this->_locktest->locked = null;
		$this->_locktest->locklooped = null;
		
		if ($data === false) 
		{
			$this->_locktest = $this->cache->lock($id,null);
			if ($this->_locktest->locked == true && $this->_locktest->locklooped == true) $data = $this->cache->get($id);
		
		}
		
		if ($data !== false) {
			echo $data;
			if ($this->_locktest->locked == true) $this->cache->unlock($id);
			return true;
		} else {
			// Nothing in cache... lets start the output buffer and start collecting data for next time.
			if ($this->_locktest->locked == false) $this->_locktest = $this->cache->lock($id,null);
			ob_start();
			ob_implicit_flush(false);
			// Set id and group placeholders
			$this->_id		= $id;
			$this->_group	= $group;
			return false;
		}
	}

	/**
	 * Stop the cache buffer and store the cached data
	 *
	 * @access	public
	 * @return	boolean	True if cache stored
	 * @since	1.5
	 */
	function end()
	{
		// Get data from output buffer and echo it
		$data = ob_get_contents();
		ob_end_clean();
		echo $data;

		// Get id and group and reset them placeholders
		$id		= $this->_id;
		$group	= $this->_group;
		$this->_id		= null;
		$this->_group	= null;

		// Get the storage handler and store the cached data
		$this->cache->store($data, $id, $group);
		if ($this->_locktest->locked == true) $this->cache->unlock($id);
	}
}
