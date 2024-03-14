<?php

class BWFAN_DB_Table_Automationmeta extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automationmeta';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"bwfan_automation_id",
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
		  `bwfan_automation_id` bigint(20) unsigned NOT NULL default '0',
		  `meta_key` varchar(255) NULL,
		  `meta_value` longtext,
		  PRIMARY KEY (`ID`),
		  KEY `bwfan_automation_id` (`bwfan_automation_id`),
		  KEY `meta_key` (`meta_key`($this->max_index_length))
		) $collate;";
	}
}
