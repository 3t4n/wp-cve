<?php
/**
 * @author Tomáš Blatný
 */

namespace Pays\PaymentGate;

use DateTime;
use wpdb;


class Database
{

	/** @var wpdb */
	private $wpdb;


	public function __construct(wpdb $wpdb)
	{
		$this->wpdb = $wpdb;
	}


	public function getPrefix()
	{
		return $this->wpdb->prefix;
	}


	/**
	 * @param string $from
	 * @param string|NULL $query
	 * @param array $args
	 * @return array
	 */
	public function select($from, $query = NULL, array $args = array())
	{
		return $this->query('SELECT * FROM `' . $this->getTableName($from) . '` ' . $query, $args);
	}


	public function selectOne($column, $table, $other = NULL, $args = array())
	{
		$query = 'SELECT ' . $column . ' FROM `' . $this->getTableName($table) . '`' . ($other ? (' ' . $other) : '') . ' LIMIT 1';
		if (strpos($query, '%')) {
			$result = $this->wpdb->get_col($this->wpdb->prepare($query, $args));
		} else {
			$result = $this->wpdb->get_col($query);
		}
		return isset($result[0]) ? $result[0] : NULL;
	}


	public function insert($table, $values, $other = NULL)
	{
		$this->runQuery('INSERT INTO `' . $this->getTableName($table) . '` (`' . implode ('`, `', array_keys($values)) . '`) VALUES (' . $this->getInsertValues($values) . ')' . ($other ? (' ' . $other) : ''), array_values($values));
	}


	public function update($table, $values, $where, $whereArgs)
	{
		$this->runQuery('UPDATE `' . $this->getTableName($table) . '` SET ' . $this->getUpdateValues($values) . ' WHERE ' . $where, array_merge($values, $whereArgs));
	}


	public function delete($table, $where, $whereArgs)
	{
		$this->runQuery('DELETE FROM `' . $this->getTableName($table) . '` WHERE ' . $where, $whereArgs);
	}


	/**
	 * @return int
	 */
	public function getLastInsertId()
	{
		return $this->wpdb->insert_id;
	}


	/**
	 * @param string $table
	 * @param array $fields
	 */
	public function createTable($table, array $fields)
	{
		$tableDefinition = array();
		foreach ($fields as $name => $field) {
			$tableDefinition[] = '`' . $name . '` ' . $field;
		}
		$this->runQuery('CREATE TABLE IF NOT EXISTS ' . $this->getTableName($table) . ' (' . implode(",\n", $tableDefinition) . ') COLLATE utf8mb4_unicode_ci');
	}


	/**
	 * @param string $table
	 */
	public function dropTable($table)
	{
		$this->runQuery('DROP TABLE `' . $this->getTableName($table) . '`');
	}


	/**
	 * @param string $query
	 * @param array $args
	 * @return array
	 */
	private function query($query, array $args = array())
	{
		return strpos($query, '%') ? $this->wpdb->get_results($this->wpdb->prepare($query, $args)) : $this->wpdb->get_results($query);
	}


	/**
	 * @param string $query
	 * @param array $args
	 * @return int|bool
	 */
	private function runQuery($query, array $args = array())
	{
		return strpos($query, '%') ? $this->wpdb->query($this->wpdb->prepare($query, $args)) : $this->wpdb->query($query);
	}


	/**
	 * @param string $table
	 * @return string
	 */
	private function getTableName($table)
	{
		if (strpos($table, $this->wpdb->prefix) !== FALSE) {
			return $table;
		}
		return $this->wpdb->prefix . 'paymentgate_' . $table;
	}


	private function getInsertValues($values)
	{
		$result = array();
		foreach ($values as &$value) {
			if (is_int($value) || is_bool($value)) {
				$result[] = '%d';
			} else {
				if ($value instanceof DateTime) {
					$value = $value->format('Y-m-d');
				}
				$result[] = '%s';
			}
		}
		return implode(', ', $result);
	}


	private function getUpdateValues($values)
	{
		$result = array();
		foreach ($values as $key => &$value) {
			if (is_int($value) || is_bool($value)) {
				$result[] = '`' . $key . '` = %d';
			} else {
				if ($value instanceof DateTime) {
					$value = $value->format('Y-m-d');
				}
				$result[] = '`' . $key . '` = %s';
			}
		}
		return implode(', ', $result);
	}

}
