<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');
jimport('joomla.plugin.helper');
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @since 1.5
 */
class ContactModelContact extends JModelItem
{
	/**
	 * @since	1.6
	 */
	protected $view_item = 'contact';
	
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_contact.contact';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('contact.id', $pk);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
					
		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_contact')) &&  (!$user->authorise('core.edit', 'com_contact'))){
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	}

	/**
	 * Gets a list of contacts
	 * @param array
	 * @return mixed Object or null
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('contact.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select($this->getState('item.select', 'a.*') . ','
				. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug, '
				. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END AS catslug ');
				$query->from('#__contact_details AS a');

				// Join on category table.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access');
				$query->join('LEFT', '#__categories AS c on c.id = a.catid');


				// Join over the categories to get parent category titles
				$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias');
				$query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

				$query->where('a.id = ' . (int) $pk);

				// Filter by start and end dates.
				$nullDate = $db->Quote($db->getNullDate());
				$nowDate = $db->Quote(JFactory::getDate()->toMySQL());


				// Filter by published state.
				$published = $this->getState('filter.published');
				$archived = $this->getState('filter.archived');
				if (is_numeric($published)) {
					$query->where('(a.published = ' . (int) $published . ' OR a.published =' . (int) $archived . ')');
					$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
					$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
				}

				$db->setQuery($query);

				$data = $db->loadObject();

				if ($error = $db->getErrorMsg()) {
					throw new JException($error);
				}

				if (empty($data)) {
					throw new JException(JText::_('COM_CONTACT_ERROR_CONTACT_NOT_FOUND'), 404);
				}

				// Check for published state if filter set.
				if (((is_numeric($published)) || (is_numeric($archived))) && (($data->published != $published) && ($data->published != $archived)))
				{
					JError::raiseError(404, JText::_('COM_CONTACT_ERROR_CONTACT_NOT_FOUND'));
				}

				// Convert parameter fields to objects.
				$registry = new JRegistry;
				$registry->loadJSON($data->params);
				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				$registry = new JRegistry;
				$registry->loadJSON($data->metadata);
				$data->metadata = $registry;

				// Compute access permissions.
				if ($access = $this->getState('filter.access')) {
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				}
				else {
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					if ($data->catid == 0 || $data->category_access === null) {
						$data->params->set('access-view', in_array($data->access, $groups));
					}
					else {
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}

				$this->_item[$pk] = $data;
			}
			catch (JException $e)
			{
				$this->setError($e);
				$this->_item[$pk] = false;
			}

		}
		if ($this->_item[$pk])
		{
			if ($extendedData = $this->getContactQuery($pk)) {
				$this->_item[$pk]->articles = $extendedData->articles;
				$this->_item[$pk]->profile = $extendedData->profile;
			}
		}
  		return $this->_item[$pk];

	}




	protected function  getContactQuery($pk = null)
	{
		// TODO: Cache on the fingerprint of the arguments
		$db		= $this->getDbo();
		$user	= JFactory::getUser();
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('contact.id');

		$query	= $db->getQuery(true);
		if ($pk) {
			$query->select('a.*, cc.access as category_access, cc.title as category_name, '
			. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug, '
			. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END AS catslug ');

			$query->from('#__contact_details AS a');

			$query->join('INNER', '#__categories AS cc on cc.id = a.catid');

			$query->where('a.id = ' . (int) $pk);
			$published = $this->getState('filter.published');
			$archived = $this->getState('filter.archived');
			if (is_numeric($published)) {
				$query->where('a.published IN (1,2)');
				$query->where('cc.published IN (1,2)');
			}
			$groups		= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');

			try {
				$db->setQuery($query);
				$result = $db->loadObject();
	
				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}
	
				if (empty($result)) {
						throw new JException(JText::_('COM_CONTACT_ERROR_CONTACT_NOT_FOUND'), 404);
				}

			// If we are showing a contact list, then the contact parameters take priority
			// So merge the contact parameters with the merged parameters
				if ($this->getState('params')->get('show_contact_list')) {
					$registry = new JRegistry;
					$registry->loadJSON($result->params);
					$this->getState('params')->merge($registry);
				}
			} catch (Exception $e) {
				$this->setError($e);
				return false;
			}

			if ($result) {
				$user	= JFactory::getUser();
				$groups	= implode(',', $user->getAuthorisedViewLevels());
				//get the content by the linked user
				$query	= $db->getQuery(true);
				$query->select('id, title, state, access, created'); 
				$query->from('#__content');
				$query->where('created_by = '.(int)$result->user_id);
				$query->where('access IN ('. $groups.')');
				$query->order('state DESC, created DESC');
				if (is_numeric($published)) {
					$query->where('state IN (1,2)');
				}
				$db->setQuery($query, 0, 10);
				$articles = $db->loadObjectList();
				$result->articles = $articles;

			//get the profile information for the linked user
			if ($result) {
					require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'models'.DS.'user.php';
					$userModel = JModel::getInstance('User','UsersModel',array('ignore_request' => true));
						$data = $userModel->getItem((int)$result->user_id);
			
					JPluginHelper::importPlugin('user');
					$form = new JForm('com_users.profile');
					// Get the dispatcher.
					$dispatcher	= JDispatcher::getInstance();
	
					// Trigger the form preparation event.
					$dispatcher->trigger('onContentPrepareForm', array($form, $data));
					// Trigger the data preparation event.
					$dispatcher->trigger('onContentPrepareData', array('com_users.profile', $data));
	
					// Load the data into the form after the plugins have operated.
					$form->bind($data);
					$result->profile = $form;
				}

			$this->contact = $result;
			return $result;
			}
		}
	}
	/**
	 * Method to get the contact form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * 
	 *
	 * @param	array	$data		An optional array of data for the form to interrogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		
		// Get the form.
		$form = $this->loadForm('com_contact.contact', 'contact', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		return $this->getData();
	}
	/**
	 * Method to get a form object.
	 *
	 * @param	string		$name		The name of the form.
	 * @param	string		$source		The form source. Can be XML string if file flag is set to false.
	 * @param	array		$options	Optional array of options for the form creation.
	 * @param	boolean		$clear		Optional argument to force load a new form.
	 * @param	string		$xpath		An optional xpath to search for the fields.
	 * @return	mixed		JForm object on success, False on error.
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control']	= JArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source.serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear) {
			return $this->_forms[$hash];
		}

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/rules');
		try {
			$form = JForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data']) {
				// Get the data for the form.
				$data = $this->loadFormData();
			} else {
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);

		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}
	
	function &getData()
	{
		$user = JFactory::getUser();
		$data = new stdClass();




		return $data;
	}
	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param	object	A form object.
	 * @param	mixed	The data expected for the form.
	 * @param	string	The name of the plugin group to import (defaults to "content").
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		// Import the approriate plugin group.
		JPluginHelper::importPlugin($group);

		// Get the dispatcher.
		$dispatcher	= JDispatcher::getInstance();

		// Trigger the form preparation event.
		$results = $dispatcher->trigger('onContentPrepareForm', array($form, $data));

		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true)) {
			// Get the last error.
			$error = $dispatcher->getError();

			// Convert to a JException if necessary.
			if (!JError::isError($error)) {
				throw new Exception($error);
			}
		}
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param	object		$form		The form to validate against.
	 * @param	array		$data		The data to validate.
	 * @return	mixed		Array of filtered data if valid, false otherwise.
	 * @since	1.1
	 */
	function validate($form, $data)
	{
		// Filter and validate the form data.
		$data	= $form->filter($data);
		$return	= $form->validate($data);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false) {
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message) {
				$this->setError(JText::_($message));
			}

			return false;
		}

		return $data;
	}
	
	
}

