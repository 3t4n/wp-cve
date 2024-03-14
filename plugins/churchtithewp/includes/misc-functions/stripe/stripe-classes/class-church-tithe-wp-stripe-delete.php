<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This is the class we use to interact with the Stripe API for DELETE requests.
 *
 * @since       1.0.0.
 * @return      array
 */
class Church_Tithe_WP_Stripe_Delete {
	/**
	 * The headers that will be sent with the DELETE request to Stripe
	 *
	 * @since  1.0
	 * @var array
	 */
	public $headers;

	/**
	 * The default URL endpoint at Stripe
	 *
	 * @since  1.0
	 * @var string
	 */
	public $url = 'https://api.stripe.com/v1';

	/**
	 * The fields which will be sent in the DELETE request to Stripe
	 *
	 * @since  1.0
	 * @var array
	 */
	public $fields = array();

	/**
	 * The secret key which will be used for the call to Stripe.
	 *
	 * @since  1.0
	 * @var string
	 */
	public $secret_key = '';

	/**
	 * Set up the call to Stripe
	 *
	 * @since   1.0.0
	 * @param   array $args The args to pass to wp_remote_post.
	 */
	public function __construct( $args ) {

		// If we are forcing a live/test mode.
		if ( isset( $args['force_mode'] ) ) {
			$mode = ! empty( $args['force_mode'] ) ? $args['force_mode'] : false;
		} else {
			$mode = false;
		}

		// If there isn't any secret key, do not execute the call to Stripe.
		$this->secret_key = church_tithe_wp_get_stripe_secret_key( $mode );

		if ( empty( $this->secret_key ) ) {
			return array();
		}

		$this->headers = array(
			'Authorization' => 'Bearer ' . $this->secret_key,
		);

		if ( isset( $args['fields'] ) ) {
			$this->fields = $args['fields'];
		}

		if ( isset( $args['url'] ) ) {
			$this->url = $args['url'];
		}
	}

	/**
	 * Execute the call to Stripe
	 *
	 * @since   1.0.0
	 * @return  array The response from Stripe.
	 */
	public function call() {

		if ( empty( $this->secret_key ) ) {
			return array();
		}

		$response = wp_remote_retrieve_body(
			wp_remote_post(
				$this->url,
				array(
					'method'  => 'DELETE',
					'headers' => $this->headers,
					'body'    => $this->fields,
				)
			)
		);

		return json_decode( $response, true );
	}
}
