<?php

class BWFAN_DB_Table_Options extends BWFAN_DB_Tables_Base {
	public $table_name = 'bwf_options';

	/**
	 * Get table's columns
	 *
	 * @return string[]
	 */
	public function get_columns() {
		return [
			"id",
			"key",
			"value",
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
 		  	`id` bigint(10) unsigned NOT NULL auto_increment,
			`key` varchar(150) default NULL,
			`value` longtext,
			PRIMARY KEY (`id`),
			KEY `id` (`id`),
			KEY `key` (`key`)
		) $collate;";
	}
}
