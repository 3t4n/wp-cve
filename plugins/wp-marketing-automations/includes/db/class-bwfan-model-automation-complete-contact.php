<?php

class BWFAN_Model_Automation_Complete_Contact extends BWFAN_Model {
	static $primary_key = 'ID';

	public static function get_automation_completed_contacts( $aid, $offset = 0, $limit = 25 ) {
		global $wpdb;
		$table_name = self::_table();

		$query = "SELECT cc.ID,cc.trail as tid, cc.cid, cc.aid, cc.c_date AS c_time, c.email, c.f_name, c.l_name, c.contact_no FROM $table_name as cc JOIN {$wpdb->prefix}bwf_contact AS c ON cc.cid = c.ID WHERE 1 = 1 AND cc.aid = $aid ORDER BY cc.c_date DESC LIMIT $limit OFFSET $offset";

		$contacts = $wpdb->get_results( $query, ARRAY_A );

		$contacts = array_map( function ( $contact ) {
			$contact['c_time'] = strtotime( $contact['c_time'] );

			return $contact;
		}, $contacts );

		return [
			'contacts' => $contacts,
			'total'    => self::get_complete_count( $aid )
		];
	}

	/**
	 * Return table name
	 *
	 * @return string
	 */
	protected static function _table() {
		global $wpdb;

		return $wpdb->prefix . 'bwfan_automation_complete_contact';
	}

	public static function get_complete_count( $aid = 0, $search = '' ) {
		global $wpdb;
		$table_name = self::_table();

		$where = '';
		$join  = '';
		$args  = [];
		if ( ! empty( $aid ) ) {
			$where  .= " AND cc.aid = %d";
			$args[] = $aid;
		}

		if ( ! empty( $search ) ) {
			$join   = " JOIN {$wpdb->prefix}bwf_contact AS c ON cc.cid = c.ID ";
			$where  .= " AND ( c.f_name LIKE %s OR c.l_name LIKE %s OR c.email LIKE %s ) ";
			$args[] = "%$search%";
			$args[] = "%$search%";
			$args[] = "%$search%";
		}

		$query = $wpdb->prepare( "SELECT COUNT(*) AS `count` FROM {$table_name} AS cc $join WHERE 1 = 1 $where", $args );

		return $wpdb->get_var( $query );
	}

	public static function get_contacts_journey( $aid, $search = '', $limit = 10, $offset = 0, $contact_with_count = false, $more_data = false, $type = '' ) {

		$where    = " AND cc.aid = $aid ";
		$contacts = self::get_contacts( $where, $search, $limit, $offset, $more_data, $type );
		if ( true === $contact_with_count ) {
			return [
				'contacts' => $contacts,
				'total'    => self::get_complete_count( $aid, $search )
			];
		}

		return $contacts;
	}

	public static function get_contacts( $where, $search, $limit, $offset, $more_data = false, $status = '' ) {
		global $wpdb;
		$table_name = self::_table();
		$limit      = " LIMIT $limit OFFSET $offset";
		if ( ! empty( $search ) ) {
			$where .= " AND ( c.f_name LIKE '%$search%' OR c.l_name LIKE '%$search%' OR c.email LIKE '%$search%' )";
		}

		$query    = "SELECT  cc.ID,cc.cid, cc.aid, cc.trail, c.email, c.f_name, c.l_name, c.contact_no, cc.s_date FROM $table_name as cc JOIN {$wpdb->prefix}bwf_contact AS c ON cc.cid = c.ID WHERE 1 = 1 $where ORDER BY cc.c_date DESC $limit";
		$contacts = $wpdb->get_results( $query, ARRAY_A );

		return array_map( function ( $contact ) use ( $more_data ) {
			$contact['c_date'] = $contact['s_date'];
			unset( $contact['s_date'] );
			/**Get trail data */
			if ( true === $more_data ) {
				$data            = BWFAN_Common::get_step_by_trail( $contact['trail'] );
				$contact['data'] = isset( $data[0] ) ? $data[0] : $data;
			}

			return $contact;
		}, $contacts );
	}

	public static function get_automation_complete_contact_count( $aids ) {
		global $wpdb;
		$table_name = self::_table();

		$query = "SELECT aid, count(aid) as count FROM $table_name WHERE aid IN ($aids) GROUP BY aid";

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public static function get_automation_contacts( $cid, $search = '', $limit = 10, $offset = 0, $contact_with_count = false, $more_data = false, $type = '' ) {
		$where    = " AND cc.cid = $cid ";
		$contacts = self::get_contacts( $where, $search, $limit, $offset, true, $type );
		if ( true === $contact_with_count ) {
			return [
				'contacts' => $contacts,
				'total'    => self::get_contact_automation_count( $cid )
			];
		}

		return $contacts;
	}

	public static function get_contact_automation_count( $cid ) {
		global $wpdb;
		$table_name = self::_table();

		$query = $wpdb->prepare( "SELECT COUNT(*) AS `count` FROM {$table_name} WHERE `cid` = %d", $cid );

		return $wpdb->get_var( $query );
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

	/**Get status */
	public static function get_status( $status ) {
		switch ( $status ) {
			case 'success':
				$status = 1;
				break;
			case 'wait':
				$status = 2;
				break;
			case 'failed':
				$status = 3;
				break;
		}

		return $status;
	}

	public static function is_contact_completed( $tid ) {
		if ( empty( $tid ) ) {
			return false;
		}
		global $wpdb;
		$table_name = self::_table();

		$query = $wpdb->prepare( "SELECT `ID` FROM {$table_name} WHERE `trail` = %s LIMIT 0,1", $tid );

		$found = $wpdb->get_var( $query );

		return intval( $found ) > 0;
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
		$date_col       = "s_date";
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

		$base_query = "SELECT  count(ID) as contact_counts" . $interval_query . "  FROM `" . $table . "` WHERE 1=1 AND aid = $aid AND`" . $date_col . "` >= '" . $start_date . "' AND `" . $date_col . "` <= '" . $end_date . "' AND aid = $aid " . $group_by . " ORDER BY " . $order_by . " ASC";

		return $wpdb->get_results( $base_query, ARRAY_A );
	}

	public static function get_row_by_trail_id( $trail_id ) {
		global $wpdb;
		$table_name = self::_table();

		$query = $wpdb->prepare( "SELECT * FROM $table_name WHERE trail = %s LIMIT 0,1", $trail_id );

		return $wpdb->get_row( $query, ARRAY_A );
	}

	/**
	 * Get automation complete count
	 *
	 * @param $cid
	 * @param $aid
	 *
	 * @return string|null
	 */
	public static function get_automation_count_by_cid( $cid, $aid ) {
		global $wpdb;

		$table = self::_table();
		$query = $wpdb->prepare( 'SELECT COUNT(`aid`) as `count` FROM ' . $table . ' WHERE `cid` = %d AND `aid` = %d ORDER BY `ID` DESC', $cid, $aid );

		return $wpdb->get_var( $query );
	}

	/**
	 * Check if contact recently (5 mins) completed, considering duplicate case
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
		$datetime = new DateTime( date( 'Y-m-d H:i:s', strtotime( $data['c_date'] ) ) );
		$c_date   = $datetime->modify( '-5 mins' )->format( 'Y-m-d H:i:s' );

		global $wpdb;

		$query = "SELECT `ID` FROM `{$wpdb->prefix}bwfan_automation_complete_contact` WHERE `cid` = %d AND `aid` = %d AND `event` = %s  AND `data` = %s AND `s_date` >= %s LIMIT 1";

		return intval( $wpdb->get_var( $wpdb->prepare( $query, $data['cid'], $data['aid'], $data['event'], $data['data'], $c_date ) ) ) > 0;
	}

	/**
	 * Check if contact has completed the automation for a particular order related event
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

		$query = "SELECT `ID`, `data` FROM `{$wpdb->prefix}bwfan_automation_complete_contact` WHERE `cid` = %d AND `aid` = %d AND `event` = %s  AND $data ORDER BY `ID` DESC LIMIT 0,1";
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
}
