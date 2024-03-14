<?php

namespace QuadLayers\WOOCCM\Controller;

/**
 * Controller Class
 */
class Controller {

	protected function error_ajax( $data = null ) {
		return wp_send_json_error( $data );
	}

	protected function success_ajax( $data = null ) {
		return wp_send_json_success( $data );
	}

	protected function error_reload_page() {
		return wp_send_json_error( esc_html__( 'Please, reload page', 'woocommerce-checkout-manager' ) );
	}

	protected function error_access_denied() {
		return wp_send_json_error( esc_html__( 'Access denied', 'woocommerce-checkout-manager' ) );
	}
}
