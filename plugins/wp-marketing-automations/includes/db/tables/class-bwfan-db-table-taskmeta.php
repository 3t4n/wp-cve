<?php

class BWFAN_DB_Table_Taskmeta extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_taskmeta';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"bwfan_task_id",
			"meta_key",
			"meta_value",
		];
	}

	/**
	 * Get query for create table
	 *
	 * @return string
	 */
	public function get_create_table_query() {
		global $wpdb;
		$collate = $this->get_collation();

		return "CREATE TABLE {$wpdb->prefix}$this->table_name (
 		  `ID` bigint(20) unsigned NOT NULL auto_increment,
		  `bwfan_task_id` bigint(20) unsigned NOT NULL default '0',
		  `meta_key` varchar(255) NULL,
		  `meta_value` longtext,
		  PRIMARY KEY (`ID`),
		  KEY `bwfan_task_id` (`bwfan_task_id`),
		  KEY `meta_key` (`meta_key`($this->max_index_length))
		) $collate;";
	}
}
