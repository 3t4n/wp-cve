<?php
/**
 * Cart Abandonment
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cart abandonment tracking class.
 */
class INTRKT_ABANDON_Cron {



	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'intrkt_abandon_update_order_status_action' ) ); //phpcs:ignore WordPress.WP.CronInterval.ChangeDetected

		// Schedule an action if it's not already scheduled.
		if ( ! wp_next_scheduled( 'intrkt_abandon_update_order_status_action' ) ) {
			wp_schedule_event( time(), 'intrkt_every_fifteen_minutes', 'intrkt_abandon_update_order_status_action' );
		}
	}

		/**
		 * Create custom schedule.
		 *
		 * @param array $schedules schedules.
		 * @return mixed
		 */
	public function intrkt_abandon_update_order_status_action( $schedules ) {

		/**
		 * Add filter to change the cron interval time to uodate order status.
		 */
		$cron_time = apply_filters( 'intrkt_update_order_cron_interval', get_option( 'intrkt_cron_run_time', 1 ) );

		$schedules['intrkt_every_fifteen_minutes'] = array(
			'interval' => intval( $cron_time ) * MINUTE_IN_SECONDS,
			'display'  => __( 'Every Fifteen Minutes', 'cart-abandonment-recovery' ),
		);

		return $schedules;
	}
}

INTRKT_ABANDON_Cron::get_instance();
