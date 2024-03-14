<?php
/**
 * Iubenda radar service class.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Iubenda radar service.
 *
 * @Class Radar_Service
 */
class Radar_Service {
	/**
	 * The source for scheduled scans refresh.
	 */
	const SOURCE_SCHEDULED_REFRESH = 'iub_wp_plugin_scheduled_refresh';

	/**
	 * The source for automatic scans at first time plugin installations.
	 */
	const SOURCE_INSTALL_TRIGGERED = 'iub_wp_plugin_install_triggered';

	/**
	 * Authorization data
	 *
	 * @var array
	 */
	private $authorization = array(
		'username' => 'devops',
		'password' => 'orIDiVvPVdHvwyjM4',
	);

	/**
	 * Service rating class.
	 *
	 * @var Service_Rating
	 */
	private $service_rating;

	/**
	 * Radar urls.
	 *
	 * @var array
	 */
	private $url = array(
		'match-async'    => 'https://radar.iubenda.com/api/match-async',
		'match-progress' => 'https://radar.iubenda.com/api/match-progress',
	);

	/**
	 * API configuration.
	 *
	 * @var array
	 */
	public $api_configuration = array();

	/**
	 * Update notice message.
	 *
	 * @var string
	 */
	private $update_message = "Please, make also sure the plugin is updated to the <a target='_blank' href='https://wordpress.org/plugins/iubenda-cookie-law-solution/'><u> latest version.</u></a>";

	/**
	 * Maximum allowable delay in seconds.
	 *
	 * @var int The maximum delay time in seconds.
	 */
	private $max_delay_in_sec = MINUTE_IN_SECONDS * 15;

	/**
	 * The dynamic interval for reloading radar configuration in seconds.
	 *
	 * @var int
	 */
	const RELOAD_INTERVAL = 3 * WEEK_IN_SECONDS;

	/**
	 * Radar_Service constructor.
	 */
	public function __construct() {
		$this->service_rating    = new Service_Rating();
		$this->api_configuration = array_filter( (array) get_option( 'iubenda_radar_api_configuration', array() ) );

		add_action( 'init', array( $this, 'check_schedule_reload_radar_config' ) );
		add_action( 'wp_ajax_force_reload_radar_config', array( $this, 'force_reload_radar_config' ) );

		/**
		 * Adds action to schedule the reload of radar configuration.
		 *
		 * @action iubenda_schedule_reload_radar_config
		 */
		add_action( 'iubenda_schedule_reload_radar_config', array( $this, 'schedule_reload_radar_config' ) );
	}

	/**
	 * Ask radar to send request.
	 *
	 * @return void
	 */
	public function ask_radar_to_send_request() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			iub_verify_ajax_request( 'iub_radar_percentage_reload_nonce', 'iub_nonce' );
		}

		if ( ! empty( $this->api_configuration ) ) {
			$this->send_radar_progress_request();

			return;
		}

		$this->send_radar_sync_request();
	}

	/**
	 * Calculate radar percentage.
	 *
	 * @return array
	 */
	public function calculate_radar_percentage() {
		$services['pp']   = $this->service_rating->is_privacy_policy_activated();
		$services['cs']   = $this->service_rating->is_cookie_solution_activated();
		$services['cons'] = $this->service_rating->is_cookie_solution_activated() && $this->service_rating->is_cookie_solution_automatically_parse_enabled();
		$services['tc']   = $this->service_rating->is_terms_conditions_activated();

		return array(
			'percentage' => ( count( array_filter( $services ) ) / count( $services ) ) * 100,
			'services'   => $services,
		);
	}

	/**
	 * Sends radar sync request for the first time.
	 *
	 * @param   bool $is_scheduled_scan  If true, it indicates that the reload is scheduled.
	 *
	 * @return bool
	 */
	private function send_radar_sync_request( bool $is_scheduled_scan = false ) {
		$payload_body  = array(
			'url'                  => get_site_url(),
			'detectLegalDocuments' => 'true',
			'source'               => $is_scheduled_scan ? self::SOURCE_SCHEDULED_REFRESH : self::SOURCE_INSTALL_TRIGGERED,
		);
		$payload       = $this->prepare_payload( $payload_body );
		$response      = wp_remote_get( iub_array_get( $this->url, 'match-async' ), $payload );
		$response_code = wp_remote_retrieve_response_code( $response );

		// check response code.
		$this->check_response( $response, $response_code );

		$response_body = json_decode( iub_array_get( $response, 'body' ), true );

		$response_body['trial_num']  = 1;
		$response_body['next_trial'] = time();

		iubenda()->iub_update_options( 'iubenda_radar_api_configuration', $response_body );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			wp_send_json(
				array(
					'code'   => $response_code,
					'status' => 'progress',
				)
			);
		}

		return true;
	}

	/**
	 * Send radar request to check the progress
	 *
	 * @return bool
	 */
	private function send_radar_progress_request() {
		$iubenda_radar_api_configuration = $this->api_configuration;

		if ( 'completed' === (string) iub_array_get( $iubenda_radar_api_configuration, 'status' ) ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				wp_send_json(
					array(
						'code'   => '200',
						'status' => 'complete',
						'data'   => $this->calculate_radar_percentage(),
					)
				);
			}

			return true;
		}

		// Check if the next trial is not now.
		$next_trial = (int) iub_array_get( $iubenda_radar_api_configuration, 'next_trial' );
		if ( $next_trial > time() ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				// Calculate the remaining time until the next trial.
				$next_request_in_sec = $next_trial - time();

				// Fallback to the maximum allowable value if $next_request_in_sec exceeds $max_delay_in_sec.
				$next_request_in_sec = min( $next_request_in_sec, $this->max_delay_in_sec );

				wp_send_json(
					array(
						'code'   => '200',
						'status' => 'timeout',
						'data'   => $next_request_in_sec,
					)
				);
			}

			return true;
		}

		$next_trial = time();
		$trial_num  = (int) ( iub_array_get( $iubenda_radar_api_configuration, 'trial_num', 1 ) ?? 1 );

		// Check if 3 trials were made in this round.
		if ( is_int( $trial_num / 3 ) ) {
			$rounds     = $trial_num / 3;
			$next_trial = time() + ( pow( 30, $rounds ) );
		}
		++$trial_num;

		// Prepare payload.
		$scan_id      = iub_array_get( $iubenda_radar_api_configuration, 'id' );
		$payload_body = array(
			'id' => $scan_id,
		);
		$payload      = $this->prepare_payload( $payload_body );

		// Send request.
		$response      = wp_remote_get( iub_array_get( $this->url, 'match-progress' ), $payload );
		$response_code = wp_remote_retrieve_response_code( $response );

		// check response code.
		$this->check_response( $response, $response_code );

		// Update options.
		$response_body               = json_decode( iub_array_get( $response, 'body' ), true );
		$response_body['trial_num']  = $trial_num;
		$response_body['next_trial'] = $next_trial;
		iubenda()->iub_update_options( 'iubenda_radar_api_configuration', $response_body );

		// Send JSON response if doing AJAX.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			wp_send_json(
				array(
					'code'   => $response_code,
					'status' => 'progress',
				)
			);
		}

		return true;
	}

	/**
	 * Check radar response
	 *
	 * @param   array|WP_Error $response       The response or WP_Error on failure.
	 * @param   int|string     $response_code  The response code as an integer. Empty string if incorrect parameter given.
	 *
	 * @return void
	 */
	private function check_response( $response, $response_code ) {
		if ( 200 !== (int) $response_code || is_wp_error( $response ) ) {
			if ( ! is_numeric( $response_code ) ) {
				$message = $this->update_message;
			} elseif ( 408 === (int) $response_code ) {
				// 408 error code it`s mean request timeout
				$message = $this->update_message;
			} elseif ( 4 === (int) substr( $response_code, 0, 1 ) ) {
				// 4xx error codes
				$message = $this->update_message;
			} else {
				$message = 'Something went wrong: ' . $response->get_error_message();
			}

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				wp_send_json(
					array(
						'code'    => $response_code,
						'status'  => 'error',
						'message' => $message,
					)
				);
			}
		}
	}

	/**
	 * Checks and schedules the reload of radar configuration after a specific interval.
	 *
	 * This function is responsible for checking if there is already a scheduled event
	 * for reloading radar configuration. If none exists, it creates a new schedule
	 * to reload the radar data after a specific interval.
	 */
	public function check_schedule_reload_radar_config() {
		// Check if the scheduled event already exists.
		if ( ! wp_next_scheduled( 'iubenda_schedule_reload_radar_config' ) ) {
			// Schedule the event with the specified dynamic interval.
			wp_schedule_single_event( time() + self::RELOAD_INTERVAL, 'iubenda_schedule_reload_radar_config' );
		}
	}

	/**
	 * Callback function for the scheduled reload of radar configuration.
	 *
	 * This function is triggered when the scheduled event is executed.
	 * It forces the deletion of radar configuration and asks radar to send a request.
	 *
	 * @return void
	 */
	public function schedule_reload_radar_config() {
		$this->force_reload_radar_config( true );
	}

	/**
	 * Forces the reload of radar configuration.
	 *
	 * This function is responsible for forcing the deletion of radar configuration
	 * and triggering a radar sync request.
	 *
	 * @param   bool $is_scheduled_scan  If true, it indicates that the reload is scheduled.
	 *
	 * @return void
	 */
	public function force_reload_radar_config( bool $is_scheduled_scan = false ) {
		$this->send_radar_sync_request( $is_scheduled_scan );
	}

	/**
	 * Encode credentials to base64.
	 *
	 * @return string The base64 encoded string.
	 */
	private function encode_credentials_to_base64() {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return base64_encode( $this->authorization['username'] . ':' . $this->authorization['password'] );
	}

	/**
	 * Prepare the payload array.
	 *
	 * @param   array $body  The body of the request.
	 *
	 * @return array The prepared payload ar ray.
	 */
	private function prepare_payload( $body ) {
		return array(
			'body'        => $body,
			'headers'     => array( 'Authorization' => "Basic {$this->encode_credentials_to_base64()}" ),
			'httpversion' => '1.0',
			'redirection' => 5,
			'timeout'     => 30,
		);
	}
}
