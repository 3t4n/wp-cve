<?php


if( !class_exists('FPD_Route_Products') ) {

	class FPD_Route_Products {

	 	public function __construct() {

	 		//PRODUCTS
	 		register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_products'),
				'args' => array(
					'include_views' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param);
						}
					),
					'cols' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param);
						}
					),
					'filter_by' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param);
						}
					),
					'sort_by' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param);
						}
					),
					'page' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param);
						}
					),
					'limit' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param);
						}
					),
					'search' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param);
						}
					),
					'category_id' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product', array(
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array( &$this, 'create_single_product'),
				'args' => array(
					'title' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
					//create product from Templates Library
					'zip_path' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
					'add_to_media_lib' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_bool($param);
						}
					),
					//create product from my templates
					'template_id' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
					//duplicate product
					'duplicate_product_id' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_single_product'),
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

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_single_product'),
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
					//add product view
					'view_title' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
					'duplicate_view_id' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
					//update order of views in product
					'view_ids' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {

							if( is_array($param) )
								return true;

							json_decode($param);
							return (json_last_error() == JSON_ERROR_NONE);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( &$this, 'delete_single_product'),
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

			//PRODUCT CATEGORIES
	 		register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product_category', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_all_product_categories'),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product_category', array(
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array( &$this, 'create_product_category'),
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

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product_category/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_product_category'),
				'args' => array(
					'id' => array(
						'required' => true,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
					//category title
					'title' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_string($param) && !empty($param);
						}
					),
					//product assignment
					'product_id' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_numeric($param) && !empty($param);
						}
					),
					'assign' => array(
						'required' => false,
						'validate_callback' => function($param, $request, $key) {
							return is_bool($param);
						}
					),
				),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/product_category/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( &$this, 'delete_product_category'),
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

		public function get_products( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::get_products( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function create_single_product( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::create_product( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_single_product( WP_REST_Request $request ) {

			$product_id = $request->get_param('id');

			$response = FPD_Resource_Products::update_product( $product_id, $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function delete_single_product( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::delete_product( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function get_all_product_categories( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::get_all_product_categories( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function create_product_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::create_product_category( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_product_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::update_product_category( $request->get_param('id'), $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function delete_product_category( WP_REST_Request $request ) {

			$response = FPD_Resource_Products::delete_product_category( $request->get_param('id') );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

	}

}

new FPD_Route_Products();

?>