<?php
/**
 * @version		$Id$
 * @package	Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Test class for JCachePage.
 * Generated by PHPUnit on 2009-10-08 at 21:43:03.
 *
 * @package	Joomla.UnitTest
 * @subpackage Cache
 *
 */
class JCachePageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var	JCachePage
	 * @access protected
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 * @access protected
	 */
	protected function setUp()
	{
		include_once JPATH_BASE.'/libraries/joomla/cache/cache.php';
		include_once JPATH_BASE.'/libraries/joomla/cache/handler/page.php';

		$this->object = JCache::getInstance('page', array());
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 * @access protected
	 */
	protected function tearDown()
	{
	}

	/**
	 * @return void
	 * @todo Implement testGet().
	 */
	public function testGet()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return void
	 * @todo Implement testStore().
	 */
	public function testStore()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return void
	 * @todo Implement test_makeId().
	 */
	public function test_makeId()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return void
	 * @todo Implement test_noChange().
	 */
	public function test_noChange()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return void
	 * @todo Implement test_setEtag().
	 */
	public function test_setEtag()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
?>
