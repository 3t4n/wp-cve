<?php

/**
 * Register Country API
 *
 * @link       https://appcheap.io
 * @since      1.0.21
 * @author     ngocdt
 */

namespace AppBuilder\Api;

use stdClass;

defined( 'ABSPATH' ) || exit;

class Country {
	protected $namespace;

	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
	}

	/**
	 * Registers a REST API route
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		/**
		 * Update customer
		 *
		 * @author Ngoc Dang
		 * @since 1.0.21
		 */
		if ( class_exists( '\WC_Countries' ) ) {
			/**
			 * @since 1.0.21
			 */
			register_rest_route(
				$this->namespace,
				'get-country-locale',
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_country_locale' ),
					'permission_callback' => '__return_true',
				)
			);

			/**
			 * @since 1.0.21
			 */
			register_rest_route(
				$this->namespace,
				'address',
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'address' ),
					'permission_callback' => '__return_true',
				)
			);
		}
	}

	/**
	 * Get country locale settings.
	 *
	 * @param $request
	 *
	 * @return \WP_REST_Response
	 */
	public function get_country_locale( $request ): \WP_REST_Response {
		$obj       = new \WC_Countries();
		$countries = $obj->get_country_locale();

		$countries = apply_filters( 'app_builder_prepare_address_fields_response', $countries );

		return new \WP_REST_Response( $countries, 200 );
	}

	/**
	 * Get address form configs.
	 *
	 * @param $request
	 *
	 * @return \WP_REST_Response
	 */
	public function address( $request ): \WP_REST_Response {
		$obj = new \WC_Countries();

		$country = $request->get_param( 'country' );
		if ( ! $country ) {
			$country = $obj->get_base_country();
		}

		$_POST['billing_country']  = $country;
		$_POST['shipping_country'] = $country;
		$checkout                  = new \WC_Checkout();

		$fields = $checkout->get_checkout_fields();

		return new \WP_REST_Response(
			array(
				'country'                     => $country,
				'billing'                     => $fields['billing'],
				'shipping'                    => $fields['shipping'],
				'address_format'              => $obj->get_address_formats(),
				'billing_countries_selected'  => get_option( 'woocommerce_allowed_countries' ),
				'billing_countries'           => $obj->get_allowed_countries(),
				'billing_countries_states'    => $obj->get_allowed_country_states(),
				'shipping_countries_selected' => get_option( 'woocommerce_ship_to_countries' ),
				'shipping_countries'          => $obj->get_shipping_countries(),
				'shipping_country_states'     => $obj->get_shipping_country_states(),
				'additional'                  => isset( $fields['additional'] ) ? $fields['additional'] : new stdClass(),
			),
			200
		);
	}
}
