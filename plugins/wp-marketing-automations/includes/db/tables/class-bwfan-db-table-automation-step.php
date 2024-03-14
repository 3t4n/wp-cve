<?php

class BWFAN_DB_Table_Automation_Step extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automation_step';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"aid",
			"type",
			"action",
			"status",
			"data",
			"created_at",
			"updated_at",
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
 		    `ID` bigint(10) UNSIGNED NOT NULL auto_increment,
			`aid` bigint(10) UNSIGNED NOT NULL ,
			`type` tinyint(1) UNSIGNED NOT NULL default 1 COMMENT '1 - Wait | 2 - Action | 3 - Goal | 4 - Conditional | 5 - Exit',
			`action` varchar(255) NULL,
			`status` tinyint(1) NOT NULL default 0 COMMENT '1 - Active | 2 - Draft | 3 - Deleted',
			`data` longtext,
			`created_at` datetime NOT NULL default '0000-00-00 00:00:00',
			`updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (`ID`),
			KEY `aid` (`aid`),
			KEY `type` (`type`)
		) $collate;";
	}
}
