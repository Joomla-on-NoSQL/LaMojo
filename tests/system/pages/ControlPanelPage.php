<?php

require_once 'pages/BasePage.php';
require_once 'includes/SeleniumConnection.php';

class ControlPanelPage extends BasePage
{

	private $locators = array(
		"menu" => "menu",
		'logout_button' => 'link=Log out',
	);
	
	private $menuOptions = array(
		'UserManagerPage' => 'link=User Manager'
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
			case 'menu':
				return $this->selenium->getText($this->locators[$property]);
			default:
				return $this->$property;
		}
	}

	function wait_until_loaded()
	{
		$this->waitForElementAvailable($this->locators['menu']);
	}

	public function open_from_menu($page)
	{
		$this->selenium->click($this->menuOptions[$page]);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$resultPage = new $page;
		$resultPage->wait_until_loaded();
		return $resultPage;
	}
	
	public function open_user_manager()
	{
		$this->selenium->click($this->locators['user_manager_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$userManagerPage = new UserManagerPage();
		$userManagerPage->wait_until_loaded();
		return $userManagerPage;
	}

	public function logout()
	{
		$this->selenium->click($this->locators['logout_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$adminLoginPage = new AdminLoginPage();
		$adminLoginPage->wait_until_loaded();
		return $adminLoginPage;
	}
}
