<?php

namespace WPPayForm\Framework\Database;

class Schema
{
	/**
	 * Get the global $wpdb instance
	 * 
	 * @return global $wpdb instance
	 */
	public static function db()
	{
		return $GLOBALS['wpdb'];
	}

	/**
	 * Get schema/db information
	 *
	 * @return string|array
	 */
	public static function getInfo($key = null)
	{
		$db = static::db();

		$info = [
			'dbname' => $db->dbname,
			'prefix' => $db->prefix,
			'dbhost' => $db->dbhost,
		    'username' => $db->dbuser,
		    'password' => $db->dbpassword,
		    'charset' => $db->charset,
		    'collation' => $db->collate,
		    'tables' => static::getTableList()
		];

		return $key ? $info[$key] : $info;
	}

	/**
	 * Migrates database table(s)
	 * 
	 * @param  string|array $table The table name without prefix
	 *  or an array where each key is table name and value is sql.
	 *  
	 * @param  string $sql Optional
	 * @return mixed
	 */
	public static function migrate($table, $sql = null)
	{
		if (!$sql && is_array($table)) {
			$result = [];
			foreach ($table as $t => $sql) {
				$result = array_merge(
					$result, (array) static::createTable($t, $sql)
				);
			}
			return $result;
		} else {
			return static::createTable($table, $sql);
		}
	}

	/**
	 * Creates a new table if doesn't exist using dbDelta function
	 * 
	 * @param  string $table The table name without the prefix
	 * @param  string $sql   The sql to create table or an absolute path of a
	 * .sql file containing the column definations for creating the new table.
	 * 
	 * @return string Message
	 */
	public static function createTableIfNotExist($table, $sql)
	{
		if (!static::hasTable($table)) {
			return static::createTable($table, $sql);
		}
	}

	/**
	 * Checks if a table exists
	 * 
	 * @param  string $table The table name without prefix
	 * @return boolean
	 */
	public static function hasTable($table)
	{
		$wpdb = static::db();

		$table = static::table($table);

		$result = $wpdb->get_var("SHOW TABLES LIKE '" . $table . "'") == $table;

		if ($result) {
			return $result;
		}

		// Check if any temporary table exists by this table name.

		// At first, store the original state of the error suppress
		// error and then turn off the error from being shown, so
		// error will be not shown if there is no temporary
		// table, then restore the error state.
		$isErrorSuppressed = $wpdb->suppress_errors;
		
		$wpdb->suppress_errors = true;

		$result = static::query("SELECT 1 FROM %{$table}% WHERE 0");

		$wpdb->suppress_errors = $isErrorSuppressed;

		return $result === 0;
	}

	/**
	 * Resolves the table prefix and makes the table name with prefix
	 * 
	 * @param  string $table The table name without the prefix
	 * @return string The resolved table name with the prefix
	 */
	public static function table($table)
	{
		$wpdb = static::db();
		
		$prefix = $wpdb->prefix;

		if (strpos($table, $prefix) === 0) {
			return $table;
		}

		return isset($wpdb->{$table}) ? $wpdb->{$table} : ($wpdb->prefix.$table);
	}

	/**
	 * Creates a new table using dbDelta function or alters the table if exists.
	 * 
	 * @param  string $table The table name without the prefix
	 * @param  string $sql   The sql to create table or an absolute path of a
	 * .sql file containing the column definations for creating the new table.
	 * 
	 * @return string message
	 */
	public static function createTable($table, $sql)
	{
		$table = static::table($table);

		$sql = @file_exists($sql) ? file_get_contents($sql) : $sql;

        $collate = static::db()->get_charset_collate();

        if ($sql && !str_contains(basename($sql), '.')) {
	        return static::callDBDelta(
	        	"CREATE TABLE $table (".PHP_EOL.trim(trim($sql), ',').PHP_EOL.") $collate;"
	        );
        }
	}

	/**
	 * Alters an existing table if exists
	 * 
	 * @param  string $table The table name without the prefix
	 * @param    string $sql   The sql to create table or an absolute path of a
	 * .sql file containing the column definations for creating the new table.
	 * 
	 * @return string message
	 */
	public static function alterTableIfExists($table, $sql)
	{
		if (static::hasTable($table)) {
			return static::alterTable($table, $sql);
		}
	}

	/**
	 * Alters an existing table
	 * 
	 * @param  string $table The table name without the prefix
	 * @param  string $sql   The sql to create table or an absolute path of a
	 * .sql file containing the column definations for creating the new table.
	 * 
	 * @return string message
	 */
	public static function alterTable($table, $sql)
	{
		$table = static::table($table);

		$sql = @file_exists($sql) ? file_get_contents($sql) : $sql;

		$sql = array_map(function($i) { return trim($i);}, explode(',', $sql));
        
        $sql = "ALTER TABLE $table ".PHP_EOL.rtrim(trim(implode(','.PHP_EOL, $sql)), ';').";";

        return static::query($sql);
	}

	/**
	 * Alters an existing table using dbDelta function if exists or creates it.
	 * 
	 * Alters an existing table but takes the create table's column defination.
	 * This is because, the dbDelta can create or update a table using the table
	 * crteate definations. In this, case, if a table exists and columns are matched
	 * then nothing happens, if there are difference in new sql then dbDelta alters
	 * the table using the new defination, if table is not there then it's get created.
	 * 
	 * @param  string $table The table name without the prefix
	 * @param    string $sql   The sql to create table or an absolute path of a
	 * .sql file containing the column definations for creating the new table.
	 * 
	 * @return string message
	 */
	public static function updateTable($table, $sql)
	{
		return static::createTable($table, $sql);
	}

	/**
	 * Drops/deletes an existing table if exists
	 * 
	 * @param  string $table The table name without the prefix
	 * @return bool
	 */
	public static function dropTableIfExists($table)
	{
		if (static::hasTable($table)) {
			return static::db()->query('DROP TABLE ' . static::table($table));
		}
	}

	/**
	 * Truncate a table.
	 * 
	 * @param  string $table
	 * @return bool
	 */
	public static function truncate($table)
	{
		$table = static::table($table);

		return static::db()->query("TRUNCATE TABLE $table");
	}

	/**
	 * Truncate a table if exists.
	 * 
	 * @param  string $table
	 * @return bool
	 */
	public static function truncateTableIfExists($table)
	{
		if (static::hasTable($table)) {
			return static::truncate($table);
		}
	}

	/**
	 * Makes raw query and can resolve the table name from the query
	 * and can form a full table name including the table prefix if
	 * the table name is wrapped like: %table_name% in the query.
	 * 
	 * @param  straing $query
	 * @return mixed
	 */
	public static function query($query)
	{
		if (preg_match('/%.*%/', $query, $m)) {
			$query = str_replace(
				$m[0], static::table(trim($m[0], '%')), $query
			);
		}

		return static::db()->query($query);
	}

	/**
	 * Get a list of all columns from the given table name.
	 * 
	 * @param  string $table The table name without the prefix
	 * @return array
	 */
	public static function getColumns($table)
	{
		if (static::hasTable($table)) {
			return static::db()->get_col(
	            'DESC ' . static::table($table), 0
	        );
		}
	}

	/**
	 * Gets a list of all columns including column information
	 * 
	 * @param  string $table The table name without the prefix
	 * @return array
	 */
	public static function getColumnsWithTypes($table)
	{
		if (!static::hasTable($table)) return;
		
		$db = static::db()->dbname;
		$table = static::table($table);
		$fields = [
			'COLUMN_NAME',
			'ORDINAL_POSITION',
			'COLUMN_DEFAULT',
			'IS_NULLABLE',
			'DATA_TYPE',
			'CHARACTER_MAXIMUM_LENGTH',
			'NUMERIC_PRECISION',
			'NUMERIC_SCALE',
			'COLUMN_KEY',
			'EXTRA',
		];
		
		$sql = "SELECT " . implode(',', $fields) . " FROM INFORMATION_SCHEMA.COLUMNS";
		$sql .= " WHERE TABLE_NAME = '".$table."' AND TABLE_SCHEMA = '".$db."'";

		return array_map(function($i) {
			$item = [];
			foreach ((array) $i as $key => $value) {
				$item[strtolower($key)] = $value;
			}
			return $item;
		}, static::db()->get_results($sql));
	}

	/**
	 * Retrieves the list of all available tables in the database.
	 * 
	 * @param  string $dbname optional
	 * @return array
	 */
	public static function getTableList($dbname = null)
	{
		$dbname = $dbname ?: static::db()->dbname;
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES";
		$sql .= " WHERE TABLE_SCHEMA = '".$dbname."'";
		return array_map(function($i) {
			return $i->TABLE_NAME;
		}, static::db()->get_results($sql));
	}

	/**
	 * The wrapper for calling dbDelta function
	 * 
	 * @param  string $sql
	 * @return mixed
	 */
	protected static function callDBDelta($sql)
	{
		if (!function_exists('dbDelta')) {
			require (ABSPATH . 'wp-admin/includes/upgrade.php');
		}

		return dbDelta($sql);
	}
}
