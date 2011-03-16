<?php

/**
 * @version		$Id: article0001Test.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.SystemTest
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * checks that all menu choices are shown in back end
 */
require_once 'JoomlaTestCase.php';

class AdminLoginTest extends JoomlaTestCase
{

	/**
	 * @test
	 */
	public function adminLoginFailureTest()
	{
		try
		{
			$landing = new AdminLoginPage();
			$landing->open_default_base_url();
			$landing->username = 'baduser';
			$landing->password = 'badpassword';
			$landing = $landing->login_failed();
			$landing->wait_until_loaded();
			$this->assertEquals($landing->error_message, 'Username and password do not match or you do not have an account yet.');
		}
		catch (Exception $e)
		{
			$this->fail($e->getMessage());
		}
	}

	/**
	 * @test
	 */
	public function adminLoginSuccessTest()
	{
		try
		{
			$landing = new AdminLoginPage();
			$landing->open_default_base_url();
			$landing->username = 'admin';
			$landing->password = 'admin';
			$controlPanelPage = $landing->login_success();
			$controlPanelPage->wait_until_loaded();
		}
		catch (Exception $e)
		{
			$this->fail($e->getMessage());
		}
	}

	/**
	 * @test
	 */
	public function adminLogoutTest()
	{
		try
		{
			$landing = new AdminLoginPage();
			$landing->open_default_base_url();
			$landing->username = 'admin';
			$landing->password = 'admin';
			$controlPanelPage = $landing->login_success();
			$landing = $controlPanelPage->logout();
			$landing->wait_until_loaded();
		}
		catch (Exception $e)
		{
			$this->fail($e->getMessage());
		}
	}

}
