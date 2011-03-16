<?php

require_once 'pages/BasePage.php';
require_once 'includes/SeleniumConnection.php';

class UserManagerPage extends BasePage
{

	private $locators = array(
		"page_title" => "css=.pagetitle h2",
		'filter_search' => 'filter_search',
		'new_user_button' => 'link=New',
		'search_button' => '//button[@type=\'submit\']',
		'checkbox' => 'cb',
		'delete_button' => 'link=Delete'
	);

	function __set($property, $value)
	{
		switch ($property)
		{
			// cases can be stacked so all the 'text' ones here
			case 'filter_search':
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

	public function delete_user($username)
	{
		$this->search_user($username);
		$this->toggle_checkbox(0);
		$this->selenium->click($this->locators['delete_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		return $this;
	}

	public function toggle_checkbox($number)
	{
		$this->selenium->click($this->locators['checkbox'].$number);
		return $this;
	}
	
	public function search_user($username)
	{
		$this->filter_search = $username;
		$this->selenium->click($this->locators['search_button']);
		sleep(13);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$this->wait_until_loaded();
		return $this;
	}
	
	public function new_user()
	{
		$this->selenium->click($this->locators['new_user_button']);
		$this->selenium->waitForPageToLoad(parent::$string_timeout);
		$userEditPage = new UserEditPage();
		$userEditPage->wait_until_loaded();
		return $userEditPage;
	}

}
