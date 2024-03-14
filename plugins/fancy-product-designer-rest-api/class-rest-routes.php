<?php


if( !class_exists('FPD_Rest_Routes') ) {

	class FPD_Rest_Routes {

		const ROUTE_NAMESPACE = 'fpd/v1.1';

	 	public function __construct() {

		 	$version = '1.1';
		 	$namespace = 'fpd/v' . $version;

		 	$disable_default_cors = get_option('fpd_rest_disable_default_cors', false);
		 	if( !empty($disable_default_cors) ) {
			 	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		 	}

		 	add_filter( 'rest_pre_serve_request', array( &$this, 'set_cors_header'), 15 );

		 	require_once( 'routes/class-route-products.php' );
		 	require_once( 'routes/class-route-views.php' );
		 	require_once( 'routes/class-route-templates.php' );
		 	require_once( 'routes/class-route-ui-layouts.php' );
	 		require_once( 'routes/class-route-designs.php' );
	 		require_once( 'routes/class-route-options.php' );
	 		require_once( 'routes/class-route-orders.php' );
	 		require_once( 'routes/class-route-pricing-rules.php' );

		}

		public function set_cors_header($value) {

			$allowed_origins = array(
				'http://admin.fancyproductdesigner.com',
				'https://admin.fancyproductdesigner.com',
				'http://localhost:3000',
			);

			$origin = get_http_origin();
			if ( $origin && in_array( $origin, $allowed_origins ) ) {
				header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
				header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
				header( 'Access-Control-Allow-Credentials: true' );
			}

			return $value;

		}

	}

}

new FPD_Rest_Routes();

?>