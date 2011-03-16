<?php

require_once 'includes/SeleniumConnection.php';

class UserEditPage extends BasePage
{

	private $locators = array(
		"page_title" => "css=.pagetitle h2",
		'name' => 'jform_name',
		'username' => 'jform_username',
		'password' => 'jform_password',
		'password2' => 'jform_password2',
		'email' => 'jform_email',
		'save_and_close_button' => 'link=Save & Close'
	);

	function __set($property, $value)
	{
		switch ($property)
		{
			// cases can be stacked so all the 'text' ones here
			case 'name':
			case 'username':
			case 'password':
			case 'password2':
			case 'email':
				$this->selenium->type($this->locators[$property], $value);
				break;
			// if there were other types of elements like checks and selects
			// there would be another stack of cases here
			default:
				$this->$property = $value;
				break;
		}
	}

	function __get($property)
	{
		switch ($property)
		{
			case 'page_title':
				return $this->selenium->getText($this->locators[$property]);
			default:
				return $this->$property;
		}
	}

	function wait_until_loaded()
	{
		$this->waitForElementAvailable($this->locators['page_title']);
	}

	public function save_and_close_success()
	{
		$this->selenium->click($this->locators['save_and_close_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$userManagerPage = new UserManagerPage();
		$userManagerPage->wait_until_loaded();
		return $userManagerPage;
	}
}
