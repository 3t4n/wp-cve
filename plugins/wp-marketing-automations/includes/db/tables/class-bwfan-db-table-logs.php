<?php

class BWFAN_DB_Table_Logs extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_logs';

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
			"status",
			"integration_slug",
			"integration_action",
			"automation_id",
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
 		  `status` int(1) NOT NULL default 0 COMMENT '0 - Failed 1 - Success',
 		  `integration_slug` varchar(50) default NULL,
 		  `integration_action` varchar(100) default NULL,
 		  `automation_id` int(10) NOT NULL,
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `status` (`status`),
		  KEY `automation_id` (`automation_id`)
		) $collate;";
	}
}
