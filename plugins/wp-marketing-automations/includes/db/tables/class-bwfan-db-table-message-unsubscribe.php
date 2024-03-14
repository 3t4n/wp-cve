<?php

class BWFAN_DB_Table_Message_Unsubscribe extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_message_unsubscribe';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"recipient",
			"mode",
			"c_date",
			"automation_id",
			"c_type",
			"sid",
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
			`recipient` varchar(255) default NULL,
			`mode` tinyint(1) NOT NULL COMMENT '1 - Email 2 - SMS' default 1,
			`c_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`automation_id` bigint(20) unsigned default '0',
			`c_type` tinyint(1) NOT NULL default '1'  COMMENT '1 - Automation 2 - Broadcast 3 - Manual 4 - Form',
			`sid` bigint(20) unsigned NOT NULL default 0 COMMENT 'Step ID',
			PRIMARY KEY (`ID`),
			KEY `ID` (`ID`),
			KEY `recipient` (`recipient`($this->max_index_length)),
			KEY `mode` (`mode`),
			KEY `c_date` (`c_date`),
			KEY `automation_id` (`automation_id`),
			KEY `c_type` (`c_type`),
			KEY `sid` (`sid`)
			 ) $collate;";
	}
}
