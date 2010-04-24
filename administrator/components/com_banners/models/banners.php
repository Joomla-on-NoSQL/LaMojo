<?php

/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of banner records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class BannersModelBanners extends JModelList {

	/**
	 * Method to get the maximum ordering value for each category.
	 *
	 * @since	1.6
	 */
	function &getCategoryOrders() {
		if (!isset($this->cache['categoryorders'])) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('MAX(ordering) as `max`, catid');
			$query->select('catid');
			$query->from('#__banners');
			$query->group('catid');
			$db->setQuery($query);
			$this->cache['categoryorders'] = $db->loadAssocList('catid', 0);
		}
		return $this->cache['categoryorders'];
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery() {

		// Initialise variables.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($this->getState('list.select', 'a.id AS id, a.name AS name, a.alias AS alias,' . 'a.checked_out AS checked_out,' . 'a.checked_out_time AS checked_out_time, a.catid AS catid,' . 'a.clicks AS clicks, a.metakey AS metakey, a.sticky AS sticky,' . 'a.impmade AS impmade, a.imptotal AS imptotal,' . 'a.state AS state, a.ordering AS ordering,' . 'a.purchase_type as purchase_type,' . 'a.language'));
		$query->from('`#__banners` AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the clients.
		$query->select('cl.name AS client_name,cl.purchase_type as client_purchase_type');
		$query->join('LEFT', '#__banner_clients AS cl ON cl.id = a.cid');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int)$published);
		}
		else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = ' . (int)$categoryId);
		}

		// Filter by client.
		$clientId = $this->getState('filter.client_id');
		if (is_numeric($clientId)) {
			$query->where('a.cid = ' . (int)$clientId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int)substr($search, 3));
			}
			else {
				$search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
				$query->where('(a.name LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = ' . $db->quote($language == '?' ? '' : $language));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol == 'ordering' || $orderCol == 'category_title') {
			$orderCol = 'category_title ' . $orderDirn . ', ordering';
		}
		$query->order($db->getEscaped($orderCol . ' ' . $orderDirn));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '') {

		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.access');
		$id.= ':' . $this->getState('filter.published');
		$id.= ':' . $this->getState('filter.category_id');
		$id.= ':' . $this->getState('filter.language');
		return parent::getStoreId($id);
	}

	/**
	 * Method to get the row form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm() {

		// Initialise variables.
		$app = & JFactory::getApplication();

		// Get the form.
		jimport('joomla.form.form');
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$form = & JForm::getInstance($this->context, 'banners', array('control' => 'filters', 'event' => 'onPrepareForm'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState($this->context . '.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}
		return $form;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Banner', $prefix = 'BannersTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState() {

		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$data = JRequest::getVar('filters');
		if (empty($data)) {
			$data = $app->getUserState($this->context . '.data');
		}
		else {
			$app->setUserState($this->context . '.data', $data);
		}
		$this->setState('filter.search', isset($data['search']['expr']) ? $data['search']['expr'] : '');
		$this->setState('filter.published', isset($data['select']['state']) ? $data['select']['state'] : '');
		$this->setState('filter.access', isset($data['select']['access']) ? $data['select']['access'] : '');
		$this->setState('filter.client_id', isset($data['select']['client']) ? $data['select']['client'] : '');
		$this->setState('filter.category_id', isset($data['select']['category']) ? $data['select']['category'] : '');
		$this->setState('filter.language', isset($data['select']['language']) ? $data['select']['language'] : '');

		// Load the parameters.
		$params = JComponentHelper::getParams('com_banners');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('name', 'asc');
	}
}

