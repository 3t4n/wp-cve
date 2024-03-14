<?php

/**
 * WooCommerce - Social Login
 * https://wpwebelite.com/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_Woo_Social_login {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
	}

	public function action() {
		global $woo_slg_options;
		if ( class_exists( 'WOO_Slg_Public' ) && ! is_null( $woo_slg_options ) ) {
			if ( $woo_slg_options['woo_slg_social_btn_position'] == 'top' ) {
				add_action( 'woocommerce_before_checkout_form', [ $this, 'print_social_login' ], 9 );
			} else {
				add_action( 'woocommerce_before_checkout_form', [ $this, 'print_social_login' ], 11 );
			}
		}
	}

	public function print_social_login() {
		if ( ! is_user_logged_in() ) {
			global $woo_slg_render;
			if ( ! is_null( $woo_slg_render ) ) {
				$woo_slg_render->woo_slg_social_login_buttons( 'checkout/form-login.php' );
			}
		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Woo_Social_login(), 'woo_social_login' );
