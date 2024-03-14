<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Generates routes in REST API and handles their authorization.
 */
class EndpointIntegrator implements Hookable {

	const ROUTE_NAMESPACE    = 'flexible-wishlist/v1';
	const ROUTE_NONCE_PARAM  = '_wpnonce';
	const ROUTE_NONCE_ACTION = 'flexible-wishlist-%';

	/**
	 * @var Endpoint
	 */
	private $endpoint;

	public function __construct( Endpoint $endpoint ) {
		$this->endpoint = $endpoint;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'rest_api_init', [ $this, 'register_rest_route' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function register_rest_route() {
		register_rest_route(
			self::ROUTE_NAMESPACE,
			$this->endpoint::get_route_name(),
			[
				'methods'             => $this->endpoint->get_route_method_type(),
				'permission_callback' => function ( \WP_REST_Request $request ) {
					return wp_verify_nonce( $request->get_param( self::ROUTE_NONCE_PARAM ), 'wp_rest' );
				},
				'callback'            => [ $this->endpoint, 'get_route_response' ],
				'args'                => $this->endpoint->get_route_args(),
			]
		);
	}
}
