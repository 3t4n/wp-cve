<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class SCC_Notifications_Cron {

	public $option = false;

	public function __construct() {
		add_action( 'scc_notifications_fetch_event', array( $this, 'update_notifications' ) );
		add_action( 'wp_ajax_scc_notification_dismiss', array( $this, 'dismiss' ) );

		$this->run_schedule();
	}

	private function run_schedule() {
		// handle already existing installation's cron scheduler
		if ( empty( get_option( 'scc_installation_timestamp' ) ) ) {
			$this->schedule_cron_event();
			update_option( 'scc_installation_timestamp', time() );
		}
		register_activation_hook( SCC_DIR . '/stylish-cost-calculator.php', array( $this, 'schedule_cron_event' ) );
		register_deactivation_hook( SCC_DIR . '/stylish-cost-calculator.php', array( $this, 'clear_scheduler' ) );
	}

	public function dismiss() {
		// verify ajax nonce
		check_ajax_referer( 'notifications-box', 'nonce' );

		$id     = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) );
		$option = $this->get_option();
		$type   = 'feed';

		$option['dismissed'][] = $id;
		$option['dismissed']   = array_unique( $option['dismissed'] );

		// Remove notification.
		if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( (string) $notification['id'] === (string) $id ) {
					unset( $option[ $type ][ $key ] );
					break;
				}
			}
		}

		update_option( 'df_scc_notifications', $option );

		wp_send_json_success();
	}

	public function update_notifications() {
		$response = $this->get_remote_notifications();
	}

	/**
	 * Schedule daily sicense checker event
	 */
	public static function schedule_cron_event() {
		if ( ! wp_next_scheduled( 'scc_notifications_fetch_event' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'scc_notifications_fetch_event' );

			wp_schedule_single_event( time() + 20, 'scc_notifications_fetch_event' );
		}
	}

	/**
	 * Clear any scheduled hook
	 */
	public function clear_scheduler() {
		wp_clear_scheduled_hook( 'scc_notifications_fetch_event' );
	}

	public function get_option( $cache = true ) {

		if ( $this->option && $cache ) {
			return $this->option;
		}

		$option = get_option( 'df_scc_notifications', array() );

		$this->option = array(
			'update'    => ! empty( $option['update'] ) ? $option['update'] : 0,
			'feed'      => ! empty( $option['feed'] ) ? $option['feed'] : array(),
			'dismissed' => ! empty( $option['dismissed'] ) ? $option['dismissed'] : array(),
		);

		return $this->option;
	}

	private function get_remote_notifications() {
		$url = 'https://api.stylishcostcalculator.com/rest/notifications';

		$headers = array(
			'user-agent' => 'StylishCostCalculator/' . STYLISH_COST_CALCULATOR_VERSION,
			'Accept'     => 'application/json',
		);

		$response = wp_remote_get( $url, [
			'headers' => $headers,
		] );

		if ( is_wp_error( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return array();
		}

		$feed   = $this->verify( json_decode( $body, true ) );
		$option = $this->get_option( 'df_scc_notifications', array() );

		update_option(
			'df_scc_notifications',
			array(
				'update'    => time(),
				'feed'      => $feed,
				'dismissed' => $option['dismissed'],
			)
		);

		return $response;
	}

	// public function get_cached_options() {
	//     get_option()
	// }

	public function verify( $notifications ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$data = array();

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return $data;
		}

		$option = $this->get_option();

		foreach ( $notifications as $notification ) {

			// The message and license should never be empty, if they are, ignore.
			if ( empty( $notification['content'] ) || empty( $notification['type'] ) ) {
				continue;
			}

			// Ignore if license type does not match.
			$license = 'free';

			if ( ! in_array( $license, $notification['type'], true ) ) {
				continue;
			}

			// Ignore if expired.
			if ( ! empty( $notification['end'] ) && time() > strtotime( $notification['end'] ) ) {
				continue;
			}

			// Ignore if notifcation has already been dismissed.
			if ( ! empty( $option['dismissed'] ) && in_array( $notification['id'], $option['dismissed'] ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				continue;
			}

			// Prevents bombarding the user with notifications after activation.
			$activated = get_option( 'scc_installation_timestamp', 1614529320 );
			// allow notifications released 2 days before activation
			$notification_min_publish_time = $activated - ( 2 * DAY_IN_SECONDS );

			if (
				! empty( $activated ) &&
				! empty( $notification['start'] ) &&
				$notification_min_publish_time > strtotime( $notification['start'] )
			) {
				continue;
			}

			$data[] = $notification;
		}

		return $data;
	}
}

new SCC_Notifications_Cron();
