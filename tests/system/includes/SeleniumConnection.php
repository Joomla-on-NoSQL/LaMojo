<?php

require_once 'Testing/Selenium.php';

class SeleniumConnection {

  // Store the single instance of Selenium server 
  private static $m_pInstance; 

  private function __construct() {
	$this->selenium = new Testing_Selenium(SeleniumSettings::$browser, SiteSettings::$url, SeleniumSettings::$host, SeleniumSettings::$port);
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
