<?php
/**
 * JLoaderTest
 *
 * @version   $Id$
 * @package   Joomla.UnitTest
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license   GNU General Public License
 */
require_once 'PHPUnit/Framework.php';
require_once JPATH_BASE . '/libraries/loader.php';

/**
 * Test class for JLoader.
 * Generated by PHPUnit on 2009-10-16 at 23:32:06.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 */
class JLoaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JLoader is an abstract class of static functions and variables, so will test without instantiation
	 */
	protected $object;
	/**
	 * @var bogusPath is the path to the bogus object for loader testing
	 */
	protected $bogusPath;
	/**
	 * @var bogusFullPath is the full path (including filename) to the bogus object
	 */
	protected $bogusFullPath;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->bogusPath = JPATH_BASE.DS.'tests/unit/objects';
		$this->bogusFullPath = JPATH_BASE.DS.'tests/unit/objects/bogusload.php';
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 *	The test cases for importing classes
	 *
	 * @return array
	 */
	function casesImport()
	{
		return array(
			'factory' => array(
				'joomla.factory',
				null,
				null,
				true,
				'factory should load properly',
				true,
			),
			'jfactory' => array(
				'joomla.jfactory',
				null,
				null,
				false,
				'JFactory does not exist so should not load properly',
				true,
			),
			'fred.factory' => array(
				'fred.factory',
				null,
				null,
				false,
				'fred.factory does not exist',
				true,
			),
			'bogus' => array(
				'bogusload',
				JPATH_BASE.DS.'tests/unit/objects',
				'',
				true,
				'bogusload.php should load properly',
				false,
			),
			'helper' => array(
				'joomla.user.helper',
				null,
				'',
				true,
				'userhelper should load properly',
				true,
			),
		);
	}

	/**
	 * The success of this test depends on some files being in the file system to be imported. If the FS changes, this test may need revisited.
	 * The files are libraries/joomla/factory.php and libraries/bitfolge/vcard.php
	 *
	 * @param	string	$filePath		Path to object
	 * @param	string	$base			Path to location of object
	 * @param	string	$libraries		Which libraries to use
	 * @param	bool	$expect			Result of import (True = success)
	 * @param	string	$message		Failure message
	 * @param	bool	$useDefaults	Use the default function arguments
	 *
	 * @group   JLoader
	 * @covers  JLoader::import
	 *
	 * @return void
	 * @dataProvider casesImport
	 */
	public function testImport( $filePath, $base, $libraries, $expect, $message, $useDefaults )
	{
		if ($useDefaults) {
			$output = JLoader::import($filePath);
		} else {
			$output = JLoader::import($filePath, $base, $libraries);
		}

		$this->assertThat(
			$output,
			$this->equalTo($expect),
			$message
		);
	}

	/**
	 * The success of this test depends on the bogusload object being present in the
	 * unittest/objects tree
	 *
	 * @group   JLoader
	 * @covers  JLoader::register
	 * @return void
	 */
	public function testRegistersAGoodClass()
	{
		$classList = JLoader::register('BogusLoad', $this->bogusFullPath);
		$this->assertArrayHasKey(
			'bogusload',
			$classList,
			'Should have a bogusload key in class list'
		);
		$this->assertEquals(
			$this->bogusFullPath,
			$classList['bogusload'],
			'Should add Bogus Class to loaded class list'
		);
	}

	/**
	 * This test should try and fail to register a non-existent class
	 *
	 * @group   JLoader
	 * @covers  JLoader::register
	 * @return void
	 */
	public function testFailsToRegisterABadClass()
	{
		$badClassList = JLoader::register("fred", "fred.php");
		$this->assertArrayNotHasKey(
			'fred',
			$badClassList,
			'Should not have a fred key in class list'
		);
	}

	/**
	 *	The test cases for loading classes
	 *
	 * @return array
	 */
	function casesLoader()
	{
		return array(
			'JObject' => array(
				'JObject',
				true,
				'JObject should load properly',
			),
			'ArrayObject' => array(
				'ArrayObject',
				true,
				'ArrayObject should exist',
			),
			'Fred' => array(
				'Fred',
				false,
				'Fred does not exist',
			),
		);
	}

	/**
	 * This tests the load method with an existing standard PHP object, and a non-existent one.
	 *
	 * @param	string	$object		Name of object to be imported
	 * @param	bool	$expect		Expected result
	 * @param	string	$message	Failure message to be displayed
	 *
	 * @return void
	 * @dataProvider casesLoader
	 * @group   JLoader
	 * @covers  JLoader::load
	 */
	public function testLoad( $object, $expect, $message )
	{
		$this->assertThat(
			JLoader::load($object),
			$this->equalTo($expect),
			$message
		);
	}

	/**
	 *	The test cases for jimport-ing classes
	 *
	 * @return array
	 */
	function casesJimport()
	{
		return array(
			'fred.factory' => array(
				'fred.factory',
				false,
				'fred.factory does not exist',
			),
			'helper' => array(
				'joomla.installer.helper',
				true,
				'installerhelper should load properly',
			),
		);
	}

	/**
	 * This tests the convenience function jimport.
	 *
	 * @param	string	$object		Name of object to be imported
	 * @param	bool	$expect		Expected result
	 * @param	string	$message	Failure message to be displayed
	 *
	 * @return void
	 * @dataProvider casesJimport
	 * @group   JLoader
	 */
	public function testJimport( $object, $expect, $message )
	{
		$this->assertThat(
			$expect,
			$this->equalTo(jimport($object)),
			$message
		);
	}
}
?>
