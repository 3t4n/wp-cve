<?php

/**
 * #[AllowDynamicProperties] 

  class WFACP_WC_MemberShip   WooCommerce Memberships By SkyVerge
 * Page Redirect in ajax when coupon apply
 *
 *
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_MemberShip {
	public function __construct() {
		add_filter( 'pre_option_wc_memberships_redirect_page_id', [ $this, 'send_null_page_id' ] );
		add_filter( 'wp_redirect_status', [ $this, 'remove_redirect_action' ] );

		add_action( 'wp_loaded', [ $this, 'remove_action' ] );
	}

	public function remove_redirect_action( $status ) {
		if ( isset( $_REQUEST['wc-ajax'] ) && false !== strpos( $_REQUEST['wc-ajax'], 'wfacp' ) ) {
			$status = false;
		}

		return $status;
	}

	public function send_null_page_id( $status ) {
		if ( isset( $_REQUEST['wc-ajax'] ) && false !== strpos( $_REQUEST['wc-ajax'], 'wfacp' ) ) {
			$status = null;
		}

		return $status;
	}

	public function remove_action() {
		if ( class_exists( 'WC_Memberships_Frontend', false ) && isset( $_POST['wfacp_login_hidden'] ) && wc_string_to_bool( $_POST['wfacp_login_hidden'] ) == true ) {
			WFACP_Common::remove_actions( 'woocommerce_login_redirect', 'WC_Memberships_Frontend', 'redirect_to_page_upon_woocommerce_login' );
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_MemberShip(), 'wc_membership' );
