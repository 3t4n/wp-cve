<?php

class BWFAN_Model_Automations extends BWFAN_Model {
	static $primary_key = 'ID';

	public static function count_rows( $dependency = null ) {
		global $wpdb;
		$table_name = self::_table();
		$sql        = 'SELECT COUNT(*) FROM ' . $table_name;

		if ( isset( $_GET['status'] ) && 'all' !== sanitize_text_field( $_GET['status'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			$status = sanitize_text_field( $_GET['status'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
			$status = ( 'active' === $status ) ? 1 : 2;
			$sql    = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE status = %d", $status ); // WPCS: unprepared SQL OK
		}

		return $wpdb->get_var( $sql ); // WPCS: unprepared SQL OK
	}

	/**
	 * Return Automation detail with its meta details
	 *
	 * @param $automation_id
	 *
	 * @return array|object|void|null
	 */
	public static function get_automation_with_data( $automation_id ) {
		$data = self::get( $automation_id );
		if ( ! is_array( $data ) || empty( $data ) ) {
			return [];
		}

		$data['meta'] = BWFAN_Model_Automationmeta::get_automation_meta( $automation_id );

		return $data;
	}

	/**
	 * Get first automation id
	 */
	public static function get_first_automation_id() {
		global $wpdb;
		$query = "SELECT MIN(id) FROM " . self::_table();

		return $wpdb->get_var( $query );
	}

	/**
	 * Get automation name
	 */
	public static function get_event_name( $automation_id ) {
		global $wpdb;
		$table_name = self::_table();
		$query      = $wpdb->prepare( "SELECT event FROM {$table_name} WHERE ID = %d", $automation_id );
		$result     = $wpdb->get_row( $query, ARRAY_A );

		return isset( $result['event'] ) ? $result['event'] : '';
	}

	/**
	 * Get automation run count for a contact
	 *
	 * @param $automation_id
	 * @param $contact_id
	 *
	 * @return int
	 */
	public static function get_contact_automation_run_count( $automation_id = '', $contact_id = '' ) {
		if ( empty( $automation_id ) || empty( $contact_id ) ) {
			return 0;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'bwfan_contact_automations';

		$query         = $wpdb->prepare( "SELECT count(*) as count FROM {$table} WHERE `contact_id` = %d AND `automation_id` = %d", $contact_id, $automation_id );
		$running_count = $wpdb->get_var( $query );

		return absint( $running_count );
	}

	/**
	 * Get top 5 automations
	 *
	 * @return array|object|stdClass[]|null
	 */
	public static function get_top_automations() {
		global $wpdb;

		$automation_table = $wpdb->prefix . 'bwfan_automations';

		$query = "SELECT `ID` AS `aid`, `v`, `title` AS `name` FROM $automation_table AS a ORDER BY a.`ID` DESC LIMIT 0,5";

		return $wpdb->get_results( $query, ARRAY_A );
	}
}
