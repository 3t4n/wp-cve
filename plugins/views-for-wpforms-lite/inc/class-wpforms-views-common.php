<?php


class WPForms_Views_Common{

	public static function get_entry_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'wpforms_entries';
	}

	public static function get_entry_fields_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'wpforms_entry_fields';
	}

		/**
	 * Gets the lead (entry) notes table name, including the site's database prefix
	 *
	 * @access public
	 * @static
	 * @global $wpdb
	 *
	 * @return string The lead (entry) notes table name
	 */
	public static function get_entry_meta_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'wpforms_entry_meta';
	}


}