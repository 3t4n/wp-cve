<?php

class BWFAN_DB_Table_Task_Claim extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwfan_task_claim';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"claim_id",
			"date_created_gmt",
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
 		  `claim_id` bigint(20) unsigned NOT NULL auto_increment,
		  `date_created_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY (`claim_id`),
		  KEY `date_created_gmt` (`date_created_gmt`)
		) $collate;";
	}
}
