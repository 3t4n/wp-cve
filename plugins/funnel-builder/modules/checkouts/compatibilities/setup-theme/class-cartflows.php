<?php

if ( ! class_exists( 'WFACP_CartFlows_Compatibility' ) ) {


	#[AllowDynamicProperties] 

  class WFACP_CartFlows_Compatibility {
		public function __construct() {
			$this->remove_template_redirect();
			add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_render_cart_flows_inline_js' ] );
			add_filter( 'wfacp_skip_checkout_page_detection', [ $this, 'disable_aero_checkout_on_cart_flows_template' ] );

			add_action( 'wp', [ $this, 'check_global_setting' ], - 1 );

		}

		public function check_global_setting() {

			if ( ! class_exists( 'WFACP_Core' ) ) {
				return;
			}


			if ( ! class_exists( 'Cartflows_Helper' ) ) {
				return;
			}
			$g_setting = get_option( '_wfacp_global_settings', [] );


			if ( ! is_array( $g_setting ) || ! isset( $g_setting['override_checkout_page_id'] ) || $g_setting['override_checkout_page_id'] == "0" ) {
				return;
			}


			$setting = Cartflows_Helper::get_admin_settings_option( '_cartflows_common', false, false );


			if ( ! is_array( $setting ) || ! isset( $setting['override_global_checkout'] ) || 'enable' !== $setting['override_global_checkout'] ) {
				return;
			}
			WFACP_Common::remove_actions( 'wp', 'Cartflows_Global_Checkout', 'override_global_checkout' );


		}

		public function remove_template_redirect() {
			WFACP_Common::remove_actions( 'template_redirect', 'Cartflows_Checkout_Markup', 'global_checkout_template_redirect' );
		}

		public function remove_render_cart_flows_inline_js() {
			if ( ! class_exists( 'Cartflows_Tracking' ) ) {
				return;
			}
			WFACP_Common::remove_actions( 'wp_head', 'Cartflows_Tracking', 'wcf_render_gtag' );
		}

		public function disable_aero_checkout_on_cart_flows_template( $status ) {
			global $post;
			if ( $post instanceof WP_Post && 'cartflows_step' === $post->post_type ) {
				return true;
			}

			return $status;
		}
	}

	return new WFACP_CartFlows_Compatibility();
}
