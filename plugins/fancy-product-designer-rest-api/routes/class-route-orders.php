<?php


if( !class_exists('FPD_Route_Orders') ) {

	class FPD_Route_Orders {

	 	public function __construct() {

	 		register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/order', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_all_orders'),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/order/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( &$this, 'get_single_order'),
				'permission_callback' => function () {
					return current_user_can( Fancy_Product_Designer::CAPABILITY );
				}
			) );

			register_rest_route( FPD_Rest_Routes::ROUTE_NAMESPACE, '/order/(?P<id>[\d]+)', array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( &$this, 'update_single_order'),
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

			add_filter( 'fpd_rest_single_order_data', array( &$this, 'single_order_data'), 10, 3 );

		}

		public function get_all_orders( WP_REST_Request $request ) {

			$response = FPD_Resource_Orders::get_all_orders( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );


		}

		public function get_single_order( WP_REST_Request $request ) {

			$response = FPD_Resource_Orders::get_single_order( $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		public function update_single_order( WP_REST_Request $request ) {

			$response = FPD_Resource_Orders::update_order( $request->get_param('id'), $request->get_params() );

			if( is_wp_error( $response ) )
				return new WP_REST_Response( array( 'message' => $response->get_error_message() ), 500 );
			else
				return new WP_REST_Response( $response, 200 );

		}

		//add italic and bold font set of custom fonts
		public function single_order_data( $response_data, $id, $order_type ) {

			if( !isset($response_data['order']) )
				return $response_data;

			$fonts_dir = FPD_WP_CONTENT_DIR.'/uploads/fpd_fonts/';
			//usedFonts in _fpd_data, used_fonts in _fpd_print_order
			$used_fonts = isset($response_data['order']->usedFonts) ? $response_data['order']->usedFonts : $response_data['order']->used_fonts;

			if( file_exists($fonts_dir) && !empty($used_fonts) ) {

				foreach($used_fonts as $used_font) {

					if( isset($used_font->url) && substr($used_font->url, 0, 4) == 'http') {

						$font_name = pathinfo($used_font->url);
						$font_name = $font_name['filename'];

						if( file_exists($fonts_dir.$font_name.'__bold.ttf') ) {

							$font_obj = array(
								'name' => $used_font->name . '__bold',
								'url' => content_url('/uploads/fpd_fonts/'.$font_name.'__bold.ttf')
							);

							isset($response_data['order']->usedFonts) ? array_push( $response_data['order']->usedFonts, (object) $font_obj ) : array_push( $response_data['order']->used_fonts, (object) $font_obj );

						}

						if( file_exists($fonts_dir.$font_name.'__italic.ttf') ) {

							$font_obj = array(
								'name' => $used_font->name . '__italic',
								'url' => content_url('/uploads/fpd_fonts/'.$font_name.'__italic.ttf')
							);

							isset($response_data['order']->usedFonts) ? array_push( $response_data['order']->usedFonts, (object) $font_obj ) : array_push( $response_data['order']->used_fonts, (object) $font_obj );

						}

						if( file_exists($fonts_dir.$font_name.'__bolditalic.ttf') ) {

							$font_obj = array(
								'name' => $used_font->name . '__bi',
								'url' => content_url('/uploads/fpd_fonts/'.$font_name.'__bolditalic.ttf')
							);

							isset($response_data['order']->usedFonts) ? array_push( $response_data['order']->usedFonts, (object) $font_obj ) : array_push( $response_data['order']->used_fonts, (object) $font_obj );

						}

					}

				}

			}

			return $response_data;

		}

	}

}

new FPD_Route_Orders();

?>