<?php


if( !class_exists('FPD_Route_Ui_Layouts') ) {

	class FPD_Route_Ui_Layouts {


	 	public function __construct() {

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/ui_layout', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_ui_layouts'),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/ui_layout', array(
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array( &$this, 'create_ui_layout'),
				'args' => array(

				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/ui_layout/(?P<id>[\S]+)', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_ui_layout'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					)
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/ui_layout/(?P<id>[\S]+)', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_ui_layout'),
				'args' => array(
					'id' => array(
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

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/ui_layout/(?P<id>[\S]+)', array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( &$this, 'delete_ui_layout'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					)
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

		}


		public function get_ui_layouts( WP_REST_Request $request ) {

			$response = FPD_Resource_UI_Layouts::get_ui_layouts();

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function create_ui_layout( WP_REST_Request $request ) {

			$response = FPD_Resource_UI_Layouts::create_ui_layout( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function get_ui_layout( WP_REST_Request $request ) {

			$response = FPD_Resource_UI_Layouts::get_ui_layout( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_ui_layout( WP_REST_Request $request ) {

			$response = FPD_Resource_UI_Layouts::update_ui_layout( $request->get_param('id') , $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function delete_ui_layout( WP_REST_Request $request ) {

			$response = FPD_Resource_UI_Layouts::delete_ui_layout( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Ui_Layouts();

?>