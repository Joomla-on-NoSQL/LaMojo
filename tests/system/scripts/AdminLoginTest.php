<?php
/**
 * @version		$Id: article0001Test.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.SystemTest
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * checks that all menu choices are shown in back end
 */

require_once 'JoomlaTestCase.php';
require_once 'AdminLoginPage.php';

class AdminLoginTest extends JoomlaTestCase {
  /**
  * @test
  */
  public function adminLoginTest()
  {
    $landing = new AdminLoginPage();
    $landing->open_default_base_url();
    $landing->username = 'admin';
    $landing->password = 'admin';
    $landing->login_failed();
  }
}
