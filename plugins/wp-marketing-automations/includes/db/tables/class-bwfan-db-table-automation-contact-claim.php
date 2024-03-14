<?php

class BWFAN_DB_Table_Automation_Contact_Claim extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_automation_contact_claim';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"ID",
			"created_at",
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
			`created_at` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (`ID`)
		) $collate;";
	}
}
