<?php

class BWFAN_DB_Table_Logmeta extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_logmeta';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"bwfan_log_id",
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
		  `bwfan_log_id` bigint(20) unsigned NOT NULL default '0',
		  `meta_key` varchar(255) default NULL,
		  `meta_value` longtext,
		  PRIMARY KEY (`ID`),
		  KEY `bwfan_log_id` (`bwfan_log_id`),
		  KEY `meta_key` (`meta_key`($this->max_index_length))
		) $collate;";
	}
}
