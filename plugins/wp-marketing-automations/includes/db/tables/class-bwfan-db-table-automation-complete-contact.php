<?php

class BWFAN_DB_Table_Automation_Complete_Contact extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automation_complete_contact';

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
			"s_date",
			"c_date",
			"data",
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
		  `s_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Start Date',
		  `c_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Completion Date',
		  `data` longtext,
		  `trail` varchar(40) NULL COMMENT 'Trail ID',
		  PRIMARY KEY (`ID`),
		  KEY `ID` (`ID`),
		  KEY `cid` (`cid`),
		  KEY `aid` (`aid`),
		  KEY `c_date` (`c_date`)
		) $collate;";
	}
}
