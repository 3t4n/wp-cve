<?php
/**
 * Utils.
 *
 * @package Woocommerce-Cart-Abandonment-Recovery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class INTRKT_ABANDON_Utils.
 */
class INTRKT_ABANDON_Utils {


	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Common zapier data
	 *
	 * @var zapier
	 */
	private static $zapier = null;

	/**
	 * Common zapier data
	 *
	 * @var zapier
	 */
	private static $cart_abandonment_settings = null;


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
	 * Check if cart abandonment tracking is enabled.
	 *
	 * @return bool
	 */
	public function intrkt_is_cart_abandonment_tracking_enabled() {
		return true;
	}

	/**
	 * Get cart abandonment tracking cutoff time.
	 *
	 * @param  boolean $in_seconds get cutoff time in seconds if true.
	 * @return bool
	 */
	public function intrkt_get_cart_abandonment_tracking_cut_off_time( $in_seconds = false ) {

		$cart_abandoned_time = apply_filters( 'intrkt_abandon_cart_abandonment_cut_off_time', INTRKT_DEFAULT_CUT_OFF_TIME );
		return $in_seconds ? $cart_abandoned_time * MINUTE_IN_SECONDS : $cart_abandoned_time;

	}

	/**
	 * Check if GDPR is enabled.
	 *
	 * @return bool
	 */
	public function intrkt_is_gdpr_enabled() {

		$intrkt_gdpr_status = get_option( 'intrkt_gdpr_status' );

		// Check if abandonment cart tracking is disabled or zapier webhook is empty.
		if ( isset( $intrkt_gdpr_status ) && 'on' === $intrkt_gdpr_status ) {
			return true;
		}

		return false;
	}


}
