<?php

/**
 * Automation step modal class
 */
class BWFAN_Model_Automation_Step extends BWFAN_Model {
	static $primary_key = 'ID';

	/**
	 * Get Steps
	 *
	 * @param int $aid
	 * @param int $offset
	 * @param int $limit
	 * @param string $search
	 * @param string $order
	 * @param string $order_by
	 * @param array $ids
	 * @param bool $get_total
	 *
	 * @return array
	 */
	public static function get_all_automation_steps( $aid = 0, $offset = 0, $limit = 0, $search = '', $order = 'DESC', $order_by = 'ID', $ids = [], $get_total = false, $get_deleted_nodes = false ) {
		global $wpdb;

		/**
		 * Default response
		 */
		$response = [
			'steps' => [],
			'total' => 0
		];

		$table = self::_table();

		$sql = "SELECT * FROM {$table}  ";

		$where_sql = ' WHERE 1=1';

		/**
		 * If automation id is provided
		 */
		if ( 0 !== intval( $aid ) ) {
			$where_sql .= " AND `aid` = {$aid}";
		}

		/**
		 * If search needed
		 */
		if ( ! empty( $search ) ) {
			$where_sql .= " AND `title` LIKE '%$search%'";
		}

		/** Get by Status */
		if ( ! $get_deleted_nodes ) {
			$where_sql .= " AND `status` != 3";
		}

		if ( ! empty( $ids ) ) {
			$where_sql .= " AND `ID` IN(" . implode( ',', $ids ) . ")";
		}

		/** Set Pagination */
		$pagination_sql = '';
		$limit          = ! empty( $limit ) ? absint( $limit ) : 0;
		$offset         = ! empty( $offset ) ? absint( $offset ) : 0;
		if ( ! empty( $limit ) || ! empty( $offset ) ) {
			$pagination_sql = " LIMIT $offset, $limit";
		}

		/** Order By */
		$order = " ORDER BY {$order_by} {$order}";

		/** Form sql query */
		$sql = $sql . $where_sql . $order . $pagination_sql;

		$response['steps'] = $wpdb->get_results( $sql, ARRAY_A );

		/**
		 * Get total
		 */
		if ( $get_total ) {
			$total_sql         = "SELECT count(*) FROM {$table} " . $where_sql;
			$response['total'] = absint( $wpdb->get_var( $total_sql ) );
		}

		return $response;
	}

	/**
	 * Return table name
	 *
	 * @return string
	 */
	protected static function _table() {
		global $wpdb;

		return $wpdb->prefix . 'bwfan_automation_step';
	}

	/**
	 * Insert new automation to db
	 *
	 * @param $data
	 *
	 * @return int
	 */
	public static function create_new_automation_step( $data ) {
		if ( empty( $data ) ) {
			return;
		}
		self::insert( $data );

		return absint( self::insert_id() );
	}

	/**
	 * Update automation step data by id
	 *
	 * @param $id
	 * @param $data
	 *
	 * @return bool
	 */
	public static function update_automation_step_data( $id, $data ) {
		if ( ! is_array( $data ) ) {
			return false;
		}

		return ! ! self::update( $data, array(
			'id' => absint( $id ),
		) );
	}

	/**
	 * Delete Automation steps
	 *
	 * @param $ids
	 *
	 * @return mixed
	 */
	public static function delete_automation_steps( $ids = [] ) {
		if ( empty( $ids ) ) {
			return false;
		}

		global $wpdb;
		$table_name = self::_table();

		if ( ! is_array( $ids ) ) {
			$ids = [ $ids ];
		}

		$ids = implode( ',', array_map( 'absint', $ids ) );

		return $wpdb->query( "DELETE FROM $table_name WHERE `ID` IN( $ids )" );
	}

	public static function get_step_data_by_id( $step_id ) {
		$result = BWFAN_Model_Automation_Step::get_specific_rows( 'ID', $step_id );

		if ( empty( $result ) && ! is_array( $result ) ) {
			return false;
		}

		return isset( $result[0] ) ? $result[0] : false;
	}

	public static function delete_steps_by_aid( $aid ) {
		global $wpdb;
		$table_name = self::_table();

		$where = "aid = %d";
		if ( is_array( $aid ) ) {
			$where = "aid IN ('" . implode( "','", array_map( 'esc_sql', $aid ) ) . "')";
			$aid   = [];
		}

		$query = " DELETE FROM $table_name WHERE $where";
		$query = $wpdb->prepare( $query, $aid );

		return $wpdb->query( $wpdb->prepare( $query, $aid ) );
	}

	public static function get_step_by_trail( $trail ) {
		global $wpdb;
		$table_name = self::_table();

		$query   = "SELECT ct.c_time AS run_time, st.action, st.type FROM {$wpdb->prefix}bwfan_automation_contact_trail AS ct JOIN {$table_name} AS st ON ct.sid=st.ID WHERE ct.tid='$trail' ORDER BY ct.ID DESC LIMIT 1";
		$results = $wpdb->get_results( $query, ARRAY_A );

		return $results;
	}

	/**
	 * Get automation steps ids
	 *
	 * @param int $aid
	 *
	 * @return array
	 */
	public static function get_automation_step_ids( $aid ) {
		if ( empty( $aid ) ) {
			return [];
		}

		global $wpdb;
		$table = self::_table();

		$query   = $wpdb->prepare( "SELECT ID FROM {$table} WHERE `status` != %d AND aid = %d", 3, $aid );
		$results = $wpdb->get_results( $query, ARRAY_A );

		return $results;
	}

	/**
	 * Return ID of the active step i.e. not equal to 3
	 *
	 * @param $id
	 *
	 * @return int|string|null
	 */
	public static function is_step_active( $id ) {
		if ( empty( $id ) ) {
			return 0;
		}

		global $wpdb;
		$table = self::_table();

		$query = $wpdb->prepare( "SELECT ID FROM {$table} WHERE `status` != %d AND ID = %d", 3, $id );

		return $wpdb->get_var( $query );
	}

	/**
	 * Get email step ids
	 *
	 * @return array
	 */
	public static function get_email_step_ids() {
		global $wpdb;
		$table = self::_table();
		$sql   = "SELECT `ID` FROM {$table}  WHERE `action` LIKE '%s' AND `status`= %d ";

		return $wpdb->get_col( $wpdb->prepare( $sql, '%wp_sendemail%', 1 ) );
	}
}
