<?php

class BWFAN_AS_V2 {
	private static $instance;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		global $wpdb;
		$wpdb->bwfan_automations                 = $wpdb->prefix . 'bwfan_automations';
		$wpdb->bwfan_automationmeta              = $wpdb->prefix . 'bwfan_automationmeta';
		$wpdb->bwfan_automation_step             = $wpdb->prefix . 'bwfan_automation_step';
		$wpdb->bwfan_automation_contact          = $wpdb->prefix . 'bwfan_automation_contact';
		$wpdb->bwfan_automation_contact_claim    = $wpdb->prefix . 'bwfan_automation_contact_claim';
		$wpdb->bwfan_automation_complete_contact = $wpdb->prefix . 'bwfan_automation_complete_contact';
	}

	/**
	 * Override the action store with our own
	 *
	 * @param string $class
	 *
	 * @return string
	 */
	public function set_store_class( $class ) {
		return BWFAN_AS_V2_Action_Store::class;
	}

	/**
	 * Override the logger with our own
	 *
	 * @param string $class
	 *
	 * @return string
	 */
	public function set_logger_class( $class ) {
		return BWFAN_AS_V2_Log_Store::class;
	}

	public function change_data_store() {
		/** Removing all action data store change filter and then assign ours */
		remove_all_filters( 'action_scheduler_store_class' );
		add_filter( 'action_scheduler_store_class', [ $this, 'set_store_class' ], 999999, 1 );

		/** Removing all log data store change filter and then assign ours */
		remove_all_filters( 'action_scheduler_logger_class' );
		add_filter( 'action_scheduler_logger_class', [ $this, 'set_logger_class' ], 999999, 1 );

		/** Removing all AS memory exceeds filter */
		remove_all_filters( 'action_scheduler_memory_exceeded' );
		add_filter( 'action_scheduler_memory_exceeded', [ $this, 'check_memory_exceeded' ], 1000000, 1 );
	}

	/**
	 * Override memory exceeded filter value
	 *
	 * @param $memory_exceeded
	 *
	 * @return bool
	 */
	public function check_memory_exceeded( $memory_exceeded ) {
		if ( true === $memory_exceeded ) {
			return $memory_exceeded;
		}

		$ins = BWF_AS::instance();

		return $ins->validate_time_breach();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set claim_id 0 for orphaned actions where claim_id exists
	 *
	 * @return void
	 * @throws Exception
	 */
	public function unset_orphaned_claims() {
		global $wpdb;

		$now = new DateTime( '', new DateTimeZone( 'UTC' ) );
		$now->modify( "-3 minutes" );
		$date_limit = $now->format( 'Y-m-d H:i:s' );

		$query = $wpdb->prepare( "SELECT `ID` FROM `{$wpdb->prefix}bwfan_automation_contact_claim` WHERE `created_at` < %s", $date_limit );
		$ids   = $wpdb->get_col( $query );
		if ( empty( $ids ) ) {
			return;
		}

		$time = time();
		do {
			foreach ( $ids as $k => $id ) {
				$query   = $wpdb->prepare( "UPDATE `{$wpdb->prefix}bwfan_automation_contact` SET `claim_id` = 0 WHERE `claim_id` = %d", $id );
				$updated = $wpdb->query( $query );
				if ( 0 === intval( $updated ) ) {
					/** No rows to update */
					$wpdb->delete( $wpdb->prefix . 'bwfan_automation_contact_claim', [ 'ID' => $id ] );
					unset( $ids[ $k ] );
				}
				if ( time() - 10 > $time ) {
					break;
				}
			}
			if ( time() - 10 > $time ) {
				break;
			}
		} while ( ! empty( $ids ) );
	}
}
