<?php


if( !class_exists('FPD_Route_Views') ) {

	class FPD_Route_Views {

	 	public function __construct() {

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/view/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_product_view'),
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

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/view/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_product_view'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
					'title' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
					'options' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {

							if( is_array($param) )
								return true;

							json_decode($param);
							return (json_last_error() == JSON_ERROR_NONE);
						}
					),
					'thumbnail' => array(
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

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/view/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( &$this, 'delete_product_view'),
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

		public function get_product_view( WP_REST_Request $request ) {

			$response = FPD_Resource_Views::get_product_view( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_product_view( WP_REST_Request $request ) {

			$response = FPD_Resource_Views::update_product_view( $request->get_param('id'), $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function delete_product_view( WP_REST_Request $request ) {

			$response = FPD_Resource_Views::delete_product_view( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Views();

?>