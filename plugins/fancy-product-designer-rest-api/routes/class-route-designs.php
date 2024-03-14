<?php


if( !class_exists('FPD_Route_Designs') ) {

	class FPD_Route_Designs {

	 	public function __construct() {


	 		//DESIGN CATEGORIES
	 		register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/design_category', array(
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array( &$this, 'create_design_category'),
				'args' => array(
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

	 		register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/design_category', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_design_categories'),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/design_category/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_single_category'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					)
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/design_category/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_design_category'),
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
							return is_string($param);
						}
					),
					'designs' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_array($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/design_category/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( &$this, 'delete_design_category'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					)
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

		}

		public function create_design_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Designs::create_design_category( $request->get_param('title') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function get_design_categories( WP_REST_Request $request ) {

			$response = FPD_Resource_Designs::get_design_categories();

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function get_single_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Designs::get_single_category( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_design_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Designs::update_design_category( $request->get_param('id'), $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function delete_design_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Designs::delete_design_category( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Designs();

?>