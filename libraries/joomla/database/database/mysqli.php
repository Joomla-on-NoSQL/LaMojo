<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Database
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * MySQLi database driver
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.0
 */
class JDatabaseMySQLi extends JDatabase
{
	/**
	 *  The database driver name
	 *
	 * @var string
	 */
	public $name = 'mysqli';

	/**
	 * The null/zero date string
	 *
	 * @var string
	 */
	protected $_nullDate = '0000-00-00 00:00:00';

	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	protected $_nameQuote = '`';

	/**
	* Database object constructor
	*
	* @access	public
	* @param	array	List of options used to configure the connection
	* @since	1.5
	* @see		JDatabase
	*/
	protected function __construct($options)
	{
		$host		= array_key_exists('host', $options)	? $options['host']		: 'localhost';
		$user		= array_key_exists('user', $options)	? $options['user']		: '';
		$password	= array_key_exists('password',$options)	? $options['password']	: '';
		$database	= array_key_exists('database',$options)	? $options['database']	: '';
		$select		= array_key_exists('select', $options)	? $options['select']	: true;

		// Unlike mysql_connect(), mysqli_connect() takes the port and socket
		// as separate arguments. Therefore, we have to extract them from the
		// host string.
		$port	= NULL;
		$socket	= NULL;
		$targetSlot = substr(strstr($host, ":"), 1);
		if (!empty($targetSlot)) {
			// Get the port number or socket name
			if (is_numeric($targetSlot))
				$port	= $targetSlot;
			else
				$socket	= $targetSlot;

			// Extract the host name only
			$host = substr($host, 0, strlen($host) - (strlen($targetSlot) + 1));
			// This will take care of the following notation: ":3306"
			if ($host == '')
				$host = 'localhost';
		}

		// perform a number of fatality checks, then return gracefully
		if (!class_exists('mysqli')) {
			throw new JException('The database php extension "mysqli" is not available', 1255, E_ERROR, 'mysqli', true);
		}

		// connect to the server
		$this->_resource = new mysqli($host, $user, $password, null, $port, $socket);

		if ($this->_resource->connect_error) {
			throw new JException('Could not connect database', 1256, E_WARNING, array('errorNum' => 0, 'errorMsg'=>$this->_resource->connect_error), true);
		}

		// finalize initialization
		parent::__construct($options);

		// select the database
		if ($select) {
			$this->select($database);
		}
	}

	/**
	 * Database object destructor
	 *
	 * @return boolean
	 * @since 1.5
	 */
	public function __destruct()
	{
		$return = false;
		if (is_object($this->_resource)) {
			$return = $this->_resource->close();
		}
		return $return;
	}

	/**
	 * Test to see if the MySQLi connector is available
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	public static function test()
	{
		return (class_exists('mysqli'));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @access	public
	 * @return	boolean
	 * @since	1.5
	 */
	public function connected()
	{
		return $this->_resource->ping();
	}

	/**
	 * Select a database for use
	 *
	 * @access	public
	 * @param	string $database
	 * @return	boolean True if the database has been successfully selected
	 * @since	1.5
	 */
	public function select($database)
	{
		if (! $database)
		{
			return false;
		}

		if (!$this->_resource->select_db($database)) {
			$this->_errorNum = mysqli_errno($this->_resource);
			$this->_errorMsg = mysqli_error($this->_resource);
			throw new JException('Could not select database', 1257, E_ERROR, array('errorNum' => $this->_errorNum, 'errorMsg' => $this->_errorMsg), true);
			return false;
		}

		return true;
	}

	/**
	 * Determines UTF support
	 *
	 * @access public
	 * @return boolean True - UTF is supported
	 */
	public function hasUTF()
	{
		$verParts = explode('.', $this->getVersion());
		return ($verParts[0] == 5 || ($verParts[0] == 4 && $verParts[1] == 1 && (int)$verParts[2] >= 2));
	}

	/**
	 * Custom settings for UTF support
	 *
	 * @access public
	 */
	public function setUTF()
	{
		return $this->_resource->query("SET NAMES 'utf8'");
	}

	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 * @access	public
	 * @abstract
	 */
	public function getEscaped($text, $extra = false)
	{
		$result = $this->_resource->real_escape_string($text);
		if ($extra) {
			$result = addcslashes($result, '%_');
		}
		return $result;
	}
	/**
	* Execute the query
	*
	* @access public
	* @return mixed A database resource if successful, FALSE if not.
	*/
	public function query()
	{
		if (!is_object($this->_resource)) {
			throw new JException('Database not connected', 1258, E_ERROR);
		}

		$sql = $this->_sql;
		if ($this->_limit > 0 || $this->_offset > 0) {
			$sql .= ' LIMIT '.$this->_offset.', '.$this->_limit;
		}
		if ($this->_debug) {
			$this->_ticker++;
			$this->_log[] = $sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		$this->_cursor = $this->_resource->query($sql);

		if (!$this->_cursor)
		{
			$this->_errorNum = mysqli_errno($this->_resource);
			$this->_errorMsg = mysqli_error($this->_resource)." SQL=$sql";
			throw new JException('Database Query Error', 1259, E_ERROR, array('errorNum' => $this->_errorNum, 'errorMsg' => $this->_errorMsg), true);
		}
		return $this->_cursor;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @return int The number of affected rows in the previous operation
	 * @since 1.0.5
	 */
	public function getAffectedRows()
	{
		return $this->_resource->affected_rows;
	}

	/**
	* Execute a batch query
	*
	* @access public
	* @return mixed A database resource if successful, FALSE if not.
	*/
	public function queryBatch($abort_on_error=true, $p_transaction_safe = false)
	{
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if ($p_transaction_safe) {
			$this->_sql = rtrim($this->_sql, '; \t\r\n\0');
			$si = $this->getVersion();
			preg_match_all("/(\d+)\.(\d+)\.(\d+)/i", $si, $m);
			if ($m[1] >= 4) {
				$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 19) {
				$this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 17) {
				$this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
			}
		}
		$query_split = $this->splitSql($this->_sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim($command_line);
			if ($command_line != '') {
				$this->_cursor = $this->_resource->query($command_line);
				if ($this->_debug) {
					$this->_ticker++;
					$this->_log[] = $command_line;
				}
				if (!$this->_cursor) {
					$error = 1;
					$this->_errorNum .= mysqli_errno($this->_resource) . ' ';
					$this->_errorMsg .= mysqli_error($this->_resource)." SQL=$command_line <br />";
					if ($abort_on_error) {
						throw new JException('Database query error', 1259, E_ERROR, array('errorNum'=>$this->_errorNum, 'errorMsg'=>$this->_errorMsg), true);
					}
				}
			}
		}
		return $error ? false : true;
	}

	/**
	 * Diagnostic function
	 *
	 * @access public
	 * @return	string
	 */
	public function explain()
	{
		$temp = $this->_sql;
		$this->_sql = "EXPLAIN $this->_sql";

		$cur = $this->query();

		$first = true;

		$buffer = '<table id="explain-sql">';
		$buffer .= '<thead><tr><td colspan="99">'.$this->getQuery().'</td></tr>';
		while ($row = $cur->fetch_assoc()) {
			if ($first) {
				$buffer .= '<tr>';
				foreach ($row as $k=>$v) {
					$buffer .= '<th>'.$k.'</th>';
				}
				$buffer .= '</tr>';
				$first = false;
			}
			$buffer .= '</thead><tbody><tr>';
			foreach ($row as $k=>$v) {
				$buffer .= '<td>'.$v.'</td>';
			}
			$buffer .= '</tr>';
		}
		$buffer .= '</tbody></table>';
		$cur->free();

		$this->_sql = $temp;

		return $buffer;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @return int The number of rows returned from the most recent query.
	 */
	public function getNumRows($cur=null)
	{
		return $cur ? $cur->num_rows : $this->_cursor->num_rows;
	}

	/**
	* This method loads the first field of the first row returned by the query.
	*
	* @access public
	* @return The value returned in the query or null if the query failed.
	*/
	public function loadResult()
	{
		$cur = $this->query();
		$ret = null;
		if ($row = $cur->fetch_row()) {
			$ret = $row[0];
		}
		$cur->free();
		return $ret;
	}

	/**
	* Load an array of single field results into an array
	*
	* @access public
	*/
	public function loadResultArray($numinarray = 0)
	{
		$cur = $this->query();
		$array = array();
		while ($row = $cur->fetch_row()) {
			$array[] = $row[$numinarray];
		}
		$cur->free();
		return $array;
	}

	/**
	* Fetch a result row as an associative array
	*
	* @access public
	* @return array
	*/
	public function loadAssoc()
	{
		$cur = $this->query();
		$ret = null;
		if ($array = $cur->fetch_assoc()) {
			$ret = $array;
		}
		$cur->free();
		return $ret;
	}

	/**
	* Load a assoc list of database rows
	*
	* @access public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	public function loadAssocList($key='')
	{
		$cur = $this->query();
		$array = array();
		while ($row = $cur->fetch_assoc()) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		$cur->free();
		return $array;
	}

	/**
	* This global function loads the first row of a query into an object
	*
	* @access public
	* @return object
	*/
	public function loadObject()
	{
		$cur = $this->query();
		$ret = null;
		if ($object = $cur->fetch_object()) {
			$ret = $object;
		}
		$cur->free();
		return $ret;
	}

	/**
	* Load a list of database objects
	*
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*
	* @access public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	public function loadObjectList($key='')
	{
		$cur = $this->query();
		$array = array();
		while ($row = $cur->fetch_object()) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		$cur->free();
		return $array;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @return The first row of the query.
	 */
	public function loadRow()
	{
		$cur = $this->query();
		$ret = null;
		if ($row = $cur->fetch_row()) {
			$ret = $row;
		}
		$cur->free();
		return $ret;
	}

	/**
	* Load a list of database rows (numeric column indexing)
	*
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*
	* @access public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	public function loadRowList($key=null)
	{
		$cur = $this->query();
		$array = array();
		while ($row = $cur->fetch_row()) {
			if ($key !== null) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		$cur->free();
		return $array;
	}

	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @access public
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	public function insertObject($table, &$object, $keyName = NULL)
	{
		$fmtsql = 'INSERT INTO '.$this->nameQuote($table).' (%s) VALUES (%s) ';
		$fields = array();
		$values = array();
		$vars = ($object INSTANCEOF JObject) ? $object->getProperties() : get_object_vars($object);
		foreach ($vars as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$fields[] = $this->nameQuote($k);
			$values[] = $this->isQuoted($k) ? $this->Quote($v) : (int) $v;
		}
		$this->setQuery(sprintf($fmtsql, implode(",", $fields) ,  implode(",", $values)));
		$this->query();
		$id = $this->insertid();
		if ($keyName && $id) {
			$object->$keyName = $id;
		}
		return true;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @param [type] $updateNulls
	 */
	public function updateObject($table, &$object, $keyName, $updateNulls=true)
	{
		$fmtsql = 'UPDATE '.$this->nameQuote($table).' SET %s WHERE %s';
		$tmp = array();
		$vars = ($object INSTANCEOF JObject) ? $object->getProperties() : get_object_vars($object);
		foreach ($vars as $k => $v) {
			if (is_array($v) or is_object($v) or $k[0] == '_') { // internal or NA field
				continue;
			}
			if ($k == $keyName) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote($v);
				continue;
			}
			if ($v === null)
			{
				if ($updateNulls) {
					$val = 'NULL';
				} else {
					continue;
				}
			} else {
				$val = $this->isQuoted($k) ? $this->Quote($v) : (int) $v;
			}
			$tmp[] = $this->nameQuote($k) . '=' . $val;
		}
		$this->setQuery(sprintf($fmtsql, implode(",", $tmp) , $where));
		return $this->query();
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	public function insertid()
	{
		return $this->_resource->insert_id;
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	public function getVersion()
	{
		return $this->_resource->server_info;
	}

	/**
	 * Assumes database collation in use by sampling one text field in one table
	 *
	 * @access public
	 * @return string Collation in use
	 */
	public function getCollation ()
	{
		if ($this->hasUTF()) {
			$this->setQuery('SHOW FULL COLUMNS FROM #__content');
			$array = $this->loadAssocList();
			return $array['4']['Collation'];
		} else {
			return "N/A (mySQL < 4.1.2)";
		}
	}

	/**
	 * Description
	 *
	 * @access public
	 * @return array A list of all the tables in the database
	 */
	public function getTableList()
	{
		$this->setQuery('SHOW TABLES');
		return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @return 	array A list the create SQL for the tables
	 */
	public function getTableCreate($tables)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval)
		{
			$this->setQuery('SHOW CREATE table ' . $this->getEscaped($tblval));
			$rows = $this->loadRowList();
			foreach ($rows as $row) {
				$result[$tblval] = $row[1];
			}
		}

		return $result;
	}

	/**
	 * Retrieves information about the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array An array of fields by table
	 */
	public function getTableFields($tables, $typeonly = true)
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval)
		{
			$this->setQuery('SHOW FIELDS FROM ' . $tblval);
			$fields = $this->loadObjectList();

			if ($typeonly)
			{
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type);
				}
			}
			else
			{
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = $field;
				}
			}
		}

		return $result;
	}
}
