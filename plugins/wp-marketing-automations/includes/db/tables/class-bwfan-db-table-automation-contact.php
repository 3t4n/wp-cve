<?php

class BWFAN_DB_Table_Automation_Contact extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automation_contact';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"cid",
			"aid",
			"event",
			"c_date",
			"e_time",
			"status",
			"last",
			"last_time",
			"data",
			"claim_id",
			"attempts",
			"trail",
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
		  `cid` bigint(20) unsigned NOT NULL,
		  `aid` bigint(10) unsigned NOT NULL,
 		  `event` varchar(120) NOT NULL,
		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Start Date',
		  `e_time` bigint(12) unsigned NOT NULL,
		  `status` tinyint(1) UNSIGNED NOT NULL default 1 COMMENT '1 - Active | 2 - Failed | 3 - Paused | 4 - Waiting | 5 - Terminate | 6 - Retry',
		  `last` bigint(10) UNSIGNED NOT NULL default 0,
		  `last_time` bigint(12) UNSIGNED NOT NULL,
		  `data` longtext,
		  `claim_id` bigint(20) UNSIGNED NOT NULL default 0,
		  `attempts` tinyint(1) UNSIGNED NOT NULL default 0,
		  `trail` varchar(40) NULL COMMENT 'Trail ID',
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `cid` (`cid`),
		  KEY `aid` (`aid`),
		  KEY `e_time` (`e_time`),
		  KEY `status` (`status`),
		  KEY `claim_id` (`claim_id`)
		) $collate;";
	}
}
