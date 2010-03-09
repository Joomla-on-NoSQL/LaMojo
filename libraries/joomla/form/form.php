<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.filesystem.path');
jimport('joomla.form.formfield');
jimport('joomla.registry.registry');

/**
 * Form Class for the Joomla Framework.
 *
 * This class implements a robust API for constructing, populating, filtering, and validating forms.
 * It uses XML definitions to construct form fields and a variety of field and rule classes to
 * render and validate the form.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JForm
{
	/**
	 * The JRegistry data store for form fields during display.
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $data;

	/**
	 * The form object errors array.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $errors = array();

	/**
	 * The name of the form instance.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $name;

	/**
	 * The form object options for use in rendering and validation.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $options = array();

	/**
	 * The form XML definition.
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $xml;

	/**
	 * Static array of JFormField objects for re-use.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $fields = array();

	/**
	 * Static array of JForm objects for re-use.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $forms = array();

	/**
	 * Search arrays of paths for loading JForm, JFormField, and JFormRule class files.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $paths = array('fields' => array(), 'forms' => array(), 'rules' => array());

	/**
	 * Static array of JFormRule objects for re-use.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $rules = array();

	/**
	 * Method to instantiate the form object.
	 *
	 * @param	string	$name		The name of the form.
	 * @param	array	$options	An array of form options.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function __construct($name, array $options = array())
	{
		// Set the name for the form.
		$this->name = $name;

		// Initialize the JRegistry data.
		$this->data = new JRegistry();

		// Set the options if specified.
		$this->options['control']  = isset($options['control']) ? $options['control'] : false;
	}

	/**
	 * Method to bind data to the form.
	 *
	 * @param	mixed	$data	An array or object of data to bind to the form.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function bind($data)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// The data must be an object or array.
		if (!is_object($data) && !is_array($data)) {
			return false;
		}

		// Convert the input to an array.
		if (is_object($data)) {
			if ($data instanceof JRegistry) {
				// Handle a JRegistry.
				$data = $data->toArray();
			} else if ($data instanceof JObject) {
				// Handle a JObject.
				$data = $data->getProperties();
			} else {
				// Handle other types of objects.
				$data = (array) $data;
			}
		}

		// Process the input data.
		foreach ($data as $k => $v) {

			// If the value is a scalar just process it.
			if (is_scalar($v)) {

				// If the field exists set the value.
				if ($this->findField($k)) {
					$this->data->set($k, $v);
				}
			}
			// If the value is not a scalar hand it off to the recursive bind level method.
			else {
				$this->bindLevel($k, $v);
			}
		}

		return true;
	}

	/**
	 * Method to filter the form data.
	 *
	 * @param	array	$data	An array of field values to filter.
	 * @param	string	$group	The dot-separated form group path on which to filter the fields.
	 *
	 * @return	mixed	boolean	True on sucess.
	 * @since	1.6
	 */
	public function filter($data, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Initialize variables.
		$input	= new JRegistry($data);
		$output	= new JRegistry();

		// Get the fields for which to filter the data.
		$fields = $this->findFieldsByGroup($group);
		if (!$fields) {
			// PANIC!
			return false;
		}

		// Filter the fields.
		foreach ($fields as $field) {

			// Initialize variables.
			$name = (string) $field['name'];

			// Get the field groups for the element.
			$attrs	= $field->xpath('ancestor::fields[@name]/@name');
			$groups	= array_map('strval', $attrs ? $attrs : array());
			$group	= implode('.', $groups);

			// Get the field value from the data input.
			if ($group) {

				// Filter the value if it exists.
				if ($input->get($group.'.'.$name) !== null) {
					$output->set($group.'.'.$name, $this->filterField($field, $input->get($group.'.'.$name)));
				}
			}
			else {
				// Filter the value if it exists.
				if ($input->get($name) !== null) {
					$output->set($name, $this->filterField($field, $input->get($name)));
				}
			}
		}

		return $output->toArray();
	}

	/**
	 * Return all errors, if any.
	 *
	 * @return	array	Array of error messages or JException objects.
	 * @since	1.6
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Method to get a form field represented as a JFormField object.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional dot-separated form group path on which to find the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 *
	 * @return	mixed	The JFormField object for the field or boolean false on error.
	 * @since	1.6
	 */
	public function getField($name, $group = null, $value = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Attempt to find the field by name and group.
		$element = $this->findField($name, $group);

		// If the field element was not found return false.
		if (!$element) {
			return false;
		}

		return $this->loadField($element, $group, $value);
	}

	/**
	 * Method to get an attribute value from a field XML element.  If the attribute doesn't exist or
	 * is null then the optional default value will be used.
	 *
	 * @param	string	$name		The name of the form field for which to get the attribute value.
	 * @param	string	$attribute	The name of the attribute for which to get a value.
	 * @param	mixed	$default	The optional default value to use if no attribute value exists.
	 * @param	string	$group		The optional dot-separated form group path on which to find the field.
	 *
	 * @return	mixed	The attribute value for the field.
	 * @since	1.6
	 */
	public function getFieldAttribute($name, $attribute, $default = null, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			// TODO: throw exception.
			return $default;
		}

		// Find the form field element from the definition.
		$element = $this->findField($name, $group);

		// If the element exists and the attribute exists for the field return the attribute value.
		if (($element instanceof JXMLElement) && ((string) $element[$attribute])) {
			return (string) $element[$attribute];
		}
		// Otherwise return the given default value.
		else {
			return $default;
		}
	}

	/**
	 * Method to get an array of JFormField objects in a given fieldset by name.  If no name is
	 * given then all fields are returned.
	 *
	 * @param	string	$set	The optional name of the fieldset.
	 *
	 * @return	array	The array of JFormField objects in the fieldset.
	 * @since	1.6
	 */
	public function getFieldset($set = null)
	{
		// Initialize variables.
		$fields = array();

		// Get all of the field elements in the fieldset.
		if ($set) {
			$elements = $this->findFieldsByFieldset($set);
		}
		// Get all fields.
		else {
			$elements = $this->findFieldsByGroup();
		}

		// If no field elements were found return empty.
		if (empty($elements)) {
			return $fields;
		}

		// Build the result array from the found field elements.
		foreach ($elements as $element) {

			// Get the field groups for the element.
			$attrs	= $element->xpath('ancestor::fields[@name]/@name');
			$groups	= array_map('strval', $attrs ? $attrs : array());
			$group	= implode('.', $groups);

			// If the field is successfully loaded add it to the result array.
			if ($field = $this->loadField($element, $group)) {
				$fields[$field->id] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Method to get an array of fieldset objects optionally filtered over a given field group.
	 *
	 * @param	string	$group	The dot-separated form group path on which to filter the fieldsets.
	 *
	 * @return	array	The array of fieldset objects.
	 * @since	1.6
	 */
	public function getFieldsets($group = null)
	{
		// Initialize variables.
		$fieldsets = array();
		$sets = array();

		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return $fieldsets;
		}

		if ($group) {

			// Get the fields elements for a given group.
			$elements = & $this->findGroup($group);
			foreach ($elements as & $element) {

				// Get an array of <fieldset /> elements and fieldset attributes within the fields element.
				if ($tmp = $element->xpath('descendant::fieldset[@name] | descendant::field[@fieldset]/@fieldset')) {
					$sets = array_merge($sets, (array) $tmp);
				}
			}
		}
		else {
			// Get an array of <fieldset /> elements and fieldset attributes.
			$sets = $this->xml->xpath('//fieldset[@name] | //field[@fieldset]/@fieldset');
		}

		// If no fieldsets are found return empty.
		if (empty($sets)) {
			return $fieldsets;
		}

		// Process each found fieldset.
		foreach ($sets as $set) {

			// Are we dealing with a fieldset element?
			if ((string) $set['name']) {

				// Only create it if it doesn't already exist.
				if (empty($fieldsets[(string) $set['name']])) {

					// Build the fieldset object.
					$fieldset = (object) array('name' => '', 'label' => '', 'description' => '');
					foreach ($set->attributes() as $name => $value) {
						$fieldset->$name = (string) $value;
					}

					// Add the fieldset object to the list.
					$fieldsets[$fieldset->name] = $fieldset;
				}
			}
			// Must be dealing with a fieldset attribute.
			else {

				// Only create it if it doesn't already exist.
				if (empty($fieldsets[(string) $set])) {

					// Attempt to get the fieldset element for data (throughout the entire form document).
					$tmp = $this->xml->xpath('//fieldset[@name="'.(string) $set.'"]');

					// If no element was found, build a very simple fieldset object.
					if (empty($tmp)) {
						$fieldset = (object) array('name' => (string) $set, 'label' => '', 'description' => '');
					}
					// Build the fieldset object from the element.
					else {
						$fieldset = (object) array('name' => '', 'label' => '', 'description' => '');
						foreach ($tmp[0]->attributes() as $name => $value) {
							$fieldset->$name = (string) $value;
						}
					}

					// Add the fieldset object to the list.
					$fieldsets[$fieldset->name] = $fieldset;
				}
			}
		}

		return $fieldsets;
	}

	/**
	 * Method to get the form control. This string serves as a container for all form fields. For
	 * example, if there is a field named 'foo' and a field named 'bar' and the form control is
	 * empty the fields will be rendered like: <input name="foo" /> and <input name="bar" />.  If
	 * the form control is set to 'joomla' however, the fields would be rendered like:
	 * <input name="joomla[foo]" /> and <input name="joomla[bar]" />.
	 *
	 * @return	string	The form control string.
	 * @since	1.6
	 */
	public function getFormControl()
	{
		return (string) $this->options['control'];
	}

	/**
	 * Method to get an array of JFormField objects in a given field group by name.
	 *
	 * @param	string	$group	The dot-separated form group path for which to get the form fields.
	 * @param	boolean	$nested	True to also include fields in nested groups that are inside of the
	 * 							group for which to find fields.
	 *
	 * @return	array	The array of JFormField objects in the field group.
	 * @since	1.6
	 */
	public function getGroup($group, $nested = false)
	{
		// Initialize variables.
		$fields = array();

		// Get all of the field elements in the field group.
		$elements = $this->findFieldsByGroup($group, $nested);

		// If no field elements were found return empty.
		if (empty($elements)) {
			return $fields;
		}

		// Build the result array from the found field elements.
		foreach ($elements as $element) {
			// If the field is successfully loaded add it to the result array.
			if ($field = $this->loadField($element, $group)) {
				$fields[$field->id] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Method to get a form field markup for the field input.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional dot-separated form group path on which to find the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 *
	 * @return	string	The form field markup.
	 * @since	1.6
	 */
	public function getInput($name, $group = null, $value = null)
	{
		// Attempt to get the form field.
		if ($field = $this->getField($name, $group, $value)) {
			return $field->input;
		}

		return '';
	}

	/**
	 * Method to get a form field markup for the field input.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional dot-separated form group path on which to find the field.
	 *
	 * @return	string	The form field markup.
	 * @since	1.6
	 */
	public function getLabel($name, $group = null)
	{
		// Attempt to get the form field.
		if ($field = $this->getField($name, $group)) {
			return $field->label;
		}

		return '';
	}

	/**
	 * Method to get the form name.
	 *
	 * @return	string	The name of the form.
	 * @since	1.6
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Method to get the value of a field.
	 *
	 * @param	string	$name		The name of the field for which to get the value.
	 * @param	string	$group		The optional dot-separated form group path on which to get the value.
	 * @param	mixed	$default	The optional default value of the field value is empty.
	 *
	 * @return	mixed	The value of the field or the default value if empty.
	 * @since	1.6
	 */
	public function getValue($name, $group = null, $default = null)
	{
		// If a group is set use it.
		if ($group) {
			$return = $this->data->get($group.'.'.$name, $default);
		}
		else {
			$return = $this->data->get($name, $default);
		}

		return $return;
	}

	/**
	 * Method to load the form description from an XML string or object.
	 *
	 * The replace option works per field.  If a field being loaded already exists in the current
	 * form definition then the behavior or load will vary depending upon the replace flag.  If it
	 * is set to true, then the existing field will be replaced in it's exact location by the new
	 * field being loaded.  If it is false, then the new field being loaded will be ignored and the
	 * method will move on to the next field to load.
	 *
	 * @param	string	$data		The name of an XML string or object.
	 * @param	string	$replace	Flag to toggle whether form fields should be replaced if a field
	 *								already exists with the same group/name.
	 *
	 * @return	boolean	True on success, false otherwise.
	 * @since	1.6
	 */
	public function load($data, $replace = true, $xpath = false)
	{
		// If the data to load isn't already an XML element or string return false.
		if ((!$data instanceof JXMLElement) && (!is_string($data))) {
			return false;
		}

		// Attempt to load the XML if a string.
		if (is_string($data)) {
			$data = JFactory::getXML($data, false);

			// Make sure the XML loaded correctly.
			if (!$data) {
				return false;
			}
		}

		// If we have no XML definition at this point let's make sure we get one.
		if (empty($this->xml)) {
			// If no XPath query is set to search for fields, and we have a <form />, set it and return.
			if (!$xpath && ($data->getName() == 'form')) {
				$this->xml = $data;
				return true;
			}
			// Create a root element for the form.
			else {
				$this->xml = new JXMLElement('<form></form>');
			}
		}

		// Get the XML elements to load.
		$elements = array();
		if ($xpath) {
			$elements = $data->xpath($xpath);
		}
		elseif ($data->getName() == 'form') {
			$elements = $data->children();
		}

		// If there is nothing to load return true.
		if (empty($elements)) {
			return true;
		}

		// Load the found form elements.
		foreach ($elements as $element) {

			// Get an array of fields with the correct name.
			$fields = $element->xpath('descendant-or-self::field');
			foreach ($fields as $field) {

				// Get the group names as strings for anscestor fields elements.
				$attrs	= $field->xpath('ancestor::fields[@name]/@name');
				$groups	= array_map('strval', $attrs ? $attrs : array());

				// Check to see if the field exists in the current form.
				if ($current = & $this->findField((string) $field['name'], implode('.', $groups))) {

					// If set to replace found fields remove it from the current definition.
					if ($replace) {
						$dom = dom_import_simplexml($current);
						$dom->parentNode->removeChild($dom);
					}

					// Else remove it from the incoming definition so it isn't replaced.'
					else {
						unset($field);
					}
				}
			}

			// Merge the new field data into the existing XML document.
			self::addNode($this->xml, $element);
		}

		return true;
	}

	/**
	 * Method to load the form description from an XML file.
	 *
	 * The reset option works on a group basis. If the XML file references
	 * groups that have already been created they will be replaced with the
	 * fields in the new XML file unless the $reset parameter has been set
	 * to false.
	 *
	 * @param	string	$file	The filesystem path of an XML file.
	 * @param	string	$reset	Flag to toggle whether the form description should be reset.
	 *
	 * @return	boolean	True on success, false otherwise.
	 * @since	1.6
	 */
	public function loadFile($file, $reset = true)
	{
		// Check to see if the path is an absolute path.
		if (!is_file($file)) {

			// Not an absolute path so let's attempt to find one using JPath.
			$file = JPath::find(self::addFormPath(), strtolower($file).'.xml');

			// If unable to find the file return false.
			if (!$file) {
				return false;
			}
		}

		// Attempt to load the XML file.
		$xml = JFactory::getXML($file, true);

		return $this->load($xml, $reset);
	}

	/**
	 * Method to remove a field from the form definition.
	 *
	 * @param	string	$name		The name of the form field for which remove.
	 * @param	string	$group		The optional dot-separated form group path on which to find the field.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function removeField($name, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			// TODO: throw exception.
			return false;
		}

		// Find the form field element from the definition.
		$element = $this->findField($name, $group);

		// If the element exists remove it from the form definition.
		if ($element instanceof JXMLElement) {
			$dom = dom_import_simplexml($element);
			$dom->parentNode->removeChild($dom);
		}

		return true;
	}

	/**
	 * Method to remove a group from the form definition.
	 *
	 * @param	string	$group	The dot-separated form group path for the group to remove.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function removeGroup($group)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			// TODO: throw exception.
			return false;
		}

		// Get the fields elements for a given group.
		$elements = & $this->findGroup($group);
		foreach ($elements as & $element) {
			$dom = dom_import_simplexml($element);
			$dom->parentNode->removeChild($dom);
		}

		return true;
	}

	/**
	 * Method to reset the form data store and optionally the form XML definition.
	 *
	 * @param	boolean	$xml	True to also reset the XML form definition.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function reset($xml = false)
	{
		unset($this->data);
		$this->data = new JRegistry();

		if ($xml) {
			unset($this->xml);
			$this->xml = new JXMLElement('<form></form>');
		}

		return true;
	}

	/**
	 * Method to set a field XML element to the form definition.  If the replace flag is set then
	 * the field will be set whether it already exists or not.  If it isn't set, then the field
	 * will not be replaced if it already exists.
	 *
	 * @param	object	$element	The XML element object representation of the form field.
	 * @param	string	$group		The optional dot-separated form group path on which to set the field.
	 * @param	boolean	$replace	True to replace an existing field if one already exists.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function setField(& $element, $group = null, $replace = true)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			// TODO: throw exception.
			return false;
		}

		// Make sure the element to set is valid.
		if (!$element instanceof JXMLElement) {
			// TODO: throw exception.
			return false;
		}

		// Find the form field element from the definition.
		$old = & $this->findField((string) $element['name'], $group);

		// If an existing field is found and replace flag is true replace the field.
		if ($replace && !empty($old)) {
			$old = & $element;
		}
		// If an existing field is found and replace flag is false do nothing.
		else if (!$replace && !empty($old)) {
			// Do not replace the field.
		}
		// If no existing field is found find a group element and add the field as a child of it.
		else {
			if ($group) {
				// Get the fields elements for a given group.
				$fields = & $this->findGroup($group);
			}
			else {
				// Get the master fields element.
				$fields = & $this->xml->fields;
			}

			// If an appropriate fields element was found for hte group, add the element.
			if (isset($fields[0]) && ($fields[0] instanceof JXMLElement)) {
				self::addNode($fields[0], $element);
			}
		}

		return true;
	}

	/**
	 * Method to set an attribute value for a field XML element.
	 *
	 * @param	string	$name		The name of the form field for which to set the attribute value.
	 * @param	string	$attribute	The name of the attribute for which to set a value.
	 * @param	mixed	$value		The value to set for the attribute.
	 * @param	string	$group		The optional dot-separated form group path on which to find the field.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function setFieldAttribute($name, $attribute, $value, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			// TODO: throw exception.
			return false;
		}

		// Find the form field element from the definition.
		$element = & $this->findField($name, $group);

		// If the element doesn't exist return false.
		if (!$element instanceof JXMLElement) {
			return false;
		}
		// Otherwise set the attribute and return true.
		else {
			$element[$attribute] = $value;
			return true;
		}
	}

	/**
	 * Method to set some field XML elements to the form definition.  If the replace flag is set then
	 * the fields will be set whether they already exists or not.  If it isn't set, then the fields
	 * will not be replaced if they already exist.
	 *
	 * @param	object	$elements	The array of XML element object representations of the form fields.
	 * @param	string	$group		The optional dot-separated form group path on which to set the fields.
	 * @param	boolean	$replace	True to replace existing fields if they already exist.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function setFields(& $elements, $group = null, $replace = true)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			// TODO: throw exception.
			return false;
		}

		// Make sure the elements to set are valid.
		foreach ($elements as $element) {
			if (!$element instanceof JXMLElement) {
				// TODO: throw exception.
				return false;
			}
		}

		// Set the fields.
		$return = true;
		foreach ($elements as $element) {
			if (!$this->setField($element, $group, $replace)) {
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * Method to set the value of a field. If the field does not exist in the form then the method
	 * will return false.
	 *
	 * @param	string	$name	The name of the field for which to set the value.
	 * @param	string	$group	The optional dot-separated form group path on which to find the field.
	 * @param	mixed	$value	The value to set for the field.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function setValue($name, $group = null, $value = null)
	{
		// If the field does not exist return false.
		if (!$this->findField($name, $group)) {
			return false;
		}

		// If a group is set use it.
		if ($group) {
			$this->data->set($group.'.'.$name, $value);
		}
		else {
			$this->data->set($name, $value);
		}

		return true;
	}

	/**
	 * Method to validate form data.
	 *
	 * Validation warnings will be pushed into JForm::errors and should be
	 * retrieved with JForm::getErrors() when validate returns boolean false.
	 *
	 * @param	array	$data	An array of field values to validate.
	 * @param	string	$group	The optional dot-separated form group path on which to filter the
	 * 							fields to be validated.
	 *
	 * @return	mixed	boolean	True on sucess.
	 * @since	1.6
	 */
	public function validate($data, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Initialize variables.
		$return	= true;

		// Create an input registry object from the data to validate.
		$input = new JRegistry($data);

		// Get the fields for which to validate the data.
		$fields = $this->findFieldsByGroup($group);
		if (!$fields) {
			// PANIC!
			return false;
		}

		// Validate the fields.
		foreach ($fields as $field) {

			// Initialize variables.
			$value	= null;
			$name	= (string) $field['name'];

			// Get the group names as strings for anscestor fields elements.
			$attrs	= $field->xpath('ancestor::fields[@name]/@name');
			$groups	= array_map('strval', $attrs ? $attrs : array());
			$group	= implode('.', $groups);

			// Get the value from the input data.
			if ($group) {
				$value = $input->get($group.'.'.$name);
			}
			else {
				$value = $input->get($name);
			}

			// Validate the field.
			$valid = $this->validateField($field, $group, $value, $input);

			// Check for an error.
			if (JError::isError($valid)) {
				switch ($valid->get('level'))
				{
					case E_ERROR:
						JError::raiseWarning(0, $valid->getMessage());
						return false;
						break;
					default:
						array_push($this->errors, $valid);
						$return = false;
						break;
				}
			}
		}

		return $return;
	}

	/**
	 * Method to bind data to the form for the group level.
	 *
	 * @param	string	$group	The dot-separated form group path on which to bind the data.
	 * @param	mixed	$data	An array or object of data to bind to the form for the group level.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function bindLevel($group, $data)
	{
		// Ensure the input data is an array.
		settype($data, 'array');

		// Process the input data.
		foreach ($data as $k => $v) {

			// If the value is a scalar just process it.
			if (is_scalar($v)) {

				// If the field exists set the value.
				if ($this->findField($k, $group)) {
					$this->data->set($group.'.'.$k, $v);
				}
			}
			// If the value is not a scalar hand it off to the recursive bind level method.
			else {
				$this->bindLevel($group.'.'.$k, $v);
			}
		}
	}

	/**
	 * Method to apply an input filter to a value based on field data.
	 *
	 * @param	string	$element	The XML element object representation of the form field.
	 * @param	mixed	$value		The value to filter for the field.
	 *
	 * @return	mixed	The filtered value.
	 * @since	1.6
	 */
	protected function filterField($element, $value)
	{
		// Make sure there is a valid JXMLElement.
		if (!$element instanceof JXMLElement) {
			return false;
		}

		// Get the field filter type.
		$filter = (string) $element['filter'];

		// If no filter is set return the raw value.
		if (!$filter) {
			return $value;
		}

		// Process the input value based on the filter.
		$return = null;
		switch (strtoupper($filter))
		{
			// Access Control Rules.
			case 'RULES':
				$return = array();
				foreach ((array) $value as $action => $ids) {
					// Build the rules array.
					$return[$action] = array();
					foreach ($ids as $id => $p) {
						if ($p !== '') {
							$return[$action][$id] = ($p == '1' || $p == 'true') ? true : false;
						}
					}
				}
				break;

			// Do nothing, thus leaving the return value as null.
			case 'UNSET':
				break;

			// No Filter.
			case 'RAW':
				$return = $value;
				break;

			// Filter safe HTML.
			case 'SAFEHTML':
				$return = JFilterInput::getInstance(null, null, 1, 1)->clean($value, 'string');
				break;

			// Convert a date to UTC based on the server timezone offset.
			case 'SERVER_UTC':
				if (intval($value)) {
					// Get the server timezone setting.
					$offset	= $config->get('offset');

					// Return a MySQL formatted datetime string in UTC.
					$return = JFactory::getDate($value, $offset)->toMySQL();
				}
				break;

			// Convert a date to UTC based on the user timezone offset.
			case 'USER_UTC':
				if (intval($value)) {
					// Get the user timezone setting defaulting to the server timezone setting.
					$offset	= $user->getParam('timezone', $config->get('offset'));

					// Return a MySQL formatted datetime string in UTC.
					$return = JFactory::getDate($value, $offset)->toMySQL();
				}
				break;

			default:
				// Check for a callback filter.
				if (strpos($filter, '::') !== false && is_callable(explode('::', $filter))) {
					$return = call_user_func(explode('::', $filter), $value);
				}
				// Filter using a callback function if specified.
				else if (function_exists($filter)) {
					$return = call_user_func($filter, $value);
				}
				// Filter using JFilterInput. All HTML code is filtered by default.
				else {
					$return = JFilterInput::getInstance()->clean($value, $filter);
				}
				break;
		}

		return $return;
	}

	/**
	 * Method to get a form field represented as an XML element object.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional dot-separated form group path on which to find the field.
	 *
	 * @return	mixed	The XML element object for the field or boolean false on error.
	 * @since	1.6
	 */
	protected function & findField($name, $group = null)
	{
		// Initialize variables.
		$false		= false;
		$element	= false;
		$fields		= array();

		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return $false;
		}

		// Let's get the appropriate field element based on the method arguments.
		if ($group) {

			// Get the fields elements for a given group.
			$elements = & $this->findGroup($group);

			// Get all of the field elements with the correct name for the fields elements.
			foreach ($elements as $element) {
				// If there are matching field elements add them to the fields array.
				if ($tmp = $element->xpath('descendant::field[@name="'.$name.'"]')) {
					$fields = array_merge($fields, $tmp);
				}
			}

			// Make sure something was found.
			if (!$fields) {
				return $false;
			}

			// Use the first correct match in the given group.
			$groupNames = explode('.', $group);
			foreach ($fields as & $field) {

				// Get the group names as strings for anscestor fields elements.
				$attrs = $field->xpath('ancestor::fields[@name]/@name');
				$names	= array_map('strval', $attrs ? $attrs : array());

				// If the field is in the exact group use it and break out of the loop.
				if ($names == (array) $groupNames) {
					$element = & $field;
					break;
				}
			}
		}
		else {
			// Get an array of fields with the correct name.
			$fields = $this->xml->xpath('//field[@name="'.$name.'"]');

			// Make sure something was found.
			if (!$fields) {
				return $false;
			}

			// Search through the fields for the right one.
			foreach ($fields as & $field) {
				// If we find an anscestor fields element with a group name then it isn't what we want.
				if ($field->xpath('ancestor::fields[@name]')) {
					continue;
				}
				// Found it!
				else {
					$element = & $field;
					break;
				}
			}
		}

		return $element;
	}

	/**
	 * Method to get an array of <field /> elements from the form XML document which are
	 * in a specified fieldset by name.
	 *
	 * @param	string	$name	The name of the fieldset.
	 *
	 * @return	mixed	Boolean false on error or array of JXMLElement objects.
	 * @since	1.6
	 */
	protected function & findFieldsByFieldset($name)
	{
		// Initialize variables.
		$false = false;

		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return $false;
		}

		/*
		 * Get an array of <field /> elements that are underneath a <fieldset /> element
		 * with the appropriate name attribute, and also any <field /> elements with
		 * the appropriate fieldset attribute.
		 */
		$fields = $this->xml->xpath('//fieldset[@name="'.$name.'"]//field | //field[@fieldset="'.$name.'"]');

		return $fields;
	}

	/**
	 * Method to get an array of <field /> elements from the form XML document which are
	 * in a control group by name.
	 *
	 * @param	mixed	$group	The optional dot-separated form group path on which to find the fields.
	 * 							Null will return all fields. False will return fields not in a group.
	 * @param	boolean	$nested	True to also include fields in nested groups that are inside of the
	 * 							group for which to find fields.
	 *
	 * @return	mixed	Boolean false on error or array of JXMLElement objects.
	 * @since	1.6
	 */
	protected function & findFieldsByGroup($group = null, $nested = false)
	{
		// Initialize variables.
		$false = false;
		$fields = array();

		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return $false;
		}

		// Get only fields in a specific group?
		if ($group) {

			// Get the fields elements for a given group.
			$elements = & $this->findGroup($group);

			// Get all of the field elements for the fields elements.
			foreach ($elements as $element) {

				// If there are field elements add them to the return result.
				if ($tmp = $element->xpath('descendant::field')) {

					// If we also want fields in nested groups then just merge the arrays.
					if ($nested) {
						$fields = array_merge($fields, $tmp);
					}
					// If we want to exclude nested groups then we need to check each field.
					else {
						$groupNames = explode('.', $group);
						foreach ($tmp as $field) {
							// Get the names of the groups that the field is in.
							$attrs = $field->xpath('ancestor::fields[@name]/@name');
							$names = array_map('strval', $attrs ? $attrs : array());

							// If the field is in the specific group then add it to the return list.
							if ($names == (array) $groupNames) {
								$fields = array_merge($fields, array($field));
							}
						}
					}
				}
			}
		} else if ($group === false) {
			// Get only field elements not in a group.
			$fields = $this->xml->xpath('descendant::fields[not(@name)]/field | descendant::fields[not(@name)]/fieldset/field ');
		} else {
			// Get an array of all the <field /> elements.
			$fields = $this->xml->xpath('//field');
		}

		return $fields;
	}

	/**
	 * Method to get a form field group represented as an XML element object.
	 *
	 * @param	string	$group	The dot-separated form group path on which to find the group.
	 *
	 * @return	mixed	An array of XML element objects for the group or boolean false on error.
	 * @since	1.6
	 */
	protected function & findGroup($group)
	{
		// Initialize variables.
		$false = false;
		$groups = array();
		$tmp = array();

		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return $false;
		}

		// Make sure there is actually a group to find.
		$group = explode('.', $group);
		if (!empty($group)) {

			// Get any fields elements with the correct group name.
			$elements = $this->xml->xpath('//fields[@name="'.(string) $group[0].'"]');

			// Check to make sure that there are no parent groups for each element.
			foreach ($elements as $element) {
				if (!$element->xpath('ancestor::fields[@name]')) {
					$tmp[] = $element;
				}
			}

			// Iterate through the nested groups to find any matching form field groups.
			for ($i = 1, $n = count($group); $i < $n; $i++) {

				// Initialize some loop variables.
				$validNames = array_slice($group, 0, $i+1);
				$current = $tmp;
				$tmp = array();

				// Check to make sure that there are no parent groups for each element.
				foreach ($current as $element) {

					// Get any fields elements with the correct group name.
					$children = $element->xpath('descendant::fields[@name="'.(string) $group[$i].'"]');

					// For the found fields elements validate that they are in the correct groups.
					foreach ($children as $fields) {

						// Get the group names as strings for anscestor fields elements.
						$attrs = $fields->xpath('ancestor-or-self::fields[@name]/@name');
						$names = array_map('strval', $attrs ? $attrs : array());

						// If the group names for the fields element match the valid names at this
						// level add the fields element.
						if ($validNames == $names) {
							$tmp[] = $fields;
						}
					}
				}
			}

			// Only include valid XML objects.
			foreach ($tmp as $element) {
				if ($element instanceof JXMLElement) {
					$groups[] = $element;
				}
			}
		}

		return $groups;
	}

	/**
	 * Method to load, setup and return a JFormField object based on field data.
	 *
	 * @param	string	$element	The XML element object representation of the form field.
	 * @param	string	$group		The optional dot-separated form group path on which to find the field.
	 * @param	mixed	$value		The optional value to use as the default for the field.
	 *
	 * @return	mixed	The JFormField object for the field or boolean false on error.
	 * @since	1.6
	 */
	protected function loadField($element, $group = null, $value = null)
	{
		// Make sure there is a valid JXMLElement.
		if (!$element instanceof JXMLElement) {
			return false;
		}

		// Get the field type.
		$type = $element['type'] ? (string) $element['type'] : 'text';

		// Load the JFormField object for the field.
		$field = $this->loadFieldType($type);

		// If the object could not be loaded, get a text field object.
		if ($field === false) {
			$field = $this->loadFieldType('text');
		}

		// Get the value for the form field if not set. Default to the 'default' attribute for the field.
		if ($value === null) {
			$value = $this->getValue((string) $element['name'], $group, (string) $element['default']);
		}

		// Setup the JFormField object.
		$field->setForm($this);

		if ($field->setup($element, $value, $group)) {
			return $field;
		}
		else {
			return false;
		}
	}

	/**
	 * Method to load a form field object given a type.
	 *
	 * @param	string	$type	The field type.
	 * @param	boolean	$new	Flag to toggle whether we should get a new instance of the object.
	 *
	 * @return	mixed	JFormField object on success, false otherwise.
	 * @since	1.6
	 */
	protected function & loadFieldType($type, $new = true)
	{
		// Initialize variables.
		$false	= false;
		$key	= md5($type);
		$class	= 'JFormField'.ucfirst($type);

		// Return the JFormField object if it already exists and we don't need a new one.
		if (isset(self::$fields[$key]) && $new === false) {
			return self::$fields[$key];
		}

		// Attempt to import the JFormField class file if it isn't already imported.
		if (!class_exists($class)) {

			// Get the field search path array.
			$paths = self::addFieldPath();

			// If the type is complex, add the base type to the paths.
			if ($pos = strpos($type, '_')) {

				// Add the complex type prefix to the paths.
				for ($i = 0, $n = count($paths); $i < $n; $i++) {
					// Derive the new path.
					$path = $paths[$i].DS.strtolower(substr($type, 0, $pos));

					// If the path does not exist, add it.
					if (!in_array($path, $paths)) {
						array_unshift($paths, $path);
					}
				}

				// Break off the end of the complex type.
				$type = substr($type, $pos+1);
			}

			// Try to find the field file.
			if ($file = JPath::find($paths, strtolower($type).'.php')) {
				require_once $file;
			} else {
				return $false;
			}

			// Check once and for all if the class exists.
			if (!class_exists($class)) {
				return $false;
			}
		}

		// Instantiate a new field object.
		self::$fields[$key] = new $class();

		return self::$fields[$key];
	}

	/**
	 * Method to load a form rule object given a type.
	 *
	 * @param	string	$type	The rule type.
	 * @param	boolean	$new	Flag to toggle whether we should get a new instance of the object.
	 *
	 * @return	mixed	JFormRule object on success, false otherwise.
	 * @since	1.6
	 */
	protected function & loadRuleType($type, $new = true)
	{
		// Initialize variables.
		$false	= false;
		$key	= md5($type);
		$class	= 'JFormRule'.ucfirst($type);

		// Return the JFormRule object if it already exists and we don't need a new one.
		if (isset(self::$rules[$key]) && $new === false) {
			return self::$rules[$key];
		}

		// Attempt to import the JFormRule class file if it isn't already imported.
		if (!class_exists($class)) {

			// Get the field search path array.
			$paths = self::addRulePath();

			// If the type is complex, add the base type to the paths.
			if ($pos = strpos($type, '_')) {

				// Add the complex type prefix to the paths.
				for ($i = 0, $n = count($paths); $i < $n; $i++) {
					// Derive the new path.
					$path = $paths[$i].DS.strtolower(substr($type, 0, $pos));

					// If the path does not exist, add it.
					if (!in_array($path, $paths)) {
						array_unshift($paths, $path);
					}
				}

				// Break off the end of the complex type.
				$type = substr($type, $pos+1);
			}

			// Try to find the field file.
			if ($file = JPath::find($paths, strtolower($type).'.php')) {
				require_once $file;
			} else {
				return $false;
			}

			// Check once and for all if the class exists.
			if (!class_exists($class)) {
				return $false;
			}
		}

		// Instantiate a new field object.
		self::$rules[$key] = new $class();

		return self::$rules[$key];
	}

	/**
	 * Method to validate a JFormField object based on field data.
	 *
	 * @param	string	$element	The XML element object representation of the form field.
	 * @param	string	$group		The optional dot-separated form group path on which to find the field.
	 * @param	mixed	$value		The optional value to use as the default for the field.
	 * @param	object	$input		An optional JRegistry object with the entire data set to validate
	 * 								against the entire form.
	 *
	 * @return	mixed	Boolean true if field value is valid, JException on failure.
	 * @since	1.6
	 */
	protected function validateField($element, $group = null, $value = null, $input = null)
	{
		// Make sure there is a valid JXMLElement.
		if (!$element instanceof JXMLElement) {
			return new JException(JText::_('LIB_FORM_VALIDATE_FIELD_ERROR'), 0, E_ERROR);
		}

		// Initialize variables.
		$valid = true;

		// Check if the field is required.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');
		if ($required) {

			// If the field is required and the value is empty return an error message.
			if (($value === '') || ($value === null)) {

				// Does the field have a defined error message?
				$message = (string) $element['message'];
				if ($message) {
					return new JException(JText::_($message), 0, E_WARNING);
				} else {
					return new JException(JText::sprintf('LIB_FORM_VALIDATE_FIELD_REQUIRED', JText::_((string) $element['name'])), 0, E_WARNING);
				}
			}
		}

		// Get the field validation rule.
		if ($type = (string) $element['validate']) {
			// Load the JFormRule object for the field.
			$rule = $this->loadRuleType($type);

			// If the object could not be loaded return an error message.
			if ($rule === false) {
				return new JException(JText::sprintf('LIB_FORM_VALIDATE_FIELD_RULE_MISSING', $rule), 0, E_ERROR);
			}

			// Run the field validation rule test.
			$valid = $rule->test($element, $value, $group, $input, $this);

			// Check for an error in the validation test.
			if (JError::isError($valid)) {
				return $valid;
			}
		}

		// Check if the field is valid.
		if ($valid === false) {

			// Does the field have a defined error message?
			$message = (string) $element['message'];
			if ($message) {
				return new JException(JText::_($message), 0, E_WARNING);
			} else {
				return new JException(JText::sprintf('LIB_FORM_VALIDATE_FIELD_INVALID', JText::_((string) $element['name'])), 0, E_WARNING);
			}
		}

		return true;
	}

	/**
	 * Method to add a path to the list of field include paths.
	 *
	 * @param	mixed	$new	A path or array of paths to add.
	 *
	 * @return	array	The list of paths that have been added.
	 * @since	1.6
	 */
	public static function addFieldPath($new = null)
	{
		// Add the default form search path if not set.
		if (empty(self::$paths['fields'])) {
			self::$paths['fields'][] = dirname(__FILE__).'/fields';
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path) {
			if (!in_array($path, self::$paths['fields'])) {
				array_unshift(self::$paths['fields'], trim($path));
			}
		}

		return self::$paths['fields'];
	}

	/**
	 * Method to add a path to the list of form include paths.
	 *
	 * @param	mixed	$new	A path or array of paths to add.
	 *
	 * @return	array	The list of paths that have been added.
	 * @since	1.6
	 */
	public static function addFormPath($new = null)
	{
		// Add the default form search path if not set.
		if (empty(self::$paths['forms'])) {
			self::$paths['forms'][] = dirname(__FILE__).'/forms';
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path) {
			if (!in_array($path, self::$paths['forms'])) {
				array_unshift(self::$paths['forms'], trim($path));
			}
		}

		return self::$paths['forms'];
	}

	/**
	 * Method to add a path to the list of rule include paths.
	 *
	 * @param	mixed	$new	A path or array of paths to add.
	 *
	 * @return	array	The list of paths that have been added.
	 * @since	1.6
	 */
	public static function addRulePath($new = null)
	{
		// Add the default form search path if not set.
		if (empty(self::$paths['rules'])) {
			self::$paths['rules'][] = dirname(__FILE__).'/rules';
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path) {
			if (!in_array($path, self::$paths['rules'])) {
				array_unshift(self::$paths['rules'], trim($path));
			}
		}

		return self::$paths['rules'];
	}

	/**
	 * Method to get an instance of a form.
	 *
	 * @param	string	$data		The name of an XML file or string to load as the form definition.
	 * @param	string	$name		The name of the form.
	 * @param	string	$file		Flag to toggle whether the $data is a file path or a string.
	 * @param	array	$options	An array of form options.
	 *
	 * @return	object	JForm instance.
	 * @since	1.6
	 */
	public static function getInstance($data, $name = 'form', $file = true, $options = array())
	{
		// Only instantiate the form if it does not already exist.
		if (!isset(self::$forms[$name])) {

			// Instantiate the form.
			self::$forms[$name] = new JForm($name, $options);

			// Load the data.
			if ($file) {
				self::$forms[$name]->loadFile($data);
			} else {
				self::$forms[$name]->load($data);
			}
		}

		return self::$forms[$name];
	}

	/**
	 * Adds a new child SimpleXMLElement node to the source.
	 *
	 * @param	SimpleXMLElement	The source element on which to append.
	 * @param	SimpleXMLElement	The new element to append.
	 */
	protected static function addNode(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		// Add the new child node.
		$node = $source->addChild($new->getName(), trim($new));

		// Add the attributes of the child node.
		foreach ($new->attributes() as $name => $value) {
			$node->addAttribute($name, $value);
		}

		// Add any children of the new node.
		foreach ($new->children() as $child) {
			self::addNode($node, $child);
		}
	}

	protected static function mergeNode(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		// Update the attributes of the child node.
		foreach ($new->attributes() as $name => $value) {
			if (isset($source[$name])) {
				$source[$name] = (string) $value;
			} else {
				$source->addAttribute($name, $value);
			}
		}

		// What to do with child elements?
	}

	/**
	 * Merges new elements into a source <fields> element.
	 *
	 * @param	SimpleXMLElement	The source element.
	 * @param	SimpleXMLElement	The new element to merge.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected static function mergeNodes(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		// The assumption is that the inputs are at the same relative level.
		// So we just have to scan the children and deal with them.

		// Update the attributes of the child node.
		foreach ($new->attributes() as $name => $value) {
			if (isset($source[$name])) {
				$source[$name] = (string) $value;
			} else {
				$source->addAttribute($name, $value);
			}
		}

		foreach ($new->children() as $child) {
			$type = $child->getName();
			$name = $child['name'];

			// Does this node exist?
			$fields = $source->xpath($type.'[@name="'.$name.'"]');

			if (empty($fields)) {
				// This node does not exist, so add it.
				self::addNode($source, $child);
			} else {
				// This node does exist.
				switch ($type) {
					case 'field':
						self::mergeNode($fields[0], $child);
						break;

					default:
						self::mergeNodes($fields[0], $child);
						break;
				}
			}
		}
	}
}
