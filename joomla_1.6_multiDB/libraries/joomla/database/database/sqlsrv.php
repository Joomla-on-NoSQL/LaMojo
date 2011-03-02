<?php
/**
 * @version		$Id: sqlsrv.php 11316 2008-11-27 03:11:24Z ian $
 * @package		Joomla.Framework
 * @subpackage	Database
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * SQL Server database driver
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.0
 */
class JDatabaseSQLSrv extends JDatabase
{
	/**
	 * The database driver name
	 *
	 * @var string
	 */
	var $name			= 'sqlsrv';

	/**
	 *  The null/zero date string
	 *
	 * @var string
	 */
	var $_nullDate		= '1900-01-01 00:00:00';

	/**
	 * Quote for named objects
	 *
	 * @var string
	 */
	var $_nameQuote		= null;

	/**
	 * Database object constructor
	 *
	 * @access	public
	 * @param	array	List of options used to configure the connection
	 * @since	1.5
	 * @see		JDatabase
	 */
	function __construct( $options )
	{
		$host		= array_key_exists('host', $options)	? $options['host']		: 'localhost';
		$user		= array_key_exists('user', $options)	? $options['user']		: '';
		$password	= array_key_exists('password',$options)	? $options['password']	: '';
		$database	= array_key_exists('database',$options)	? $options['database']	: '';
		$prefix		= array_key_exists('prefix', $options)	? $options['prefix']	: 'jos_';
		$select		= array_key_exists('select', $options)	? $options['select']	: true;

		// perform a number of fatality checks, then return gracefully
		if (!function_exists( 'sqlsrv_connect' )) {
			$this->_errorNum = 1;
			$this->_errorMsg = 'The MS SQL adapter "sqlsrv" is not available.';
			return;
		}

		// connect to the server
		if (!($this->_connection = sqlsrv_connect( $host, Array("Database" => $database, 'uid'=>$user, 'pwd'=>$password, 'CharacterSet'=>'UTF-8','ReturnDatesAsStrings' => true) ) )) {
			$this->_errorNum = 2;
			$this->_errorMsg = 'Could not connect to SQL Server';
			return;
		}
		//ini_set("display_errors", 1);
		//error_reporting(E_ALL);
		sqlsrv_configure("WarningsReturnAsErrors", 0);
		//sqlsrv_configure('WarningsReturnAsErrors', 1);

		// finalize initialization
		parent::__construct($options);

		// select the database
		if ( $select ) {
			$this->select($database);
		}
	}

	/**
	 * Database object destructor
	 *
	 * @return boolean
	 * @since 1.5
	 */
	function __destruct()
	{
		$return = false;
		if (is_resource($this->_connection)) {
			$return = sqlsrv_close($this->_connection);
		}
		return $return;
	}

	/**
	 * Test to see if the SQL Server connector is available
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function test()
	{
		return (function_exists( 'sqlsrv_connect' ));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @access	public
	 * @return	boolean
	 * @since	1.5
	 */
	function connected()
	{
		/*if(is_resource($this->_connection)) {
			return mysql_ping($this->_connection);
			}
			return false;
			*/
		// TODO: Run a blank query here
		return true;
	}

	/**
	 * Select a database for use
	 *
	 * @access	public
	 * @param	string $database
	 * @return	boolean True if the database has been successfully selected
	 * @since	1.5
	 */
	function select($database)
	{
		if ( ! $database )
		{
			return false;
		}

		if(!sqlsrv_query( $this->_connection, 'USE '. $database, null, Array('scrollable' => SQLSRV_CURSOR_STATIC) ))
		{
			$this->_errorNum = 3;
			$this->_errorMsg = 'Could not connect to Master database '.$database;
			return false;
		}
		//$this->setQuery('USE '. $database);
		/*if ( !$this->Query() ) {
			$this->_errorNum = 3;
			$this->_errorMsg = 'Could not connect to database';
			return false;
			}*/

		return true;
	}

	/**
	 * Determines UTF support
	 *
	 * @access	public
	 * @return boolean True - UTF is supported
	 */
	function hasUTF()
	{
		return true;
	}

	/**
	 * Custom settings for UTF support
	 *
	 * @access	public
	 */
	function setUTF()
	{
		// TODO: Remove this?
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
	function getEscaped( $text, $extra = false )
	{
		// TODO: MSSQL Compatible escaping
		// The quoting for MSSQL isn't handled in the driver
		// however it should be (it'd be nice), so we need
		// to do this ourselves.
		// It should just be ' to '' but not sure
		// Hooduku: Not sure if this is the right implementation. But it works in all cases
		$result = addslashes($text);
		$result = str_replace("\'", "''", $result);
		$result = str_replace('\"', '"', $result);
		//$result = str_replace("\\", "''", $result);
		if ($extra) {
			$result = addcslashes($result, '%_');
		}
		return $result;
	}

	/**
	 * Execute the query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function query()
	{
		if (!is_resource($this->_connection)) {
			return false;
		}

		// Take a local copy so that we don't modify the original query and cause issues later
		$sql = $this->replacePrefix((string) $this->_sql);
		//$sql = str_replace('`', '', $sql);

		if ($this->_limit > 0 || $this->_offset > 0) {
			//$i = $this->_limit + $this->_offset;
			//$sql = preg_replace('/(^\SELECT (DISTINCT)?)/i','\\1 TOP '.$i.' ', $sql);
			$sql = $this->_limit($sql, $this->_limit, $this->_offset);
		}
		if ($this->_debug) {
			$this->_ticker++;
			$this->_log[] = $sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';

		//sqlsrv_num_rows requires a static or keyset cursor
		$array = array();
		jimport("joomla.utilities.string");
		if(JString::startsWith(ltrim(strtoupper($sql)), 'SELECT'))
		$array = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			
		$this->_cursor = sqlsrv_query( $this->_connection, $sql, array(), $array );
		
		if (!$this->_cursor)
		{
			//echo $sql;die();
			$errors = sqlsrv_errors( );
			$this->_errorNum = $errors[0]['SQLSTATE'];
			$this->_errorMsg = $errors[0]['message'].'SQL='.$sql.'<br>';
			// $errors[0]['errorcode']; // Holds the SQL Server Native Error Code

			if ($this->_debug) {
				JError::raiseError(500, 'JDatabaseSQLSrv::query: '.$this->_errorNum.' - '.$this->_errorMsg );
			}
			return false;
		}
		return $this->_cursor;
	}

	private function _limit($sql, $limit, $offset)
	{
		$order_by  = stristr($sql, 'ORDER BY');
		if(is_null($order_by) || empty($order_by))
		$order_by = 'ORDER BY (select 0)';
		$sql = str_ireplace($order_by, '', $sql);

		$row_number_text = ',ROW_NUMBER() OVER ('.$order_by.') AS RowNumber FROM ';

		$sql = preg_replace('/\\s+FROM/','\\1 '.$row_number_text.' ', $sql, 1);
		$sql = 'SELECT TOP '.$this->_limit.' * FROM ('.$sql.') _myResults WHERE RowNumber > '.$this->_offset;

		return $sql;
	}
	/**
	 * Get the current or query, or new JDatabaseQuery object.
	 *
	 * @param boolean False to return the last query set by setQuery, True to return a new JDatabaseQuery object.
	 * @return  string  The current value of the internal SQL variable
	 */
	function getQuery($new = false)
	{
		if ($new) {
			jimport('joomla.database.databasequerysqlsrv');
			return new JDatabaseQuerySQLSrv;
		} else {
			return $this->_sql;
		}
	}
	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of affected rows in the previous operation
	 * @since 1.0.5
	 */
	function getAffectedRows()
	{
		return sqlsrv_rows_affected($this->_cursor);
	}

	/**
	 * Execute a batch query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	function queryBatch( $abort_on_error=true, $p_transaction_safe = false)
	{
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		$this->_sql = 'BEGIN TRANSACTION;' . $this->_sql . '; COMMIT TRANSACTION;';
		$query_split = $this->splitSql($this->_sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim( $command_line );
			if ($command_line != '') {
				$this->_cursor = sqlsrv_query( $this->_connection, $command_line, null, Array('scrollable' => SQLSRV_CURSOR_STATIC) );
				if ($this->_debug) {
					$this->_ticker++;
					$this->_log[] = $command_line;
				}
				if (!$this->_cursor) {
					$error = 1;
					$errors = sqlsrv_errors( );
					$this->_errorNum = $errors[0]['sqlstate'];
					$this->_errorMsg = $errors[0]['message'];
					// $errors[0]['errorcode']; // Holds the SQL Server Native Error Code
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;
	}

	/**
	 * Diagnostic function
	 *
	 * @access	public
	 * @return	string
	 */
	function explain()
	{
		$temp = $this->_sql;

		// SET SHOWPLAN_ALL ON - will make sqlsrv to show some explain of query instead of run it
		// see also: http://msdn.microsoft.com/en-us/library/aa259203%28SQL.80%29.aspx
		$this->setQuery("SET SHOWPLAN_ALL ON");
		$this->query();

		$this->setQuery($temp);
		if (!($cur = $this->query())) {
			return null;
		}
		$first = true;

		$buffer = '<table id="explain-sql">';
		$buffer .= '<thead><tr><td colspan="99">'.$this->getQuery().'</td></tr>';
		while ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_ASSOC )) {
			if ($first) {
				$buffer .= '<tr>';
				foreach ($row as $k=>$v) {
					$buffer .= '<th>'.$k.'</th>';
				}
				$buffer .= '</tr></thead>';
				$first = false;
			}
			$buffer .= '<tbody><tr>';
			foreach ($row as $k=>$v) {
				$buffer .= '<td>'.$v.'</td>';
			}
			$buffer .= '</tr>';
		}
		$buffer .= '</tbody></table>';
		sqlsrv_free_stmt( $cur );

		// remove the explain status
		$this->setQuery("SET SHOWPLAN_ALL OFF");
		$this->query();

		$this->setQuery($temp);

		return $buffer;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of rows returned from the most recent query.
	 */
	function getNumRows( $cur=null )
	{
		return sqlsrv_num_rows( $cur ? $cur : $this->_cursor );
	}

	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access	public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_NUMERIC )) {
			$ret = $row[0];
		}
		$ret = stripslashes($ret);
		sqlsrv_free_stmt( $cur );
		return $ret;
	}

	/**
	 * Load an array of single field results into an array
	 *
	 * @access	public
	 */
	function loadResultArray($numinarray = 0)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_NUMERIC )) {
			$array[] = $row[$numinarray];
		}
		sqlsrv_free_stmt( $cur );
		return $array;
	}

	/**
	 * Fetch a result row as an associative array
	 *
	 * @access	public
	 * @return array
	 */
	function loadAssoc()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($array = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_ASSOC )) {
			$ret = $array;
		}
		sqlsrv_free_stmt( $cur );
		return $ret;
	}

	/**
	 * Load a assoc list of database rows
	 *
	 * @access	public
	 * @param string The field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 */
	function loadAssocList( $key='', $column = null )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_ASSOC )) {
			$value = ($column) ? (isset($row[$column]) ? $row[$column] : $row) : $row;
			if ($key) {
				$array[$row[$key]] = $value;
			} else {
				$array[] = $value;
			}
		}
		sqlsrv_free_stmt( $cur );
		return $array;
	}

	/**
	 * This global function loads the first row of a query into an object
	 *
	 * @access	public
	 * @return 	object
	 */
	function loadObject($className = 'stdClass')
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = sqlsrv_fetch_object( $cur )) {
			$ret = $object;
		}
		sqlsrv_free_stmt( $cur );
		return $ret;
	}

	/**
	 * Load a list of database objects
	 *
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 *
	 * @access	public
	 * @param string The field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 */
	function loadObjectList( $key='', $className = 'stdClass' )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sqlsrv_fetch_object( $cur )) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		sqlsrv_free_stmt( $cur );
		return $array;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return The first row of the query.
	 */
	function loadRow()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_NUMERIC )) {
			$ret = $row;
		}
		sqlsrv_free_stmt( $cur );
		return $ret;
	}

	/**
	 * Load a list of database rows (numeric column indexing)
	 *
	 * @access public
	 * @param string The field name of a primary key
	 * @return array If <var>key</var> is empty as sequential list of returned records.
	 * If <var>key</var> is not empty then the returned array is indexed by the value
	 * the database key.  Returns <var>null</var> if the query fails.
	 */
	function loadRowList( $key=null )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_NUMERIC )) {
			if ($key !== null) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		sqlsrv_free_stmt( $cur );
		return $array;
	}

	/**
	 * Load the next row returned by the query.
	 *
	 * @return      mixed   The result of the query as an array, false if there are no more rows, or null on an error.
	 *
	 * @since       1.6.0
	 */
	public function loadNextRow()
	{
		static $cur;

		if (!($cur = $this->query())) {
			return $this->_errorNum ? null : false;
		}

		if ($row = sqlsrv_fetch_array( $cur, SQLSRV_FETCH_NUMERIC )) {
			return $row;
		}

		sqlsrv_free_stmt($cur);
		$cur = null;

		return false;
	}

	/**
	 * Load the next row returned by the query.
	 *
	 * @return      mixed   The result of the query as an object, false if there are no more rows, or null on an error.
	 *
	 * @since       1.6.0
	 */
	public function loadNextObject($className = 'stdClass')
	{
		static $cur;

		if (!($cur = $this->query())) {
			return $this->_errorNum ? null : false;
		}

		if ($row =  sqlsrv_fetch_object( $cur )) {
			return $row;
		}

		sqlsrv_free_stmt($cur);
		$cur = null;

		return false;
	}

	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @access	public
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	function insertObject( $table, &$object, $keyName = NULL )
	{
		$fmtsql = 'INSERT INTO '.$this->nameQuote($table).' ( %s ) VALUES ( %s ) ';
		$fields = array();
		foreach (get_object_vars( $object ) as $k => $v) {
			if (is_array($v) or is_object($v)) {
				continue;
			}
			if(!$this->_checkFieldExists($table, $k))
			continue;
			if ($k[0] == '_') { // internal field
				continue;
			}
			if($k == $keyName && $keyName == 0)
			continue;
			$fields[] = $this->nameQuote( $k );
			$values[] = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
		if (!$this->query()) {
			return false;
		}
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
	function updateObject( $table, &$object, $keyName, $updateNulls=true )
	{
		$fmtsql = 'UPDATE '.$this->nameQuote($table).' SET %s WHERE %s';
		$tmp = array();
		foreach (get_object_vars( $object ) as $k => $v)
		{
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if( $k == $keyName ) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote( $v );
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
				$val = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
			}
			$tmp[] = $this->nameQuote( $k ) . '=' . $val;
		}

		// Nothing to update.
		if (empty($tmp)) {
			return true;
		}

		$this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
		return $this->query();
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	function insertid()
	{
		// TODO: SELECT IDENTITY
		$this->setQuery('SELECT @@IDENTITY');
		return $this->loadResult();
	}

	/**
	 * Description
	 *
	 * @access public
	 */
	function getVersion()
	{
		//return sqlsrv_server_info( $this->_connection );
		return '5.1.0';
	}

	/**
	 * Assumes database collation in use by sampling one text field in one table
	 *
	 * @access	public
	 * @return string Collation in use
	 */
	function getCollation ()
	{
		// TODO: Not fake this
		return 'MSSQL UTF-8 (UCS2)';
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return array A list of all the tables in the database
	 */
	function getTableList()
	{
		$this->setQuery( 'SELECT name from sysobjects WHERE xtype = \'U\';' );
		return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @return 	array A list the create SQL for the tables
	 */
	function getTableCreate( $tables )
	{
		// MSSQL doesn't support that
		return '';
	}

	/**
	 * Retrieves information about the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array An array of fields by table
	 */
	function getTableFields( $tables, $typeonly = true )
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			//$this->setQuery('SHOW FIELDS FROM ' . $tblval);

			$tblval_temp = $this->replacePrefix((string) $tblval);

			$this->setQuery('select column_name as Field, data_type as Type, is_nullable as \'Null\', column_default as \'Default\' from information_schema.columns where table_name= ' . $this->Quote($tblval_temp));
			$fields = $this->loadObjectList();

			if ($typeonly) {
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type);
				}
			} else {
				foreach ($fields as $field) {
					$result[$tblval][$field->Field] = $field;
				}
			}
		}

		return $result;
	}

	private function _checkFieldExists($table_name, $field)
	{
		$table_name = $this->replacePrefix((string) $table_name);
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS".
 				" WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$field'".
 				" ORDER BY ORDINAL_POSITION";
		$this->setQuery($sql);
		if($this->loadResult())
		return true;
		else
		return false;
	}
	
	/**
	 * Gets the date as an SQL Server datetime string.
	 *
	 *
	 * @param	boolean	True to return the date string in the local time zone, false to return it in GMT.
	 * @return	string	The date string in SQL Server datetime format.
	 * @since	1.6
	 */
	public function toSQLDate(&$date, $local = false)
	{
		return $date->toSQLSrv($local);
	}
}
