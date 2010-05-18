<?php
/**
 * @version		$Id: interface.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Connector Handler Interface.
 *
 * @package	NoixFLAPP.Framework
 * @base connector handler
 * @since	1.0
 */
interface JFlappConnectorHandlerInterface
{
	public function __construct($config=null);
	
	public function connect();
	
	
}