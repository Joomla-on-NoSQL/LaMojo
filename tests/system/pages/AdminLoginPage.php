<?php

require_once 'includes/SeleniumConnection.php';

class AdminLoginPage extends BasePage
{

	private $locators = array(
		"username" => "username",
		"password" => "passwd",
		"submit_button" => "link=Log in",
		"error_message" => "css=#system-message li"
	);

	function __set($property, $value)
	{
		switch ($property)
		{
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

	function __get($property)
	{
		switch ($property)
		{
			case 'error_message':
				return $this->selenium->getText($this->locators[$property]);
			default:
				return $this->$property;
		}
	}

	function wait_until_loaded()
	{
		$this->waitForElementAvailable($this->locators['username']);
	}

	function open_default_base_url()
	{
		global $site;
		$this->selenium->open(SiteSettings::$url . 'administrator');
	}

	function login_success($username = null, $password = null)
	{
		if (is_null($username)) {
			$username = SiteSettings::$username;
			$password = SiteSettings::$password;
		}
		$this->username = $username;
		$this->password = $password;
		
		$this->selenium->click($this->locators['submit_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$controlPanelPage = new ControlPanelPage();
		$controlPanelPage->wait_until_loaded();
		return $controlPanelPage;
	}

	function login_failed()
	{
		$this->selenium->click($this->locators['submit_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		return $this;
	}

}
