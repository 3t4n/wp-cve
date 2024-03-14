<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Blocksy {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function action() {
		if ( ! $this->enable() ) {
			return;
		}
		add_filter( 'body_class', [ $this, 'unset_body_class' ], 9999999 );
		add_action( 'wp_enqueue_scripts', [ $this, 'remove_theme_style' ], 99 );
	}

	public function unset_body_class( $body_class ) {
		if ( is_array( $body_class ) && count( $body_class ) > 0 ) {

			$key = array_search( "ct-elementor-default-template", $body_class );

			if ( isset( $body_class[ $key ] ) ) {
				unset( $body_class[ $key ] );
			}
		}

		return $body_class;
	}

	public function remove_theme_style() {
		wp_dequeue_style( 'ct-woocommerce-styles' );
	}

	public function internal_css() {
		if ( ! $this->enable() ) {
			return;
		}

		echo '<style>';
		echo '.checkout_coupon p:first-child {display: block;}';
		echo 'form#checkout {display: block;}';
		echo '.payment_methods>li>label{height: auto;}';
		echo 'body #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon input#coupon_code{padding: 12px 10px;}';
		echo '.button:hover{transform: none;}';
		echo 'body #wfacp-e-form form.checkout_coupon.woocommerce-form-coupon .button{min-height: 50px;}';
//		echo 'body .wfacp_main_form.woocommerce input[type=radio]{border: 1px solid #b4b9be !important;background: #fff !important;}';
//		echo 'body .wfacp_main_form.woocommerce .woocommerce-form__input[type="checkbox"]:checked{border: 1px solid #b4b9be !important;background: #fff !important;}';
		echo 'body .wfacp_main_form.woocommerce  #payment .payment_methods>li>input[type="radio"]:first-child{ visibility: visible;}';
		echo '</style>';

	}

	public function enable() {
		return class_exists( 'Blocksy_Manager' );
	}

}



WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Blocksy(), 'wfacp-blocksy' );
