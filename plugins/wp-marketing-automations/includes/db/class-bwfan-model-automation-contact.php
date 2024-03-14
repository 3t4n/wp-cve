<?php

class BWFAN_Model_Automation_Contact extends BWFAN_Model {
	static $primary_key = 'ID';

	public static function get_data( $id ) {
		global $wpdb;

		$table = self::_table();
		$query = $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE `ID` = %d', $id );

		return $wpdb->get_row( $query, ARRAY_A );
	}

	/**
	 * Returns table name
	 *
	 * @return string
	 */
	protected static function _table() {
		global $wpdb;

		return $wpdb->prefix . 'bwfan_automation_contact';
	}

	public static function get_automation_contact( $automation_id, $contact_id ) {
		global $wpdb;

		$table = self::_table();
		$query = $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE `aid` = %d AND `cid` = %d ORDER BY `ID` DESC LIMIT 0,1', $automation_id, $contact_id );

		return $wpdb->get_row( $query, ARRAY_A );
	}

	/**
	 * Get Automation Contact by abandoned id
	 *
	 * */
	public static function get_automation_contact_by_ab_id( $ab_id, $cid ) {
		global $wpdb;

		$table        = self::_table();
		$abandoned_id = '%"cart_abandoned_id":"' . $ab_id . '"%';
		$query        = $wpdb->prepare( "SELECT * FROM $table WHERE `cid` = %d AND `data` LIKE '$abandoned_id'", $cid );

		return $wpdb->get_row( $query, ARRAY_A );
	}

	/**
	 * Check if any contact is in automation to execute
	 *
	 * @return bool
	 */
	public static function maybe_can_execute() {
		global $wpdb;
		$time  = current_time( 'timestamp', 1 );
		$table = self::_table();
		$query = $wpdb->prepare( "SELECT MAX(`ID`) FROM {$table} WHERE `e_time` < %s AND `status` IN (1,6)", $time );
		$count = $wpdb->get_var( $query );
		if ( empty( $count ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if contact is active in the automation
	 *
	 * @param $cid
	 * @param $aid
	 *
	 * @return bool
	 */
	public static function maybe_contact_in_automation( $cid, $aid ) {
		global $wpdb;
		$table = self::_table();
		$query = $wpdb->prepare( "SELECT MAX(`ID`) FROM {$table} WHERE `cid` = %d AND `aid` = %d AND `status` IN (%d,%d,%d) ", $cid, $aid, 1, 4, 6 );
		$count = $wpdb->get_var( $query );
		if ( empty( $count ) ) {
			return false;
		}

		return true;
	}

	public static function get_contacts_journey( $aid, $search = '', $limit = 10, $offset = 0, $contact_with_count = false, $more_data = false, $status = '' ) {
		$where = " AND cc.aid = $aid ";

		if ( ! empty( $status ) ) {
			/**Convert string status to integer */
			if ( 'active' === $status ) {
				$where .= " AND (cc.status = 1 OR cc.status = 4 OR cc.status = 6 ) ";
			} else {
				$status = self::get_status( $status );
				$where  .= " AND cc.status = $status";
			}

		}
		$contacts = self::get_contacts( $where, $search, $limit, $offset, $more_data );
		if ( true === $contact_with_count ) {
			return [
				'contacts' => $contacts,
				'total'    => self::get_active_count( $aid, $status, $search )
			];
		}

		return $contacts;
	}

	/**Get status */
	public static function get_status( $status ) {
		switch ( $status ) {
			case 'active':
				$status = 1;
				break;
			case 'failed':
				$status = 2;
				break;
			case 'paused':
				$status = 3;
				break;
			case 'wait':
				$status = 4;
				break;
			case 'terminate':
				$status = 5;
				break;
			case 'Retry':
				$status = 6;
				break;
		}

		return $status;
	}

	public static function get_contacts( $where, $search, $limit, $offset, $more_data ) {
		global $wpdb;
		$table_name = self::_table();
		$limit      = " LIMIT $limit OFFSET $offset";
		if ( ! empty( $search ) ) {
			$where .= " AND ( c.f_name LIKE '%$search%' OR c.l_name LIKE '%$search%' OR c.email LIKE '%$search%' )";
		}

		$more_columns = '';
		if ( true === $more_data ) {
			$more_columns = ", cc.e_time, cc.status, cc.last ";
		}

		$query    = "SELECT  cc.ID,cc.cid, cc.aid, cc.trail, cc.c_date, c.email, c.f_name, c.l_name, c.contact_no, c.creation_date as date $more_columns FROM $table_name as cc JOIN {$wpdb->prefix}bwf_contact AS c ON cc.cid = c.ID WHERE 1=1 $where  ORDER BY cc.c_date DESC $limit";
		$contacts = $wpdb->get_results( $query, ARRAY_A );

		return array_map( function ( $contact ) use ( $more_data ) {
			$contact['e_time'] = isset( $contact['e_time'] ) ? date( 'Y-m-d H:i:s', $contact['e_time'] ) : '';
			/**Get trail data */
			if ( true === $more_data ) {
				$data            = BWFAN_Common::get_step_by_trail( $contact['trail'] );
				$contact['data'] = isset( $data[0] ) ? $data[0] : $data;
			}

			return $contact;
		}, $contacts );
	}

	public static function get_active_count( $aid = '', $status = '', $search = '' ) {
		global $wpdb;
		$table_name = self::_table();

		$where   = '';
		$args    = [];
		$columns = '';
		$join    = '';
		if ( is_array( $aid ) ) {
			$columns = " cc.aid, ";
			$aids    = implode( "', '", $aid );
			$where   .= " AND cc.aid IN ('$aids')";
		}

		if ( empty( $where ) && absint( $aid ) > 0 ) {
			$where  .= " AND cc.aid = %d";
			$args[] = $aid;
		}

		if ( 'active' === $status ) {
			$where .= " AND ( cc.status = 1 OR cc.status = 4 OR cc.status = 6 )";
		} else if ( absint( $status ) ) {
			$where  .= " AND cc.status = %d";
			$args[] = $status;
		}

		if ( ! empty( $search ) ) {
			$join   = " JOIN {$wpdb->prefix}bwf_contact AS c ON cc.cid = c.ID ";
			$where  .= " AND ( c.f_name LIKE %s OR c.l_name LIKE %s OR c.email LIKE %s ) ";
			$args[] = "%$search%";
			$args[] = "%$search%";
			$args[] = "%$search%";
		}

		$where .= is_array( $aid ) ? " GROUP BY cc.aid " : '';
		$query = $wpdb->prepare( "SELECT $columns COUNT(*) AS `count` FROM {$table_name} AS cc $join WHERE 1 = 1 $where ", $args );
		if ( is_array( $aid ) ) {
			return $wpdb->get_results( $query, ARRAY_A );
		}

		return $wpdb->get_var( $query );
	}

	public static function get_automation_contact_count( $aids ) {
		global $wpdb;
		$table_name = self::_table();

		$query = "SELECT aid, count(aid) as count FROM $table_name WHERE aid IN ($aids) GROUP BY aid";

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public static function get_automation_contacts( $cid, $search = '', $limit = 10, $offset = 0, $contact_with_count = false, $more_data = false ) {
		$where    = " AND cc.cid = $cid ";
		$contacts = self::get_contacts( $where, $search, $limit, $offset, $more_data );

		if ( true === $contact_with_count ) {
			return [
				'contacts' => $contacts,
				'total'    => 0
			];
		}

		return $contacts;
	}

	public static function delete_automation_contact_by_aid( $aid ) {
		global $wpdb;
		$table_name = self::_table();

		$where = "aid = %d";
		if ( is_array( $aid ) ) {
			$where = "aid IN ('" . implode( "','", array_map( 'esc_sql', $aid ) ) . "')";
			$aid   = [];
		}

		$query = " DELETE FROM $table_name WHERE $where";

		return $wpdb->query( $wpdb->prepare( $query, $aid ) );
	}

	public static function get_row_by_trail_id( $trail_id ) {
		global $wpdb;
		$table_name = self::_table();

		$query = $wpdb->prepare( "SELECT * FROM $table_name WHERE trail = %s LIMIT 0,1", $trail_id );

		return $wpdb->get_row( $query, ARRAY_A );
	}

	/** Get all the contacts where automations already ran for the given automation range */
	public static function get_contacts_automation( $aid, $start_date, $end_date = '' ) {
		global $wpdb;
		$table_name = self::_table();

		$args = [ $aid ];
		if ( ! empty( $start_date ) && empty( $end_date ) ) {
			$where  = " AND `c_date` > %s";
			$args[] = $start_date;
		}

		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			$where  = " AND `c_date` > %s AND `c_date` < %s";
			$args[] = $start_date;
			$args[] = $end_date;
		}

		$query = $wpdb->prepare( "SELECT DISTINCT `cid` AS contact_id FROM {$table_name} WHERE `aid` = %d $where", $args );

		return $wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * @param $start_date
	 * @param $end_date
	 * @param $is_interval
	 * @param $interval
	 *
	 * @return array|object|null
	 */
	public static function get_total_contacts( $aid, $start_date, $end_date, $is_interval, $interval ) {
		global $wpdb;
		$table          = self::_table();
		$date_col       = "c_date";
		$interval_query = '';
		$group_by       = '';
		$order_by       = ' ID ';

		if ( 'interval' === $is_interval ) {
			$get_interval   = BWFCRM_Dashboards::get_interval_format_query( $interval, $date_col );
			$interval_query = $get_interval['interval_query'];
			$interval_group = $get_interval['interval_group'];
			$group_by       = "GROUP BY " . $interval_group;
			$order_by       = ' time_interval ';
		}

		$base_query = "SELECT  count(ID) as contact_counts" . $interval_query . "  FROM `" . $table . "` WHERE 1=1 AND aid = $aid AND `" . $date_col . "` >= '" . $start_date . "' AND `" . $date_col . "` <= '" . $end_date . "' AND aid = $aid " . $group_by . " ORDER BY " . $order_by . " ASC";

		$contact_counts = $wpdb->get_results( $base_query, ARRAY_A );

		return $contact_counts;
	}

	/**
	 * Get automation contact by last step id
	 *
	 * @param $sid
	 *
	 * @return array
	 */
	public static function get_automation_contact_by_sid( $sid, $step_type = '', $status = 1 ) {
		global $wpdb;
		$table = self::_table();
		$where = " AND `status` = $status ";
		if ( 'goal' === $step_type ) {
			$where = " AND ( `status`= 4 OR `status` = 1 ) ";
		}

		// if ( $aid > 0 ) {
		// 	$where .= " AND aid = $aid";
		// }

		$query = "SELECT ID FROM {$table} WHERE `last` = %d $where";
		$query = $wpdb->prepare( $query, $sid );

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public static function get_automation_count_by_cid( $cid, $aid ) {
		global $wpdb;

		$table = self::_table();
		$query = $wpdb->prepare( 'SELECT COUNT(`aid`) as `count` FROM ' . $table . ' WHERE `cid` = %d AND `aid` = %d ORDER BY `ID` DESC', $cid, $aid );

		return $wpdb->get_var( $query );
	}

	public static function update_status_by_multiple_ids( $ac_ids, $status = 1, $e_time = '' ) {
		if ( empty( $ac_ids ) && ! is_array( $ac_ids ) ) {
			return false;
		}

		global $wpdb;
		$table_name = self::_table();
		$set_e_time = '';
		$args       = [];
		if ( ! empty( $e_time ) ) {
			$set_e_time = " , `e_time` = %d ";
			$args[]     = $e_time;
		}

		$string_placeholders   = array_fill( 0, count( $ac_ids ), '%d' );
		$prepared_placeholders = implode( ', ', $string_placeholders );
		$args                  = array_merge( $args, $ac_ids );

		$query = "UPDATE {$table_name} SET `status`= $status $set_e_time WHERE `ID` IN ($prepared_placeholders) ";
		$query = $wpdb->prepare( $query, $args );

		return $wpdb->query( $query );
	}

	public static function get_multiple_automation_contact( $aids, $contact_id ) {
		global $wpdb;
		$args = [];
		if ( ! is_array( $aids ) ) {
			$args = [ $aids ];
		} else {
			$args = array_merge( $args, $aids );
		}

		$placeholders = array_fill( 0, count( $args ), '%d' );
		$placeholders = implode( ', ', $placeholders );
		$args[]       = $contact_id;

		$table = self::_table();
		$query = $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE `aid` IN (' . $placeholders . ') AND `cid` = %d  ORDER BY `ID` DESC', $args );

		return $wpdb->get_results( $query, ARRAY_A );
	}

	/**
	 * Get next run time
	 *
	 * @param $trail
	 *
	 * @return string|null
	 */
	public static function get_next_run_time_by_trail( $trail ) {
		global $wpdb;
		$table = self::_table();

		return $wpdb->get_var( $wpdb->prepare( "SELECT `e_time` FROM {$table} WHERE `trail` = %s ", $trail ) );
	}

	/**
	 * Check if duplicate contact data, already exists with same data in 5 mins in the same automation
	 *
	 * @param $data
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function check_duplicate_automation_contact( $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		/** Check if contact already exists in automation contact complete table */
		$already_exists = BWFAN_Model_Automation_Complete_Contact::check_duplicate_automation_contact( $data );
		if ( $already_exists ) {
			return true;
		}

		$datetime = new DateTime( date( 'Y-m-d H:i:s', strtotime( $data['c_date'] ) ) );
		$c_date   = $datetime->modify( '-5 mins' )->format( 'Y-m-d H:i:s' );

		global $wpdb;

		$query = "SELECT `ID` FROM `{$wpdb->prefix}bwfan_automation_contact` WHERE `cid` = %d AND `aid` = %d AND `event` = %s  AND `data` = %s AND `c_date` >= %s LIMIT 1";

		return intval( $wpdb->get_var( $wpdb->prepare( $query, $data['cid'], $data['aid'], $data['event'], $data['data'], $c_date ) ) ) > 0;
	}

	/**
	 * Check if contact is in automation for a particular order related event
	 *
	 * @param $aid
	 * @param $cid
	 * @param $order_id
	 * @param $single_item
	 * @param $event
	 *
	 * @return bool
	 */
	public static function is_contact_with_same_order( $aid, $cid, $order_id, $single_item = 0, $event = 'wc_new_order' ) {
		global $wpdb;

		$like1 = '%"order_id":"' . $order_id . '"%';
		$like2 = '%"order_id":' . $order_id . '%';
		$data  = "( `data` LIKE '$like1' OR `data` LIKE '$like2' )";
		if ( ! empty( $single_item ) ) {
			$like1 = '%"wc_single_item_id":"' . $single_item . '"%';
			$like2 = '%"wc_single_item_id":' . $single_item . '%';
			$data  .= " AND (`data` LIKE '$like1' OR `data` LIKE '$like2')";
		}

		$query = "SELECT `ID`, `data` FROM `{$wpdb->prefix}bwfan_automation_contact` WHERE `cid` = %d AND `aid` = %d AND `event` = %s  AND $data ORDER BY `ID` DESC LIMIT 0,1";
		$res   = $wpdb->get_row( $wpdb->prepare( $query, $cid, $aid, $event ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL
		$data  = isset( $res['data'] ) ? json_decode( $res['data'], true ) : [];
		if ( empty( $data ) ) {
			return false;
		}

		$order_validation = false;
		if ( isset( $data['global']['order_id'] ) && ( intval( $order_id ) === intval( $data['global']['order_id'] ) ) ) {
			$order_validation = true;
		}

		if ( empty( $single_item ) || false === $order_validation ) {
			return $order_validation;
		}

		return ( isset( $data['global']['wc_single_item_id'] ) && ( intval( $single_item ) === intval( $data['global']['wc_single_item_id'] ) ) );
	}

	/**
	 * Update e_time of multiple rows
	 *
	 * @param $ids
	 * @param $e_time
	 *
	 * @return bool|int|mysqli_result|null
	 */
	public static function update_e_time_col_of_ids( $ids, $e_time = '' ) {
		if ( empty( $ids ) || ! is_array( $ids ) ) {
			return 0;
		}

		global $wpdb;
		$table_name = self::_table();

		if ( empty( $e_time ) ) {
			$e_time = current_time( 'timestamp', 1 );
		}
		$args = [ $e_time ];

		$string_placeholders   = array_fill( 0, count( $ids ), '%d' );
		$prepared_placeholders = implode( ', ', $string_placeholders );
		$args                  = array_merge( $args, $ids );

		$query = "UPDATE {$table_name} SET `e_time` = %d WHERE `ID` IN ($prepared_placeholders) ";
		$query = $wpdb->prepare( $query, $args );

		return $wpdb->query( $query );
	}
}
