<?php

require_once 'Testing/Selenium.php';

class SeleniumConnection {

  // Store the single instance of Selenium server 
  private static $m_pInstance; 

  private function __construct() {
	global $site, $selenium;
	var_dump($site); print_r($selenium);
	$this->selenium = new Testing_Selenium($selenium['browser'], $site['baseurl'], $selenium['host'], $selenium['port']);
  }

  public static function getInstance() 
  {
      if (!self::$m_pInstance) 
      { 
          self::$m_pInstance = new SeleniumConnection(); 
      } 

      return self::$m_pInstance; 
  }
}


?>
