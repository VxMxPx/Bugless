<?php if (!defined('AVRELIA')) { die('Access is denied!'); }

/**
 * Avrelia
 * ----
 * Database Plug
 * ----
 * @package    Avrelia
 * @author     Avrelia.com
 * @copyright  Copyright (c) 2010, Avrelia.com
 * @license    http://framework.avrelia.com/license
 * @link       http://framework.avrelia.com
 * @since      Version 0.80
 * @since      2012-03-21
 */
class cDatabase
{
	/**
	 * @var	cDatabaseDriverInterface	PDO Database Driver Instance
	 */
	private static $Driver;


	/**
	 * Init the Database object
	 * --
	 * @return	boolean
	 */
	public static function _OnInit()
	{
		self::_LoadDriver();

		# Load all other required libraries
		if (!class_exists('cDatabaseQuery',     false)) { include(ds(dirname(__FILE__).'/database_query.php'));     }
		if (!class_exists('cDatabaseResult',    false)) { include(ds(dirname(__FILE__).'/database_result.php'));    }
		if (!class_exists('cDatabaseStatement', false)) { include(ds(dirname(__FILE__).'/database_statement.php')); }

		if (!self::$Driver->connect()) {
			Log::Add('ERR', "Can't connect to or create database.", __LINE__, __FILE__);
			return false;
		}

		return true;
	}
	//-

	/**
	 * Enable database plug
	 * --
	 * @return	boolean
	 */
	public static function _OnEnable()
	{
		self::_LoadDriver();
		return self::$Driver->_create();
	}
	//-

	/**
	 * Remove the database
	 * --
	 * @return	boolean
	 */
	public static function _OnDisable()
	{
		self::_LoadDriver();
		return self::$Driver->_destroy();
	}
	//-

	/**
	 * Will load driver and config
	 * --
	 * @return	void
	 */
	private static function _LoadDriver()
	{
		Plug::GetConfig(__FILE__);
		self::$Driver = Plug::GetDriver(__FILE__, Cfg::Get('plugs/database/driver'));
	}
	//-

	/**
	 * Return PDO Driver object.
	 * ---
	 * @return	cDatabaseDriverInterface
	 */
	public static function _getDriver()
	{
		return self::$Driver;
	}
	//-

	/**
	 * Execute raw SQL statement
	 * --
	 * @param	string	$statement
	 * @param	array	$bind
	 * --
	 * @return	cDatabaseResult
	 */
	public static function Execute($statement, $bind=false)
	{
		$Statement = new cDatabaseStatement($statement);

		if ($bind) {
			$Statement->bind($bind);
		}

		return $Statement->execute();
	}
	//-

	/**
	 * Create new record.
	 * --
	 * @param	array	$Values
	 * @param	string	$table
	 * --
	 * @return	cDatabaseResult
	 */
	public static function Create($Values, $table)
	{
		# Create statement
		$sql = "INSERT INTO {$table} (" . vArray::ImplodeKeys(', ', $Values) . ')' .
				' VALUES (';

		foreach ($Values as $k => $v) {
			$sql .= ":{$k}, ";
		}

		$sql = substr($sql, 0, -2);
		$sql .= ')';

		$Statement = new cDatabaseStatement($sql);
		$Statement->bind($Values);

		return $Statement->execute();
	}
	//-

	/**
	 * Will read (select) items from database.
	 * --
	 * @param	string	$table
	 * @param	mixed	$condition	['id' => 12] || 'id=:id AND name=:name' and bind it later.
	 * @param	array	$bind
	 * @param	mixed	$limit		Select 12 records || range: [10, 25]
	 * @param	array	$order		['name' => 'DESC'] || ['name' => 'DESC', 'date' => 'ASC'] || ['name', 'id' => 'DESC']
	 * --
	 * @return	cDatabaseResult
	 */
	public static function Find($table, $condition=false, $bind=false, $limit=false, $order=false)
	{
		# Initial statement
		$sql = "SELECT * FROM {$table}";

		# Parse condition, if is an array
		if (is_array($condition)) {
			$bind = self::ParseCondition($condition);
		}

		# Append condition
		if ($condition) {
			$sql .= ' ' . $condition;
		}

		# Do we have limit?
		if ($limit) {
			if (!is_array($limit)) {
				$limit = array(0, $limit);
			}

			$limit = implode(', ', $limit);
			$sql .= ' LIMIT ' . $limit;
		}

		# Do we have order?
		if ($order) {
			$orderStatement = '';
			foreach ($order as $field => $type) {
				if (is_integer($field)) {
					# name, id DESC
					$orderStatement .= $type . ', ';
				}
				else {
					# id DESC, name ASC, ...
					$orderStatement .= "{$field} {$type}, ";
				}
			}
			$sql .= ' ORDER BY ' . substr($orderStatement, 0, -2);
		}

		# Make it happened
		$Statement = new cDatabaseStatement($sql);

		# Do we have anything to bind?
		if ($bind) {
			$Statement->bind($bind);
		}

		# Execute, and return results
		return $Statement->execute();
	}
	//-

	/**
	 * Update particular record.
	 * --
	 * @param	array	$Values
	 * @param	string	$table
	 * @param	mixed	$condition	['id' => 12] || 'id=:id AND name=:name' and bind it.
	 * @param	array	$bind
	 * --
	 * @return	cDatabaseResult
	 */
	public static function Update($Values, $table, $condition, $bind=false)
	{
		$sql = "UPDATE {$table} SET ";

		foreach ($Values as $key => $val) {
			$sql .= "{$key}=:{$key}, ";
		}

		$sql = substr($sql, 0, -2);

		if (is_array($condition)) {
			$bind = self::ParseCondition($condition);
		}

		$sql .= ' '.$condition;

		$Statement = new cDatabaseStatement($sql);
		$Statement->bind($Values);
		if ($bind) {
			$Statement->bind($bind);
		}

		return $Statement->execute();
	}
	//-

	/**
	 * Will delete particular item.
	 * --
	 * @param	string	$table
	 * @param	mixed	$condition	['id' => 12] || 'id=:id AND name=:name' and bind it later.
	 * @param	array	$bind
	 * --
	 * @return	cDatabaseResult
	 */
	public static function Delete($table, $condition, $bind=false)
	{
		$sql  = "DELETE FROM {$table}";

		if (is_array($condition)) {
			$bind = self::ParseCondition($condition);
		}

		$sql .= ' ' . $condition;

		$Statement = new cDatabaseStatement($sql);

		if ($bind) {
			$Statement->bind($bind);
		}

		return $Statement->execute();
	}
	//-

	/**
	 * Parse an array condition (like) ['id' => 12] into WHERE id=:id
	 * --
	 * @param	array	$condition
	 * --
	 * @return	string
	 */
	private static function ParseCondition(&$condition)
	{
		if (is_array($condition)) {
			$bind         = array();
			$newCondition = 'WHERE ';

			foreach ($condition as $k => $v) {
				$divider = strpos(str_replace(array('AND ', 'OR '), '', $k), ' ') !== false ? ' ' : ' = ';
				$kclean  = vString::Clean($k, 100, 'aA1c', '_');
				$newCondition .= "{$k}{$divider}:{$kclean} AND ";
				$bind[$kclean] = $v;
			}

			$condition = substr($newCondition, 0, -5);
			return $bind;
		}
		else {
			Log::Add('WAR', "Condition must be an array: {$condition}.", __LINE__, __FILE__);
			return $condition;
		}
	}
	//-
}
//--
