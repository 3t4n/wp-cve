<?php

class BWFAN_DB_Table_Automations extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automations';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"source",
			"event",
			"status",
			"priority",
			"start",
			"v",
			"benchmark",
			"title",
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
 		  `source` varchar(60) NOT NULL,
 		  `event` varchar(60) NOT NULL,
 		  `status` tinyint(1) NOT NULL default 0 COMMENT '1 - Active 2 - Inactive',
 		  `priority` tinyint(3) NOT NULL default 0,
 		  `start`  bigint(10) UNSIGNED NOT NULL,
 		  `v` tinyint(1) UNSIGNED NOT NULL default 1,
 		  `benchmark` longtext,
 		  `title` varchar(255) NULL,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `status` (`status`)
		) $collate;";
	}
}
