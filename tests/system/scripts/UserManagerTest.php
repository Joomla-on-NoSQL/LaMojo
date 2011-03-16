<?php

/**
 * @version		$Id: article0001Test.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.SystemTest
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * checks that all menu choices are shown in back end
 */
require_once 'JoomlaTestCase.php';

class UserManagerTest extends JoomlaTestCase
{

	/**
	 * @test
	 */
	public function createNewUserTest()
	{
		try
		{
			$landing = new AdminLoginPage();
			$landing->open_default_base_url();
			$control = $landing->login_success();
			$control->wait_until_loaded();
			$user_manager = $control->open_from_menu('UserManagerPage');
			$user_edit_page = $user_manager->new_user();
			$user_edit_page->name = 'testuser';
			$user_edit_page->username = 'testusername';
			$user_edit_page->password = 'password';
			$user_edit_page->password2 = 'password';
			$user_edit_page->email = 'testemail@example.com';
			$user_manager = $user_edit_page->save_and_close_success();
			$user_manager->delete_user('testuser');
		}
		catch (Exception $e)
		{
			$this->fail($e->getMessage());
		}
	}

}
