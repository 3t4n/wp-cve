<?php


if( !class_exists('FPD_Route_Options') ) {

	class FPD_Route_Options {

	 	public function __construct() {

		 	//SINGLE OPTIONS
			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/option', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_single_options'),
				'args' => array(
					'keys' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_array($param) && !empty($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );


	 		//OPTIONS GROUP
	 		register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/options', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_options_group'),
				'args' => array(
					'group' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
					'lang_code' => array(
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

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/options', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_options_group'),
				'args' => array(
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			//LANGUAGES
			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/languages', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_languages'),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

		}

		public function get_single_options( WP_REST_Request $request ) {

			$response = FPD_Resource_Options::get_options( $request->get_param('keys') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );


		}

		public function get_options_group( WP_REST_Request $request ) {

			$response = FPD_Resource_Options::get_options_group( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_options_group( WP_REST_Request $request ) {

			$response = FPD_Resource_Options::update_options( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function get_languages( WP_REST_Request $request ) {

			$response = FPD_Resource_Options::get_languages();

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Options();

?>