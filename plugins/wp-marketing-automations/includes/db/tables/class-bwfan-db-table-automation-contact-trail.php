<?php

class BWFAN_DB_Table_Automation_Contact_Trail extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automation_contact_trail';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"tid",
			"cid",
			"aid",
			"sid",
			"c_time",
			"status",
			"data",
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
 		    `ID` bigint(20) UNSIGNED NOT NULL auto_increment,
			`tid` varchar(40) NOT NULL COMMENT 'Trail ID',
			`cid` bigint(12) UNSIGNED NOT NULL COMMENT 'Contact ID',
			`aid` bigint(10) UNSIGNED NOT NULL COMMENT 'Automation ID',
			`sid` bigint(10) UNSIGNED NOT NULL COMMENT 'Step ID',
			`c_time` bigint(12) UNSIGNED NOT NULL,
			`status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 - Success | 2 - Wait | 3 - Failed | 4 - Skipped',
			`data` varchar(255) NULL,
			PRIMARY KEY (`ID`),
			KEY `ID` (`ID`),
			KEY `tid` (`tid`(40)),
			KEY `cid` (`cid`),
			KEY `sid` (`sid`),
			KEY `status` (`status`)
		) $collate;";
	}
}
