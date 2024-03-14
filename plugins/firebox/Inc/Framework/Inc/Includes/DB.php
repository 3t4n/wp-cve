<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\DBHelper;

abstract class DB
{
	/**
	 * The name of our database table
	 *
	 * @var  String
	 */
	public $table_name;

	/**
	 * The default primary key.
	 * 
	 * @var  String
	 */
	protected $primary_key = 'id';

	/**
	 * Whitelist of columns
	 *
	 * @return  array
	 */
	public function get_columns()
	{
		return [];
	}
	
	/**
	 * Retrieve a row by a specific column / value
	 *
	 * @param   array    $args    Arguments of the query
	 * @param   boolean  $cache   Whether to cache the results
	 * @param   boolean  $count   Whether to return the count of the results
	 * @param   boolean  $output  Specify how the data are returned. [OBJECT|OBJECT_K|ARRAY_A|ARRAY_N]
	 * 
	 * @return  mixed
	 */
	public function getResults($args = [], $cache = false, $count = false, $output = OBJECT)
	{
		if (empty($this->table_name))
		{
			return [];
		}

		if (!DBHelper::table_exists($this->table_name))
		{
			return [];
		}

		global $wpdb;

		$table_name = $this->getFullTableName();

		$defaults = array(
			'limit'	=> 9999999999
		);
		
		// set given args to defaults values
		$args = wp_parse_args($args, $defaults);

		// select
		$select = isset($args['select']) ? $args['select'] : '*';
		$select = $this->parseSelect($select);

		// table name as
		$from_table_name_as = isset($args['from_table_name_as']) ? ' ' . $args['from_table_name_as'] : '';

		// FROM 
		$from = isset($args['from']) ? 'FROM ' . $args['from'] : 'FROM ' . $table_name . $from_table_name_as;

		// GROUP BY format: [column]
		$groupby = isset($args['groupby']) ? 'GROUP BY ' . $args['groupby'] : '';
		
		// HAVING
		$having = isset($args['having']) ? 'HAVING ' . $args['having'] : '';
		
		// ORDRE BY format: [column] [ASC|DESC]
		$orderby = isset($args['orderby']) ? 'ORDER BY ' . $args['orderby'] : '';

		// get join statement
		$join = $this->getJoinStatement($args);

		// get where statement
		$where = $this->getWhereStatement($args);

		// check limit
		$limit = '';
		if (isset($args['limit']) && is_int($args['limit']))
		{
			$limit = ' LIMIT ' . (int) $args['limit'];
		}

		// check offset
		$offset = '';
		if (isset($args['offset']) && is_int($args['offset']))
		{
			$offset = 'OFFSET ' . (int) $args['offset'];
		}

		$results = false;

		// if we are searching the cache
		if ($cache == true)
		{
			// cache key
			$cache_key = (true === $count) ? md5('fpframework_count' . serialize($args)) : md5('fpframework_' . serialize($args));

			// get cache
			$results = wp_cache_get($cache_key, 'fpframework_cache_' . $table_name);
		}

		if ($results)
		{
			return $results;
		}

		// return count
		if ($count === true)
		{
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$results = absint($wpdb->get_var("SELECT COUNT(*) {$from} {$join} {$where};"));
		}
		else
		{
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$results = $wpdb->get_results("SELECT {$select} {$from} {$join} {$where} {$groupby} {$having} {$orderby} {$limit} {$offset};", $output);
		}

		// if we want to set cache
		if ($cache == true)
		{
			wp_cache_set($cache_key, $results, 'fpframework_cache_' . $table_name, 3600);
		}

		return $results;
	}

	/**
	 * Returns a comma separated SELECT statement if array
	 * otherwise returns the given select.
	 * 
	 * @param   mixed  $select
	 * 
	 * @return  string
	 */
	private function parseSelect($select)
	{
		if (!$select)
		{
			return $select;
		}
		
		if (is_string($select))
		{
			return $select;
		}

		if (is_array($select))
		{
			$select = implode(',', $select);
		}

		return $select;
	}

	/**
	 * Returns the where statement
	 * 
	 * @param   array  $args
	 * 
	 * @return  string
	 */
	private function getWhereStatement($args)
	{
		if (!isset($args['where']))
		{
			return '';
		}

		$where = '';

		$i = 1;
		foreach ($args['where'] as $key => $value)
		{
			$suffix = '';
			
			if ($i != count($args['where']))
			{
				$suffix = ' AND ';
			}
			
			$where .= $key . $value . $suffix;

			$i++;
		}

		if (empty(trim($where)))
		{
			return '';
		}
		
		$prefix = 'WHERE ';

		return $prefix . $where;
	}

	/**
	 * Returns the join statement
	 * 
	 * @param   array  $args
	 * 
	 * @return  string
	 */
	private function getJoinStatement($args)
	{
		if (!isset($args['join']))
		{
			return '';
		}

		$join = '';
		
		foreach ($args['join'] as $key => $value)
		{
			$join .= $key . $value . ' ';
		}

		return $join;
	}

	/**
	 * Insert a new row
	 * 
	 * @param   array  $data
	 *
	 * @return  int
	 */
	public function insert($data)
	{
		if (empty($this->table_name))
		{
			return [];
		}

		global $wpdb;

		$data = (array) $data;
		
		$wpdb->insert($wpdb->prefix . $this->table_name, $data);
		$wpdb_insert_id = $wpdb->insert_id;

		return $wpdb_insert_id;
	}

	/**
	 * Update a row
	 *
	 * @param   array   $data
	 * @param   string  $where
	 * 
	 * @return  bool
	 */
	public function update($data = [], $where = '')
	{
		if (empty($this->table_name))
		{
			return false;
		}
		
		if (empty($where))
		{
			return false;
		}

		global $wpdb;

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case($data);

		// White list columns
		$data = array_intersect_key($data, $column_formats);

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys = array_keys($data);
		$column_formats = array_merge(array_flip($data_keys), $column_formats);

		$table = $wpdb->prefix . $this->table_name;

		if (false === $wpdb->update($wpdb->prefix . $this->table_name, $data, $where, $column_formats))
		{
			return false;
		}

		return true;
	}

	/**
	 * Run a given SQL statement and data to pass to the query
	 * 
	 * @param   string   $sql
	 * @param   array    $data
	 * 
	 * @return  boolean
	 */
	public function executeRaw($sql, $data = [])
	{
		if (!$sql)
		{
			return false;
		}
		
		global $wpdb;

		$query = null;

		if ($data)
		{
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$query = $wpdb->query($wpdb->prepare($sql, $data));
		}
		else
		{
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$query = $wpdb->query($sql);
		}
		
		if (false === $query)
		{
			return false;
		}

		return true;
	}

	/**
	 * Delete a row identified by the primary key
	 *
	 * @param   array    $args
	 * 
	 * @return  boolean
	 */
	public function delete($args)
	{
		if (empty($this->table_name))
		{
			return false;
		}
		
		global $wpdb;

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case($args);

		// White list columns
		$data = array_intersect_key($data, $column_formats);

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys = array_keys($data);
		$column_formats = array_merge(array_flip($data_keys), $column_formats);

		$table = $wpdb->prefix . $this->table_name;

		if (false === $wpdb->delete($table, $args, $column_formats))
		{
			return false;
		}

		return true;
	}

	/**
	 * Delete Raw Query
	 * 
	 * @param   string  $where
	 * @param   array   $data
	 * 
	 * @return  bool
	 */
	public function deleteRaw($where, $data = [])
	{
		$table = $this->getFullTableName();

		$sql = "DELETE FROM `$table` $where";
		
		return $this->executeRaw($sql, $data);
	}

	/**
	 * Returns the full table name, containing the table prefix
	 * 
	 * @return  string
	 */
	public function getFullTableName()
	{
		global $wpdb;
		
		$table = $wpdb->prefix . $this->table_name;
		
		return $table;
	}
}