<?php

class BWFAN_DB_Table_Tasks extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_tasks';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"c_date",
			"e_date",
			"automation_id",
			"integration_slug",
			"integration_action",
			"status",
			"claim_id",
			"attempts",
			"priority",
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
 		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00',
 		  `e_date` bigint(12) NOT NULL,
 		  `automation_id` int(10) NOT NULL,
 		  `integration_slug` varchar(50) NULL,
 		  `integration_action` varchar(100) NULL,
 		  `status` int(1) NOT NULL default 0 COMMENT '0 - Pending 1 - Paused',
		  `claim_id` bigint(20) unsigned default 0,
		  `attempts` tinyint(1) unsigned default 0,
		  `priority` int(5) unsigned default 10,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `e_date` (`e_date`),
		  KEY `automation_id` (`automation_id`),
		  KEY `status` (`status`),
		  KEY `claim_id` (`claim_id`)
		) $collate;";
	}
}
