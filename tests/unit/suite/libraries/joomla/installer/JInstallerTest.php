<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE . '/libraries/joomla/installer/installer.php';

/**
 * Test class for JInstaller.
 * Generated by PHPUnit on 2009-10-27 at 15:20:37.
 */
class JInstallerTest extends JoomlaTestCase
{
	/**
	 * @var JInstaller
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * @todo Implement testGetInstance().
	 */
	public function testGetInstance()
	{
		$this->assertThat(
			$this->object = JInstaller::getInstance(),
			$this->isInstanceOf('JInstaller'),
			'JInstaller::getInstance failed'
		);
	}

	public function testMyOnlyTest()
	{
		$this->setExpectedError(array('level' => E_ERROR, 'code' => 500, 'message' => 'test'));
		//$this->setExpectedError();
		JError::raiseError(500, 'test');

	}

	/**
	 * @todo Implement testGetOverwrite().
	 */
	public function testGetAndSetOverwrite()
	{
		$this->object = JInstaller::getInstance();
		$this->object->setOverwrite(false);

		$this->assertThat(
			$this->object->getOverwrite(),
			$this->equalTo(false),
			'Get or Set overwrite failed'
		);

		$this->assertThat(
			$this->object->setOverwrite(true),
			$this->equalTo(false),
			'setOverwrite did not return the old value properly.'
		);

		$this->assertThat(
			$this->object->getOverwrite(),
			$this->equalTo(true),
			'getOverwrite did not return the expected value.'
		);

	}

	/**
	 * @todo Implement testGetOverwrite().
	 */
	public function testGetAndSetRedirectUrl()
	{
		$this->object = JInstaller::getInstance();
		$this->object->setRedirectUrl('http://www.example.com');

		$this->assertThat(
			$this->object->getRedirectUrl(),
			$this->equalTo('http://www.example.com'),
			'Get or Set Redirect Url failed'
		);

	}

	/**
	 * @todo Implement testGetOverwrite().
	 */
	public function testGetAndSetUpgrade()
	{
		$this->object = JInstaller::getInstance();
		$this->object->setUpgrade(false);

		$this->assertThat(
			$this->object->getUpgrade(),
			$this->equalTo(false),
			'Get or Set Upgrade failed'
		);

		$this->assertThat(
			$this->object->setUpgrade(true),
			$this->equalTo(false),
			'setUpgrade did not return the old value properly.'
		);

		$this->assertThat(
			$this->object->getUpgrade(),
			$this->equalTo(true),
			'getUpgrade did not return the expected value.'
		);

	}

	/**
	 * @todo Implement testGetManifest().
	 */
	public function testGetManifest()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGetPath().
	 */
	public function testGetPath()
	{
		$this->object = JInstaller::getInstance();

		$this->assertThat(
			$this->object->getPath('path1_getpath', 'default_value'),
			$this->equalTo('default_value'),
			'getPath did not return the default value for an undefined path'
		);

		$this->object->setPath('path2_getpath', JPATH_BASE.'/required_path');

		$this->assertThat(
			$this->object->getPath('path2_getpath', 'default_value'),
			$this->equalTo(JPATH_BASE.'/required_path'),
			'getPath did not return the previously set value for the path'
		);
	}

	/**
	 * @todo Implement testAbort().
	 */
	public function testAbortFile()
	{
		copy(JPATH_BASE.'/tests/_data/installer_packages/com_alpha/com_alpha.jpg', JPATH::dirname(__FILE__).'/tmp/com_alpha.jpg');

		$this->object = JInstaller::getInstance();
		$this->object->pushStep(array('type' => 'file', 'path' => JPATH::dirname(__FILE__).'/tmp/com_alpha.jpg'));


		$this->assertThat(
			$this->object->abort(),
			$this->isTrue()
		);

		$this->assertThat(
			file_exists(JPATH::dirname(__FILE__).'/tmp/com_alpha.jpg'),
			$this->isFalse()
		);

	}

	/**
	 * @todo Implement testAbort().
	 */
	public function testAbortFolder()
	{
		JFolder::copy(JPATH_BASE.'/tests/_data/installer_packages/com_alpha/language/en-GB', JPATH::dirname(__FILE__).'/tmp/en-GB');

		$this->object = JInstaller::getInstance();
		$this->object->pushStep(array('type' => 'folder', 'path' => JPATH::dirname(__FILE__).'/tmp/en-GB'));


		$this->assertThat(
			$this->object->abort(),
			$this->isTrue()
		);

		$this->assertThat(
			file_exists(JPATH::dirname(__FILE__).'/tmp/en-GB'),
			$this->isFalse()
		);

	}


	/**
	 * @todo Implement testAbort().
	 */
	public function testAbortQuery()
	{
		$this->object = JInstaller::getInstance();
		$this->object->pushStep(array('type' => 'query'));


		$this->assertThat(
			$this->object->abort(),
			$this->isFalse()
		);

	}


	/**
	 * @todo Implement testAbort().
	 */
	public function testAbortExtension()
	{
		$this->saveFactoryState();

		$newDbo = $this->getMock('test', array('setQuery', 'query'));

		$newDbo->expects($this->once())
			->method('setQuery')
			->with($this->equalTo('DELETE FROM `#__extensions` WHERE extension_id = 3'));

		$newDbo->expects($this->once())
			->method('Query')
			->with()
			->will($this->returnValue(true));

		JFactory::$database = &$newDbo;

		//$this->object = JInstaller::getInstance();
		$this->object = new JInstaller;
		$this->object->pushStep(array('type' => 'extension', 'id' => 3));

		$this->assertThat(
			$this->object->abort(),
			$this->isTrue()
		);

		$this->restoreFactoryState();


	}


	/**
	 * @todo Implement testAbort().
	 */
	public function testAbortDefault()
	{
		$adapterMock = $this->getMock('test', array('_rollback_testtype'));

		$adapterMock->expects($this->once())
			->method('_rollback_testtype')
			->with($this->equalTo(array('type' => 'testtype')))
			->will($this->returnValue(true));


		//$this->object = JInstaller::getInstance();
		$this->object = new JInstaller;

		$this->object->setAdapter('testadapter', $adapterMock);

		$this->object->pushStep(array('type' => 'testtype'));

		$this->assertThat(
			$this->object->abort(null, 'testadapter'),
			$this->isTrue()
		);

	}

	/**
	 * Test that an abort message results in a raised warning
	 */
	public function testAbortMsg()
	{
		//$this->object = JInstaller::getInstance();
		$this->object = new JInstaller;

		$this->setExpectedError(array('code' => 100, 'message' => 'Warning Text'));

		$this->assertThat(
			$this->object->abort('Warning Text'),
			$this->isTrue()
		);

	}


	/**
	 * Test that if the type is not good we fall back properly
	 */
	public function testAbortBadType()
	{
		//$this->object = JInstaller::getInstance();
		$this->object = new JInstaller;

		$this->object->pushStep(array('type' => 'badstep'));

		$this->assertThat(
			$this->object->abort(null, false),
			$this->isFalse()
		);

	}


	/**
	 * This test is weak and may need removal at some point
	 */
	public function testAbortDebug()
	{

		$configMock = $this->getMock('test', array('get'));

		$configMock->expects($this->atLeastOnce())
			->method('get')
			->will($this->returnValue(true));

		$this->setExpectedError(array('code' => 500));

		//$this->object = JInstaller::getInstance();
		$this->object = new JInstaller;

		$this->saveFactoryState();
		JFactory::$config = $configMock;

		$this->assertThat(
			$this->object->abort(),
			$this->isTrue()
		);

		$this->restoreFactoryState();

	}


	/**
	 * @todo Implement testInstall().
	 */
	public function testInstall()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testDiscover_install().
	 */
	public function testDiscover_install()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testDiscover().
	 */
	public function testDiscover()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testUpdate().
	 */
	public function testUpdate()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testUninstall().
	 */
	public function testUninstall()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testRefreshManifestCache().
	 */
	public function testRefreshManifestCache()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testSetupInstall().
	 */
	public function testSetupInstall()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testParseQueries().
	 */
	public function testParseQueries()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testParseSQLFiles().
	 */
	public function testParseSQLFiles()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testParseFiles().
	 */
	public function testParseFiles()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testParseLanguages().
	 */
	public function testParseLanguages()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testParseMedia().
	 */
	public function testParseMedia()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGetParams().
	 */
	public function testGetParams()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testCopyFiles().
	 */
	public function testCopyFiles()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testRemoveFiles().
	 */
	public function testRemoveFiles()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testCopyManifest().
	 */
	public function testCopyManifest()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testFindManifest().
	 */
	public function testFindManifest()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testIsManifest().
	 */
	public function testIsManifest()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGenerateManifestCache().
	 */
	public function testGenerateManifestCache()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testCleanDiscoveredExtension().
	 */
	public function testCleanDiscoveredExtension()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testFindDeletedFiles().
	 */
	public function testFindDeletedFiles()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testLoadMD5Sum().
	 */
	public function testLoadMD5Sum()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGetGroupIDFromName().
	 */
	public function testGetGroupIDFromName()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}
}
?>
