<?php
/**
 * Common functions for GSC module.
 *
 * @package SurferSEO
 */

namespace SurferSEO\Surfer\GSC;

trait Surfer_GSC_Common {

	/**
	 * Checks if GSC is connected.
	 *
	 * @param bool $force - if true, will force check.
	 * @return bool
	 */
	public function check_if_gsc_connected( $force = false ) {
		$connected = Surfer()->get_surfer()->is_surfer_connected();
		if ( false === $connected ) {
			return false;
		}

		$cached_status = Surfer()->get_surfer_settings()->get_option( 'content-importer', 'surfer_gsc_connection', null );

		if ( null !== $cached_status && false === $force ) {
			return $cached_status;
		}

		$params = array(
			'url' => home_url(),
		);

		$return = Surfer()->get_surfer()->make_surfer_request( '/check_gsc_connection', $params );

		$response = isset( $return['response'] ) ? $return['response'] : array();

		if ( isset( $response['gsc_connected'] ) && true === (bool) $response['gsc_connected'] ) {
			$connection = true;
		} else {
			$connection = false;
		}

		Surfer()->get_surfer_settings()->save_option( 'content-importer', 'surfer_gsc_connection', $connection );
		return $connection;
	}

	/**
	 * Gets data of a single post for performance report.
	 *
	 * @param int $post_id - ID of the post.
	 * @return string | bool
	 */
	public function get_previous_period_date( $post_id ) {
		global $wpdb;
		$previous_record = $wpdb->get_results( $wpdb->prepare( 'SELECT p.data_gathering_date FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS p WHERE p.post_id = %d ORDER BY p.data_gathering_date DESC LIMIT 2', $post_id ) );

		if ( null !== $previous_record && 2 === count( $previous_record ) ) {
			return $previous_record[1]->data_gathering_date;
		}

		return false;
	}

	/**
	 * Checks if user want to receive performance report by email
	 *
	 * @return bool
	 */
	public function performance_report_email_notification_endabled() {

		$notification_enabled = Surfer()->get_surfer_settings()->get_option( 'content-importer', 'surfer_position_monitor_summary', false );

		if ( isset( $notification_enabled ) && 1 === intval( $notification_enabled ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Display data period value.
	 *
	 * @param string $date - date.
	 * @return string
	 */
	public function return_period_based_on_gathering_date( $date ) {

		if ( false !== \DateTime::createFromFormat( 'Y-m-d H:i:s', $date ) || false !== \DateTime::createFromFormat( 'Y-m-d', $date ) ) {

			$start = gmdate( 'd-m-Y', strtotime( 'previous monday', strtotime( $date ) ) );
			$end   = gmdate( 'd-m-Y', strtotime( 'previous sunday', strtotime( $date ) ) );

			return $start . ' - ' . $end;
		}

		return esc_html__( 'No data gathered yet.', 'surferseo' );
	}
}
