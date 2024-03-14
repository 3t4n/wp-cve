<?php

namespace WpifyWoo\Modules\DeliveryDates\Api;

use WP_REST_Response;
use WP_REST_Server;
use WpifyWoo\Plugin;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractRest;

/**
 * @property Plugin $plugin
 */
class DeliveryDatesApi extends AbstractRest {

	public function setup() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'delivery-dates-country',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'set_shipping_country' ),
					'permission_callback' => '__return_true',
					'args'                => array(
						'country' => array(
							'required' => true,
						),
					),
				),
			)
		);
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_REST_Response
	 */
	public function set_shipping_country( $request ): WP_REST_Response {

		if ( apply_filters( 'wpify_woo_delivery_dates_disable_change_country', false ) ) {
			return new WP_REST_Response( '', 204 );
		}

		$country = $request->get_param( 'country' );

		if ( empty(WC()->customer->get_billing_address())) {
			WC()->customer->set_billing_country( $country );
		}

		if ( empty(WC()->customer->get_shipping_address())) {
			WC()->customer->set_shipping_country( $country );
		}

		return new WP_REST_Response( array( 'country' => $country ), 200 );
	}
}
