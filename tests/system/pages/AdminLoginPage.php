<?php

require_once 'pages/BasePage.php';
require_once 'includes/SeleniumConnection.php';

class AdminLoginPage extends BasePage {
  private $locators = array(
    "username" => "username",
    "password" => "passwd",
    "submit_button" => "link=Log in"
  );

  function __set($property, $value) {
    switch($property) {
      // cases can be stacked so all the 'text' ones here
      case "username":
      case "password":
        $this->selenium->type($this->locators[$property], $value);
        break;
      // if there were other types of elements like checks and selects
      // there would be another stack of cases here
      default:
        $this->$property = $value;
    }
  }
  
  function __get($property) {
    switch($property) {
      default:
        return $this->$property;
    }
  }
  
  function wait_until_loaded() {
    $this->waitForElementAvailable($this->locators['username']);
  }

  function open_default_base_url() {
	global $site;
    $this->selenium->open($site['baseurl'].'/administrator');
  }


  function login() {
    $this->selenium->click($this->locators['submit_button']);
    $this->selenium->waitForPageToLoad(parent::$string_timeout);
    if ($this->selenium->isElementPresent($this->locators['username']) {
		return $this;
	} else {
		$controlPanelPage = new ControlPanelPage();
		$controlPanelPage->wait_until_loaded();
		return $controlPanelPage;
	}
  }
}
