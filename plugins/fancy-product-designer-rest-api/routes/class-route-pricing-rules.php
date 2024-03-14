<?php


if( !class_exists('FPD_Route_Pricing_Rules') ) {

	class FPD_Route_Pricing_Rules {

	 	public function __construct() {

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/pricing_rules', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_pricing_rules'),

				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/pricing_rules', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_pricing_rules'),
				'args' => array(
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

		}

		public function get_pricing_rules( WP_REST_Request $request ) {

			$response = FPD_Resource_Pricing_Rules::get_pricing_rules();

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );


		}

		public function update_pricing_rules( WP_REST_Request $request ) {

			$response = FPD_Resource_Pricing_Rules::update_pricing_rules( $request->get_param('groups') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Pricing_Rules();

?>