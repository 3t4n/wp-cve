<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Przelewy24 Payment Gateway
 * http://www.przelewy24.pl/pobierz
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_P24
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_P24 {
	protected $is_enabled = false;

	public function __construct() {
		$this->remove_p24_hook();
	}

	public function remove_p24_hook() {
		if ( WFACP_Common::is_theme_builder() ) {
			WFACP_Common::remove_actions( 'woocommerce_available_payment_gateways', 'P24_Core', 'inject_additional_gateways' );
		}
	}

	public static function is_enable() {
		return function_exists( 'woocommerce_p24_init' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_P24(), 'p24' );

