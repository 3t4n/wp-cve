<?php

class BWFAN_AS_V2_Action_Store extends ActionScheduler_Store {

	public function init() {
	}

	public function save_action( ActionScheduler_Action $action, DateTime $date = null ) {
		/** Not scheduling any new action while processing our requests */
	}

	public function fetch_action( $action_id ) {
		$this->log( __FUNCTION__ . ' - running automation contact id: ' . $action_id );

		global $wpdb;

		$cache_key = 'fetch_v2_action_' . $action_id;
		$data      = wp_cache_get( $cache_key, __FUNCTION__ );

		if ( false === $data ) {
			$query = $wpdb->prepare( "SELECT a.*, g.event AS `group` FROM {$wpdb->bwfan_automation_contact} a LEFT JOIN {$wpdb->bwfan_automations} g ON a.aid=g.ID WHERE a.ID=%d", $action_id );
			$data  = $wpdb->get_row( $query ); // WPCS: unprepared SQL OK
			wp_cache_set( $cache_key, $data, __FUNCTION__, ( 60 ) );
		}

		if ( empty( $data ) ) {
			return $this->get_null_action();
		}

		/** Added manually as we are not inserting the data using AS */
		$data->args = '[' . $data->ID . ']';
		$data->hook = 'bwfan_execute_automation_contact';

		return $this->make_action_from_db_record( $data );
	}

	protected function log( $message ) {
		BWFAN_Core()->logger->log( $message, 'as-data-store-v2' );
	}

	protected function get_null_action() {
		return new ActionScheduler_NullAction();
	}

	protected function make_action_from_db_record( $data ) {
		$hook = $data->hook;
		$args = json_decode( $data->args, true );

		/** creating fresh schedule */
		$schedule = new ActionScheduler_NullSchedule();
		$group    = $data->group ? $data->group : '';
		if ( $this->verify_status( $data->status ) ) {
			$action = new ActionScheduler_Action( $hook, $args, $schedule, $group );
		} else {
			/** status if not 1 or 6 - finishing the AS action (status won't occur as we are fetching 1 & 6 status actions only) */
			$action = new ActionScheduler_FinishedAction( $hook, $args, $schedule, $group );
		}
		$this->log( 'action class name ' . get_class( $action ) );

		return $action;
	}

	/**
	 * @param $status
	 * 1 - Active
	 * 6 - Retry
	 * Above 2 are valid status only
	 *
	 * @return bool
	 */
	protected function verify_status( $status ) {
		return ( in_array( intval( $status ), [ 1, 6 ], true ) ) ? true : false;
	}

	/**
	 * @param string $hook
	 * @param array $params
	 *
	 * @return string
	 */
	public function find_action( $hook, $params = [] ) {
		/** This is invoked during unscheduled or next schedule, we are not doing anything, so blank */

		return '';
	}

	/**
	 * @param array $query
	 * @param string $query_type Whether to select or count the results. Default, select.
	 *
	 * @return null|string|array The IDs of actions matching the query
	 */
	public function query_actions( $query = [], $query_type = 'select' ) {
		global $wpdb;

		/** If during actions execution worker if anyone schedule or un-schedule actions then this shouldn't work */
		if ( isset( $query['hook'] ) ) {
			return array();
		}

		/** cleanup call handling */
		if ( isset( $query['status'] ) && in_array( $query['status'], array( 'complete', 'canceled', 'in-progress' ), true ) ) {
			return array();
		}

		if ( 'pending' === $query['status'] ) {
			$query['status'] = [ 1, 6 ];
		}

		/** Code is not going to this level as when clean function ran we get only top 4 statuses */
		$sql = $this->get_query_actions_sql( $query, $query_type );

		return ( 'count' === $query_type ) ? $wpdb->get_var( $sql ) : $wpdb->get_col( $sql ); // WPCS: unprepared SQL OK
	}

	/**
	 * Returns the SQL statement to query (or count) actions.
	 *
	 * @param array $query Filtering options
	 * @param string $select_or_count Whether the SQL should select and return the IDs or just the row count
	 *
	 * @return string SQL statement. The returned SQL is already properly escaped.
	 */
	protected function get_query_actions_sql( array $query, $select_or_count = 'select' ) {
		if ( ! in_array( $select_or_count, array( 'select', 'count' ), true ) ) {
			throw new InvalidArgumentException( __( 'Invalid value for select or count parameter. Cannot query actions.', 'action-scheduler' ) );
		}

		$query = wp_parse_args( $query, [
			'hook'             => '',
			'args'             => null,
			'date'             => null,
			'date_compare'     => '<=',
			'modified'         => null,
			'modified_compare' => '<=',
			'group'            => '',
			'status'           => '0',
			'claimed'          => null,
			'per_page'         => 5,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'ASC',
		] );

		global $wpdb;
		$sql        = ( 'count' === $select_or_count ) ? 'SELECT count(a.ID)' : 'SELECT a.ID ';
		$sql        .= "FROM {$wpdb->bwfan_automation_contact} a";
		$sql_params = [];

		/** Ignoring group here */

		$sql .= ' WHERE 1=1';

		if ( is_array( $query['status'] ) && count( $query['status'] ) > 0 ) {
			$string_placeholder = array_fill( 0, count( $query['status'] ), '%d' );
			$placeholder        = implode( ', ', $string_placeholder );
			$sql                .= " AND a.status IN ($placeholder)";
			$sql_params         = array_merge( $sql_params, $query['status'] );
		} elseif ( '' !== $query['status'] ) {
			$sql          .= ' AND a.status=%s';
			$sql_params[] = $query['status'];
		}

		if ( $query['date'] instanceof DateTime ) {
			$date = clone $query['date'];
			$date->setTimezone( new DateTimeZone( 'UTC' ) );
			$date_string  = $date->getTimestamp();
			$comparator   = $this->validate_sql_comparator( $query['date_compare'] );
			$sql          .= " AND a.e_time $comparator %d";
			$sql_params[] = $date_string;
		} elseif ( $query['modified'] instanceof DateTime ) {
			$date = clone $query['modified'];
			$date->setTimezone( new DateTimeZone( 'UTC' ) );
			$date_string  = $date->getTimestamp();
			$comparator   = $this->validate_sql_comparator( $query['modified_compare'] );
			$sql          .= " AND a.e_time $comparator %d";
			$sql_params[] = $date_string;
		}

		if ( true === $query['claimed'] ) {
			$sql .= ' AND a.claim_id != 0';
		} elseif ( false === $query['claimed'] ) {
			$sql .= ' AND a.claim_id = 0';
		} elseif ( ! is_null( $query['claimed'] ) ) {
			$sql          .= ' AND a.claim_id = %d';
			$sql_params[] = $query['claimed'];
		}

		if ( 'select' === $select_or_count ) {
			switch ( $query['orderby'] ) {
				case 'date':
				default:
					$orderby = 'a.e_time';
					break;
			}
			if ( strtoupper( $query['order'] ) === 'ASC' ) {
				$order = 'ASC';
			} else {
				$order = 'DESC';
			}
			$sql .= " ORDER BY $orderby $order";
			if ( $query['per_page'] > 0 ) {
				$sql          .= ' LIMIT %d, %d';
				$sql_params[] = $query['offset'];
				$sql_params[] = $query['per_page'];
			}
		}

		if ( ! empty( $sql_params ) ) {
			$sql = $wpdb->prepare( $sql, $sql_params ); // WPCS: unprepared SQL OK
		}

		return $sql;
	}

	/**
	 * Get a count of all actions in the store, grouped by status
	 * Not in use we are not showing counts of status on a listing page
	 *
	 * @return array Set of 'status' => int $count
	 */
	public function action_counts() {
		$this->log( __FUNCTION__ );

		return [];
	}

	/**
	 * @param string $action_id
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function cancel_action( $action_id ) {
		$this->log( __FUNCTION__ );
	}

	/**
	 * @param string $action_id
	 */
	public function delete_action( $action_id ) {
		$this->log( __FUNCTION__ );
	}

	/**
	 * don't know where using
	 *
	 * @param string $action_id
	 *
	 * @return DateTime The local date the action is scheduled to run, or the date that it ran.
	 * @throws InvalidArgumentException
	 */
	public function get_date( $action_id ) {
		$date = $this->get_date_gmt( $action_id );
		ActionScheduler_TimezoneHelper::set_local_timezone( $date );

		return $date;
	}

	/**
	 * @param string $action_id
	 *
	 * @return DateTime The GMT date the action is scheduled to run, or the date that it ran.
	 * @throws InvalidArgumentException
	 */
	protected function get_date_gmt( $action_id ) {
		global $wpdb;
		$record = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->bwfan_automation_contact} WHERE ID=%d", $action_id ) );
		if ( empty( $record ) ) {
			throw new InvalidArgumentException( sprintf( __( 'Unidentified action %s', 'action-scheduler' ), $action_id ) );
		}
		if ( $this->verify_status( $record->status ) ) {
			return as_get_datetime_object( $record->e_time );
		}
	}

	/**
	 * @param $max_actions
	 * @param DateTime|null $before_date
	 * @param $hooks
	 * @param $group
	 *
	 * @return ActionScheduler_ActionClaim
	 */
	public function stake_claim( $max_actions = 10, DateTime $before_date = null, $hooks = array(), $group = '' ) {
		$this->log( __FUNCTION__ );
		$claim_id = $this->generate_claim_id();
		$this->claim_actions( $claim_id, $max_actions, $before_date, $hooks, $group );
		$action_ids = $this->find_actions_by_claim_id( $claim_id );
		$this->log( 'claimed action ids' );

		return new ActionScheduler_ActionClaim( $claim_id, $action_ids );
	}

	/**
	 * Generating claim id - current date time
	 *
	 * @return int
	 */
	protected function generate_claim_id() {
		global $wpdb;
		$now = as_get_datetime_object();
		$wpdb->insert( $wpdb->bwfan_automation_contact_claim, [
			'created_at' => $now->format( 'Y-m-d H:i:s' ),
		] );

		return $wpdb->insert_id;
	}

	/**
	 * @param $claim_id
	 * @param $limit
	 * @param DateTime|null $before_date Should use UTC timezone.
	 * @param $hooks
	 * @param $group
	 *
	 * @return int The number of actions that were claimed
	 */
	protected function claim_actions( $claim_id, $limit, DateTime $before_date = null, $hooks = array(), $group = '' ) {
		global $wpdb;
		$this->log( __FUNCTION__ );

		/** can't use $wpdb->update() because of the <= condition */
		$update = "SELECT t.`ID` FROM {$wpdb->bwfan_automation_contact} AS t INNER JOIN {$wpdb->bwfan_automations} AS aut ON t.`aid` = aut.`ID`";
		$params = [];

		$where    = 'WHERE t.`claim_id` = 0 AND t.`e_time` <= %s AND t.`status` IN (1, 6) AND aut.`status` = 1';
		$params[] = time();

		$order    = 'ORDER BY t.`e_time` ASC, t.`ID` DESC LIMIT %d';
		$params[] = $limit;

		$sql       = $wpdb->prepare( "{$update} {$where} {$order}", $params ); //phpcs:ignore WordPress.DB.PreparedSQL, WordPress.DB.PreparedSQLPlaceholders
		$claim_ids = $wpdb->get_results( $sql, ARRAY_A ); // WPCS: unprepared SQL OK
		$this->log( count( $claim_ids ) . ' ids claimed' );
		if ( ! is_array( $claim_ids ) || count( $claim_ids ) === 0 ) {
			return 0;
		}
		$claim_ids = array_column( $claim_ids, 'ID' );

		/** Update call */
		$type   = array_fill( 0, count( $claim_ids ), '%d' );
		$format = implode( ', ', $type );
		$query  = "UPDATE {$wpdb->bwfan_automation_contact} SET `claim_id` = %d WHERE `ID` IN ({$format})";
		$params = array( $claim_id );
		$params = array_merge( $params, $claim_ids );
		$sql    = $wpdb->prepare( $query, $params ); // WPCS: unprepared SQL OK

		$rows_affected = $wpdb->query( $sql ); // WPCS: unprepared SQL OK
		if ( false === $rows_affected ) {
			throw new RuntimeException( __( 'Unable to claim actions. Database error.', 'action-scheduler' ) );
		}

		return (int) $rows_affected;
	}

	/**
	 * Get actions by claim_id
	 *
	 * @param string $claim_id
	 *
	 * @return Array
	 */
	public function find_actions_by_claim_id( $claim_id ) {
		$this->log( __FUNCTION__ . ' ' . $claim_id );

		global $wpdb;

		$cache_key = 'v2_action_id_for_claim_id_' . $claim_id;

		$cache_available = wp_cache_get( $cache_key, __FUNCTION__ );
		if ( false !== $cache_available ) {
			return $cache_available;
		}

		$sql = "SELECT `ID` FROM {$wpdb->bwfan_automation_contact} WHERE `claim_id` = %d ORDER BY `e_time` ASC, `ID` DESC";
		$sql = $wpdb->prepare( $sql, $claim_id ); // WPCS: unprepared SQL OK

		$action_ids = $wpdb->get_col( $sql ); // WPCS: unprepared SQL OK
		$action_ids = array_map( 'intval', $action_ids );

		$this->log( 'found ' . count( $action_ids ) . ' automation contacts (' . implode( ',', $action_ids ) . ') against claim id ' . $claim_id );

		wp_cache_set( $cache_key, $action_ids, __FUNCTION__, ( HOUR_IN_SECONDS / 4 ) );

		return $action_ids;
	}

	/**
	 * Return unique claim id actions counts
	 *
	 * @return int
	 */
	public function get_claim_count() {
		$this->log( __FUNCTION__ );
		global $wpdb;

		/** passing status 1 & 6 as those actions needs to execute */
		$sql = "SELECT COUNT(DISTINCT claim_id) FROM {$wpdb->bwfan_automation_contact} WHERE claim_id != 0 AND status IN (1, 6)";

		return (int) $wpdb->get_var( $sql ); // WPCS: unprepared SQL OK
	}

	/**
	 * Return an action's claim ID, as stored in the claim_id column
	 *
	 * @param string $action_id
	 *
	 * @return mixed
	 */
	public function get_claim_id( $action_id ) {
		$this->log( __FUNCTION__ );

		global $wpdb;

		$sql = "SELECT claim_id FROM {$wpdb->bwfan_automation_contact} WHERE ID=%d";
		$sql = $wpdb->prepare( $sql, $action_id ); // WPCS: unprepared SQL OK

		return (int) $wpdb->get_var( $sql ); // WPCS: unprepared SQL OK
	}

	/**
	 * Releasing the claim
	 *
	 * @param ActionScheduler_ActionClaim $claim
	 */
	public function release_claim( ActionScheduler_ActionClaim $claim ) {
		$this->log( __FUNCTION__ . ' ' . $claim->get_id() );

		global $wpdb;
		$wpdb->update( $wpdb->bwfan_automation_contact, [
			'claim_id' => 0,
		], [
			'claim_id' => $claim->get_id(),
		], [ '%d' ], [ '%d' ] );

		$wpdb->delete( $wpdb->bwfan_automation_contact_claim, [
			'ID' => $claim->get_id(),
		], [ '%d' ] );
	}

	/**
	 * Un-claiming an action
	 *
	 * @param string $action_id
	 *
	 * @return void
	 */
	public function unclaim_action( $action_id ) {
		$this->log( __FUNCTION__ );

		global $wpdb;
		$wpdb->update( $wpdb->bwfan_automation_contact, [
			'claim_id' => 0,
		], [
			'ID' => $action_id,
		], [ '%s' ], [ '%d' ] );
	}

	/**
	 * Run when an action is failed
	 *
	 * @param string $action_id
	 */
	public function mark_failure( $action_id ) {
		$this->log( __FUNCTION__ );

		/**
		 * Maybe update attempt count
		 * if yes, update the e_time and change status to 6 i.e. retry
		 * update the trail with time and message against the failure step
		 */
	}

	/**
	 * @param string $action_id
	 *
	 * @return void
	 */
	public function log_execution( $action_id ) {
		/** no need to log as we are managing logs differently, even attempts */
	}

	/**
	 * @param string $action_id
	 */
	public function mark_complete( $action_id ) {
		/** no need to mark anything complete */
	}

	/**
	 * Get status AS friendly from the action
	 *
	 * @param $action_id
	 *
	 * @return string
	 */
	public function get_status( $action_id ) {
		$this->log( __FUNCTION__ );

		global $wpdb;
		$sql    = "SELECT status FROM {$wpdb->bwfan_automation_contact} WHERE ID=%d";
		$sql    = $wpdb->prepare( $sql, $action_id ); // WPCS: unprepared SQL OK
		$status = $wpdb->get_var( $sql ); // WPCS: unprepared SQL OK
		if ( $status === null ) {
			throw new InvalidArgumentException( __( 'Invalid action ID. No status found.', 'action-scheduler' ) );
		} else {
			return $this->get_as_defined_status_val( $status );
		}
	}

	protected function get_as_defined_status_val( $status ) {
		switch ( $status ) {
			case '1':
			case '6':
				return 'pending';
		}

		return $status;
	}

	/**
	 * Cancel pending actions by hook.
	 *
	 * @param string $hook
	 *
	 * @since 3.0.0 Action Scheduler and 1.0.8 Autonami
	 */
	public function cancel_actions_by_hook( $hook ) {
		return;
	}

	/**
	 * Cancel pending actions by group.
	 *
	 * @param string $group
	 *
	 * @since 3.0.0 Action Scheduler and 1.0.8 Autonami
	 */
	public function cancel_actions_by_group( $group ) {
		return;
	}

}
