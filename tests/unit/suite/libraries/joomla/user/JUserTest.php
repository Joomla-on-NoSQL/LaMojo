<?php
/**
 * JUserTest.php -- unit testing file for JUser
 *
 * @version		$Id$
 * @package	Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once JPATH_BASE.'/tests/unit/JoomlaDatabaseTestCase.php';
/**
 * Test class for JUser.
 * Generated by PHPUnit on 2009-10-26 at 22:44:07.
 *
 * @package	Joomla.UnitTest
 * @subpackage User
 *
 */
class JUserTest extends JoomlaDatabaseTestCase
{
	/**
	 * @var JUser
	 */
	protected $object;
	/**
	 * Receives the callback from JError and logs the required error information for the test.
	 *
	 * @param	JException	The JException object from JError
	 *
	 * @return	bool	To not continue with JError processing
	 */
	static function errorCallback( $error )
	{
		JUserTest::$actualError['code'] = $error->get('code');
		JUserTest::$actualError['msg'] = $error->get('message');
		JUserTest::$actualError['info'] = $error->get('info');
		return false;
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		include_once JPATH_BASE . '/libraries/joomla/database/table.php';
		include_once JPATH_BASE . '/libraries/joomla/user/user.php';

		parent::setUp();

		$this->saveFactoryState();
		$this->saveErrorHandlers();
		$this->setErrorCallback('JUserTest');
		JUserTest::$actualError = array();

		JUser::getTable('user', 'JTable');
		$this->object = new JUser(42);
		$params = new JRegistry;
		$this->object->setParameters($params);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		$this->setErrorhandlers($this->savedErrorState);
	}

	/**
	 * Test cases for getInstance
	 *
	 * @return array
	 */
	function casesGetInstance()
	{
		return array(
			'42' => array(
				42,
				null,
				array()
			),
			'admin' => array(
				'admin',
				null,
				array(),
			),
			'nobody' => array(
				'nobody',
				false,
				array(
					'code' => 'SOME_ERROR_CODE',
					'msg' => 'JUser::_load: User nobody does not exist',
					'info' => ''
				),
			),
		);
	}
	/**
	 * TestingGetInstance().
	 *
	 * @param	mixed	User ID or name
	 * @param	mixed	User object or false if unknown
	 * @param	array	Expected error info
	 *
	 * @return void
	 * @dataProvider casesGetInstance
	 */
	public function testGetInstance( $userid, $expected, $error )
	{
		$expResult = (is_null($expected))? $this->object : $expected;
		$user = JUser::getInstance($userid);
		$this->assertThat(
			$user,
			$this->equalTo($expResult)
		);
		$this->assertThat(
			JUserTest::$actualError,
			$this->equalTo($error)
		);
	}

	/**
	 * Testing individual parameter control.
	 *
	 * @return void
	 */
	public function testParameters()
	{
		$this->assertThat(
			$this->object->getParam('holy', 'fred'),
			$this->equalTo('fred')
		);

		$this->object->defParam('holy', 'batman');
		$this->assertThat(
			$this->object->getParam('holy', 'fred'),
			$this->equalTo('batman')
		);

		$this->object->setParam('holy', 'batman');
		$this->assertThat(
			$this->object->getParam('holy', 'fred'),
			$this->equalTo('batman')
		);
	}

	/**
	 * Test cases for authorizations
	 *
	 * @return array
	 */
	function casesAuthorize()
	{
		return array(
			'Simple' => array(
				'read',
				null,
				true,
			),
			'fictional' => array(
				'nuke',
				null,
				true,
			),
			'root' => array(
				'com.banners',
				'root.1',
				true,
			),
			'article' => array(
				'edit',
				'com.article.1',
				true,
			),
		);
	}
	/**
	 * Testing authorize().
	 *
	 * @param	string	Action to get aithorized for this user
	 * @param	string	Asset to get authorization for
	 * @param	bool	Expected return from the authorization check
	 *
	 * @return void
	 * @dataProvider casesAuthorize
	 */
	public function testAuthorize( $action, $asset, $expected )
	{
		$users[0] = $this->object;
		$users[1] = new JUser(0);
		$users[2] = new JUser(100);

		foreach ($users as $user)
		{
			$this->assertThat(
				$user->authorize($action, $asset),
				$this->equalTo($expected),
				"Failed for user $user"
			);
		}
	}

	/**
	 * Testing authorise().
	 *
	 * @param	string	Action to get aithorized for this user
	 * @param	string	Asset to get authorization for
	 * @param	bool	Expected return from the authorization check
	 *
	 * @return void
	 * @dataProvider casesAuthorize
	 */
	public function testAuthorise( $action, $asset, $expected )
	{
		$users[0] = $this->object;
		$users[1] = new JUser(0);
		$users[2] = new JUser(100);

		foreach ($users as $user)
		{
			$this->assertThat(
				$user->authorise($action, $asset),
				$this->equalTo($expected),
				"Failed for user {$user->id}"
			);
		}
	}

	/**
	 * Test cases for authorizedLevels
	 *
	 * @return array
	 */
	function casesAuthorizedLevels()
	{
		return array(
			'Normal' => array(
				0,
				array( 1, 3 ),
			),
			'User1' => array(
				1,
				array( 1 ),
			),
			'User100' => array(
				100,
				array( 1 ),
			),
		);
	}

	/**
	 * Testing authorisedLevels().
	 *
	 * @param	Integer	User ID
	 * @param	array	Authorized levels of use
	 *
	 * @return void
	 * @dataProvider	casesAuthorizedLevels
	 */
	public function testAuthorisedLevels( $user, $expected )
	{
		if ($user )
		{
			$user = new JUser($user);
		}
		else
		{
			$user = $this->object;
		}

		$this->assertThat(
			$user->authorisedLevels(),
			$this->equalTo($expected),
			"Failed for user {$user->id}"
		);
	}

	/**
	 * Testing setLastVisit().
	 *
	 * @return void
	 */
	public function testSetLastVisit()
	{
		$timestamp = '2006-12-23 12:12:14';

		$this->object->setLastVisit($timestamp);
		$testUser = new JUser(42);
		$this->assertThat(
			$testUser->lastvisitDate,
			$this->equalTo($timestamp)
		);
	}

	/**
	 * Testing getParameters
	 *
	 * @return void
	 * @todo Implement testGetParameters().
	 */
	public function testGetParameters()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Testing setParameters
	 *
	 * @return void
	 * @todo Implement testSetParameters().
	 */
	public function testSetParameters()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test cases for gettable
	 *
	 * @return array
	 */
	function casesGetTable()
	{
		return array(
			'Wierd' => array(
				'fred',
				'JTable',
				false,
				'#__users',
			),
			'simple' => array(
				null,
				null,
				'JTableUser',
				'#__users',
			),
			'unknown' => array(
				null,
				'PTable',
				'JTableUser',
				'#__users',
			),
			'reset' => array(
				'user',
				'JTable',
				'JTableUser',
				'#__users',
			),
		);
	}

	/**
	 * Testing getTable().
	 *
	 * @param	string	The type of table
	 * @param	string	The prefix for the table
	 * @param	string	The expected class of the table
	 * @param	string	The expected name of the table
	 *
	 * @return void
	 *
	 * @dataProvider casesGetTable
	 */
	public function testGetTable( $type, $prefix, $expClass, $expName )
	{
		$table = $this->object->getTable($type, $prefix);

		$this->assertThat(
			$table,
			$expClass?$this->isInstanceOf($expClass):$this->isFalse(),
			'Table is not instance of JTableUser'
		);
		if ($expClass)
		{
			$this->assertThat(
				$table->getTableName(),
				$this->equalTo($expName),
				'Failed table name check'
			);
		}
	}

	/**
	 *	Testing bind()
	 *
	 * @return void
	 * @todo Implement testBind().
	 */
	public function testBind()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Testing creation and deletion of users
	 *
	 * @return void
	 */
	public function testCreateDeleteUser()
	{
		include_once JPATH_BASE . '/libraries/joomla/event/dispatcher.php';
		include_once JPATH_BASE . '/libraries/joomla/plugin/helper.php';

		$mockSession = $this->getMock('JSession', array('_start', 'get'));
		$mockSession->expects($this->any())->method('get')->will(
			$this->returnValue($this->object)
		);
		JFactory::$session = $mockSession;

		$testUser = new JUser();
		$testUser->name = "Floyd Smoot";
		$testUser->username = "Floyd";

		$this->assertThat(
			$testUser->id,
			$this->equalTo(0),
			"Newly created id should be zero"
		);

		$this->assertThat(
			$testUser->save(),
			$this->isFalse(),
			'Cannot save without valid email'
		);
		$this->assertThat(
			$testUser->getErrors(),
			$this->equalTo(
				array('Please enter a valid e-mail address.')
			),
			'Should have caused valid email error'
		);

		$testUser->email = "harry@sally.com";
		$this->assertThat(
			$testUser->save(true),
//			$this->isFalse(),
			$this->isTrue(),
			'Should not create new user when update only flag is set'
		);

		$this->assertThat(
			$testUser->save(),
			$this->isTrue()
		);

		$this->assertThat(
			$testUser->id,
			$this->greaterThan(0),
			"Newly saved id should not be zero"
		);

		$testUser->email = "sally@harry.com";
		$this->assertThat(
			$testUser->save(),
			$this->isTrue(),
			'Should update existing user.'
		);

		$testUser1 = JUser::getInstance('Floyd');
		$this->assertThat(
			$testUser1->id,
			$this->equalTo($testUser1->id),
			"Id's should be the same"
		);

		$this->assertThat(
			$testUser->delete(),
			$this->isTrue()
		);

		$testUser2 = JUser::getInstance('Floyd');
		$this->assertThat(
			$testUser2,
			$this->isFalse(),
			"Id should not be found"
		);
	}

	/**
	 * Test cases for load
	 *
	 * @return array
	 */
	function casesLoad()
	{
		return array(
			'non-existant' => array(
				1120,
				false,
			),
			'existing' => array(
				42,
				true,
			),
		);
	}

	/**
	 * Testing load().
	 *
	 * @param	integer	User ID to load
	 * @param	bool	Expected result of load operation
	 *
	 * @return void
	 *
	 * @dataProvider casesLoad
	 */
	public function testLoad( $id, $expected )
	{
		$testUser = new JUser($id);

		$this->assertThat(
			$testUser->load($id),
			$this->equalTo($expected)
		);
	}
}

