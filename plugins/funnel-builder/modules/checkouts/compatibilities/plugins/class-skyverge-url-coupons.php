<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_url_coupons_Sky_Verge {
	public function __construct() {

		add_filter( 'wc_url_coupons_url_matches_coupon', [ $this, 'disable_coupon_apply' ] );
		add_action( 'wfacp_changed_default_woocommerce_page', [ $this, 'skip_add_to_cart' ] );
		add_filter( 'wfacp_skip_add_to_cart', [ $this, 'skip_add_to_cart' ] );
	}

	public function skip_add_to_cart( $status ) {
		add_action( 'woocommerce_before_cart_emptied', [ $this, 'catch_applied_coupons' ] );
		add_action( 'wfacp_after_add_to_cart', [ $this, 're_apply_coupon_global' ], 10 );

		return $status;
	}

	private function is_enabled() {
		return function_exists( 'wc_url_coupons' );
	}

	public function catch_applied_coupons() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->coupons = WC()->cart->applied_coupons;
	}

	public function re_apply_coupon_global() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		if ( ! empty( $this->coupons ) ) {

			foreach ( $this->coupons as $coupon ) {
				WC()->cart->add_discount( $coupon );
			}
			wc_clear_notices();
		}
	}


	public function disable_coupon_apply( $url_match ) {
		add_action( 'wp', [ $this, 're_apply_coupon' ], 10 );

		return false;
	}


	public function re_apply_coupon() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		remove_filter( 'wc_url_coupons_url_matches_coupon', [ $this, 'disable_coupon_apply' ] );
		if ( ! is_null( wc_url_coupons()->get_frontend_instance() ) ) {
			wc_url_coupons()->get_frontend_instance()->maybe_apply_coupon();
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_url_coupons_Sky_Verge(), 'url_coupon_sky_verge' );
