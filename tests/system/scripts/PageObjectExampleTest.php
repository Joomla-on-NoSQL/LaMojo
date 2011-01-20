<?php

require_once 'JoomlaTestCase.php';
require_once 'AdminLoginPage.php';

class PageObjectExample extends JoomlaTestCase {
  /**
  * @test
  */
  public function example()
  {
    $landing = new AdminLoginPage();
    $landing->open_default_base_url();
    $landing->username = 'admin';
    $landing->password = 'admin';
    $landing->login();
  }
}
