<?php
/**
 * This file contains a class that handles missed schedule posts.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements a missed schedule handler.
 */
class Nelio_Content_Missed_Schedule_Handler {

	const ACTION      = 'nelio_content_missed_schedule_handler';
	const NONCE       = 'nelio_content_missed_schedule_handler_nonce';
	const BATCH_LIMIT = 20;
	const FREQUENCY   = 900;
	const OPTION_NAME = 'nc_missed_schedule_handler_last_run';

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		add_action( 'plugins_loaded', array( $this, 'add_hooks_if_handler_is_enabled' ) );

	}//end init()

	public function add_hooks_if_handler_is_enabled() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_missed_schedule_handler' ) ) {
			return;
		}//end if

		add_action( 'send_headers', array( $this, 'send_headers' ) );
		add_action( 'shutdown', array( $this, 'maybe_send_handler_run_request' ) );
		add_action( 'wp_ajax_' . self::ACTION, array( $this, 'maybe_handle_missed_posts' ) );
		add_action( 'wp_ajax_nopriv_' . self::ACTION, array( $this, 'maybe_handle_missed_posts' ) );

	}//end add_hooks_if_handler_is_enabled()

	/**
	 * Prevent caching of requests including the AJAX script.
	 *
	 * Includes the no-caching headers if the response will include the
	 * AJAX fallback script. This is to prevent excess calls to the
	 * admin-ajax.php action.
	 */
	public function send_headers() {
		if ( ! $this->can_handler_be_run() ) {
			return;
		}//end if

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		nocache_headers();
	}//end send_headers()

	public function enqueue_scripts() {
		if ( ! $this->can_handler_be_run() ) {
			return;
		}//end if

		// Shutdown request is not needed.
		remove_action( 'shutdown', array( $this, 'maybe_send_handler_run_request' ) );

		// Null script for inline script to come afterward.
		wp_register_script( // phpcs:ignore
			self::ACTION,
			null,
			array(),
			null,
			true
		);

		$request = array(
			'url'  => add_query_arg( 'action', self::ACTION, admin_url( 'admin-ajax.php' ) ),
			'args' => array(
				'method' => 'POST',
				'body'   => self::NONCE . '=' . $this->get_no_priv_nonce(),
			),
		);

		$script = '
		(function( request ){
			if ( ! window.fetch ) {
				return;
			}
			request.args.body = new URLSearchParams( request.args.body );
			fetch( request.url, request.args );
		}( ' . wp_json_encode( $request ) . ' ));
		';

		wp_add_inline_script(
			self::ACTION,
			$script
		);

		wp_enqueue_script( self::ACTION );
	}//end enqueue_scripts()

	public function maybe_send_handler_run_request() {
		if ( ! $this->can_handler_be_run() ) {
			return;
		}//end if

		// Do request.
		$request = array(
			'url'  => add_query_arg( 'action', self::ACTION, admin_url( 'admin-ajax.php' ) ),
			'args' => array(
				'timeout'   => 0.01,
				'blocking'  => false,
				/** This filter is documented in wp-includes/class-wp-http-streams.php */
				'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
				'body'      => array(
					self::NONCE => $this->get_no_priv_nonce(),
				),
			),
		);

		wp_remote_post( $request['url'], $request['args'] );
	}//end maybe_send_handler_run_request()

	public function maybe_handle_missed_posts() {
		if ( ! $this->verify_no_priv_nonce( $_POST[ self::ACTION . '_nonce' ] ) ) { // phpcs:ignore
			wp_send_json_success();
		}//end if

		if ( ! $this->can_handler_be_run() ) {
			wp_send_json_success();
		}//end if

		$this->publish_missed_posts();
		wp_send_json_success();
	}//end maybe_handle_missed_posts()

	private function verify_no_priv_nonce( $nonce ) {
		$nonce = (string) $nonce;
		if ( empty( $nonce ) ) {
			return false;
		}//end if

		$uid   = 'n/a';
		$token = 'n/a';
		$i     = wp_nonce_tick();

		// Nonce generated 0-12 hours ago.
		$expected = substr( wp_hash( $i . '|' . self::ACTION . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );
		if ( hash_equals( $expected, $nonce ) ) {
			return 1;
		}//end if

		// Nonce generated 12-24 hours ago.
		$expected = substr( wp_hash( ( $i - 1 ) . '|' . self::ACTION . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );
		if ( hash_equals( $expected, $nonce ) ) {
			return 2;
		}//end if

		return false;
	}//end verify_no_priv_nonce()

	private function get_no_priv_nonce() {
		$uid   = 'n/a';
		$token = 'n/a';
		$i     = wp_nonce_tick();
		return substr( wp_hash( $i . '|' . self::ACTION . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );
	}//end get_no_priv_nonce()

	private function can_handler_be_run() {
		$last_run = (int) get_option( self::OPTION_NAME, 0 );
		return $last_run < ( time() - $this->get_handler_running_frequency() );
	}//end can_handler_be_run()

	private function get_handler_running_frequency() {
		/**
		 * Filters the running frequency of the missed schedule handler.
		 *
		 * Controls the frequency in seconds of each execution of the missed
		 * schedule handler.
		 *
		 * @param int  $frequency  Running frequency in seconds.
		 *
		 * @since 2.5.1
		 */
		return (int) apply_filters( 'nelio_content_missed_schedule_handler_run_frequency', self::FREQUENCY );
	}//end get_handler_running_frequency()

	private function publish_missed_posts() {
		global $wpdb;

		update_option( self::OPTION_NAME, time() );

		$scheduled_ids = $wpdb->get_col( // phpcs:ignore
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_date <= %s AND post_status = 'future' LIMIT %d",
				current_time( 'mysql', 0 ),
				self::BATCH_LIMIT
			)
		);

		if ( ! count( $scheduled_ids ) ) {
			return;
		}//end if

		if ( count( $scheduled_ids ) === self::BATCH_LIMIT ) {
			update_option( self::OPTION_NAME, 0 );
		}//end if

		array_map( 'wp_publish_post', $scheduled_ids );
	}//end publish_missed_posts()

}//end class
