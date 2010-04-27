<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Contacts component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @since		1.5
 */
class ContactViewContacts extends JView
{
	public $items;
	public $pagination;
	public $state;

	/**
	 * Display the view
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('items');
		$this->pagination	= $this->get('pagination');
		$this->state		= $this->get('state');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}

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
		require_once JPATH_COMPONENT.'/helpers/contact.php';
		$canDo	= ContactHelper::getActions($this->state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_CONTACT_MANAGER_CONTACTS'), 'generic.png');

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('contact.add','JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('contact.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {
			if ($this->state->get('filter.published') != 2){
				JToolBarHelper::divider();
				JToolBarHelper::custom('contacts.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('contacts.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			if ($this->state->get('filter.published') != -1 ) {
				JToolBarHelper::divider();
				if ($this->state->get('filter.published') != 2) {
					JToolBarHelper::archiveList('contacts.archive','JTOOLBAR_ARCHIVE');
				}
				else if ($this->state->get('filter.published') == 2) {
					JToolBarHelper::unarchiveList('contacts.publish', 'JTOOLBAR_UNARCHIVE');
				}
			}
		}
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'contacts.delete','JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('contacts.trash','JTOOLBAR_TRASH');
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_contact');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('Components_Contacts_Contacts');
	}
}