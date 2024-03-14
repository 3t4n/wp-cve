<?php

class BWFAN_Model_Abandonedcarts extends BWFAN_Model {
	public static $primary_key = 'ID';

	public static function get_abandoned_data( $where = '', $offset = '', $per_page = '', $order_by = 'ID', $output = OBJECT ) {
		global $wpdb;

		$limit_string = '';
		if ( '' !== $offset ) {
			$limit_string = "LIMIT {$offset}";
		}
		if ( '' !== $per_page && '' !== $limit_string ) {

			$limit_string .= ',' . $per_page;
		}
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bwfan_abandonedcarts {$where} ORDER BY {$order_by} DESC {$limit_string}", $output ); // WPCS: unprepared SQL OK

		return $results;
	}

	public static function delete_abandoned_cart_row( $data ) {
		if ( ! is_array( $data ) || empty( $data ) ) {
			return;
		}

		global $wpdb;
		$where      = '';
		$count      = count( $data );
		$i          = 0;
		$table_name = $wpdb->prefix . 'bwfan_abandonedcarts';

		foreach ( $data as $key => $value ) {
			$i ++;

			if ( 'string' === gettype( $value ) ) {
				$where .= '`' . $key . '` = ' . "'" . $value . "'";
			} else {
				$where .= '`' . $key . '` = ' . $value;
			}

			if ( $i < $count ) {
				$where .= ' AND ';
			}
		}

		return $wpdb->query( 'DELETE FROM ' . $table_name . " WHERE $where" ); // WPCS: unprepared SQL OK
	}

	/**
	 * Check if any carts available for execution.
	 *
	 * @param $abandoned_time
	 *
	 * @return bool
	 */
	public static function maybe_run( $abandoned_time = 0 ) {
		global $wpdb;
		$table          = self::_table();
		$abandoned_time = intval( $abandoned_time );
		$query          = "SELECT `ID` FROM {$table} WHERE `status` IN (0, 4)";
		if ( $abandoned_time > 0 ) {
			$query .= $wpdb->prepare( " AND TIMESTAMPDIFF(MINUTE,last_modified,UTC_TIMESTAMP) >= %d", $abandoned_time );
		}
		$count = $wpdb->get_var( $query );
		if ( empty( $count ) ) {
			return false;
		}

		return true;
	}
}
