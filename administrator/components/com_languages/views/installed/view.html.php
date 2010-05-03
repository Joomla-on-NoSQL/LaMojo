<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Displays a list of the installed languages.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @since		1.6
 */
class LanguagesViewInstalled extends JView
{
	/**
	 * @var object client object
	 */
	protected $client = null;

	/**
	 * @var boolean|JExeption True, if FTP settings should be shown, or an exeption
	 */
	protected $ftp = null;

	/**
	 * @var string option name
	 */
	protected $option = null;

	/**
	 * @var object pagination information
	 */
	protected $pagination=null;

	/**
	 * @var array languages information
	 */
	protected $rows=null;

	/**
	 * @var object user object
	 */
	protected $user = null;

	/**
	 * @var object form object
	 */
	protected $form = null;

	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		// Get data from the model

		$this->ftp			= $this->get('Ftp');
		$this->option		= $this->get('Option');
		$this->pagination	= $this->get('Pagination');
		$this->rows			= $this->get('Data');
		$this->state		= $this->get('State');
		$this->form			= $this->get('Form');

		$this->addToolbar();
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_LANGUAGES_VIEW_INSTALLED_TITLE'), 'langmanager.png');
		JToolBarHelper::makeDefault('installed.publish','JTOOLBAR_DEFAULT');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_languages');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.languages','JTOOLBAR_HELP');
	}
}