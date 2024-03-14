<?php


if( !class_exists('FPD_Route_Templates') ) {

	class FPD_Route_Templates {

	 	public function __construct() {

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/template', array(
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array( &$this, 'create_product_template'),
				'args' => array(
					'product_id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
					'title' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/template', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_product_templates'),
				'args' => array(
					'type' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/template/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( &$this, 'delete_product_template'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

		}

		public function create_product_template( WP_REST_Request $request ) {

			$response = FPD_Resource_Templates::create_product_template( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function get_product_templates( WP_REST_Request $request ) {

			$response = FPD_Resource_Templates::get_product_templates( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function delete_product_template( WP_REST_Request $request ) {

			$response = FPD_Resource_Templates::delete_product_template( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Templates();

?>