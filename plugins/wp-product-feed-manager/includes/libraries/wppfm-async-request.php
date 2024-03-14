<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract WPPFM_Async_Request class derived from https://github.com/A5hleyRich/wp-background-processing.
 *
 * @package WPPFM-Background-Processing
 * @abstract
 */
abstract class WPPFM_Async_Request {

	/**
	 * Prefix
	 *
	 * (default value: 'wppfm')
	 *
	 * @var string
	 * @access protected
	 */
	protected $prefix = 'wppfm';

	/**
	 * Action
	 *
	 * (default value: 'async_request')
	 *
	 * @var string
	 */
	protected $action = 'async_request';

	/**
	 * Identifier
	 *
	 * @var mixed
	 */
	protected $identifier;

	/**
	 * Data
	 *
	 * (default value: array())
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * File Path
	 *
	 * (default value: empty string)
	 *
	 * @var string
	 */
	protected $file_path = '';

	/**
	 * Contains the general data of the feed
	 *
	 * (default value: empty string)
	 *
	 * @var string
	 */
	protected $feed_data = '';

	/**
	 * Contains general pre feed production data
	 *
	 * @var array
	 */
	protected $pre_data;

	/**
	 * Contains the channels category title and description title
	 *
	 * @var array
	 */
	protected $channel_details;

	/**
	 * Contains the relations between the WooCommerce and channel fields
	 *
	 * @var array
	 */
	protected $relations_table;

	/**
	 * Initiate new async request
	 */
	public function __construct() {
		$this->identifier = $this->prefix . '_' . $this->action;

		add_action( 'wp_ajax_' . $this->identifier, array( $this, 'maybe_handle' ) ); // This is the wppfm_feed_generation_process action used to start the background process using a wp_remote_post call.
		add_action( 'wp_ajax_nopriv_' . $this->identifier, array( $this, 'maybe_handle' ) );
	}

	/**
	 * Set data used during the request
	 *
	 * @param array $data Data.
	 *
	 * @return $this
	 */
	public function data( $data ) {
		$this->data = $data;

		return $this;
	}

	/**
	 * Dispatch the async request to trigger the feed process with a remote post.
	 *
	 * @param string $feed_id
	 */
	public function dispatch( $feed_id ) {
		if ( get_option( 'wppfm_disabled_background_mode', 'false' ) === 'false' ) { // start a background process
			$url  = add_query_arg( $this->get_query_args(), $this->get_query_url() );
			$args = $this->get_post_args();

			do_action( 'wppfm_register_remote_post_args', $feed_id, $url, $args );

			// activate the background process
			$response = wp_remote_post( esc_url_raw( $url ), $args );

			do_action( 'wppfm_wp_remote_post_response', $feed_id, $response );

		} else { // start a foreground process
			$this->maybe_handle();
		}
	}

	/**
	 * Get query args
	 *
	 * @return array
	 */
	protected function get_query_args() {
		return array(
			'action' => $this->identifier,
			'nonce'  => wp_create_nonce( $this->identifier ),
		);
	}

	/**
	 * Get query URL
	 *
	 * @return string
	 */
	protected function get_query_url() {
		return admin_url( 'admin-ajax.php' );
	}

	/**
	 * Get post args
	 *
	 * @return array
	 */
	protected function get_post_args() {
		// @since 2.41.0. Switch blocking = true if the logger functionality is enabled. This will enable a response from a wp_remote_post request.
		$blocking = false;

		if ( 'true' === get_option( 'wppfm_process_logger_status', 'false' ) && wppfm_logger_prerequisites() ) {
			$blocking = true;
		}

		return array(
			'timeout'  => 5,
			'blocking' => $blocking,
			'body'     => json_encode( $this->data ), // encoding @since 2.41.0 to minimize the post input var size.
			'cookies'  => stripslashes_deep( $_COOKIE ),
		);
	}

	/**
	 * Maybe handle
	 *
	 * Check for correct nonce and pass to handler.
	 */
	public function maybe_handle() {
		// Don't lock up other requests while processing
		session_write_close();

		check_ajax_referer( $this->identifier, 'nonce' );

		$this->handle();

		wp_die();
	}

	/**
	 * Handle
	 *
	 * Override this method to perform any actions required
	 * during the async request.
	 */
	abstract protected function handle();
}
