<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WCCT {
	public function __construct() {

		add_action( 'template_redirect', [ $this, 'am_before_sticky_bar_call' ], 1 );
		add_action( 'template_redirect', [ $this, 'am_after_sticky_bar_call' ], 3 );
		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'am_allow_finale_sticky_campaigns' ] );
	}

	public function am_before_sticky_bar_call() {
		add_filter( 'wcct_force_do_not_run_campaign', [ $this, 'am_force_campaign_run' ], 100, 2 );
	}

	public function am_after_sticky_bar_call() {
		remove_filter( 'wcct_force_do_not_run_campaign', 'am_force_campaign_run', 100 );
	}

	public function am_force_campaign_run() {
		return true;
	}

	public function am_allow_finale_sticky_campaigns() {
		if ( ! class_exists( 'WCCT_Appearance' ) ) {
			return;
		}
		$appearance = WCCT_Appearance::get_instance();
		if ( ! method_exists( $appearance, 'wcct_triggers_sticky_header_and_footer' ) ) {
			return;
		}

		if ( ! class_exists( 'wfacp_template' ) ) {
			return;
		}

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}


		if ( 'pre_built' == $instance->get_template_type() ) {
			remove_action( 'wp_footer', [ $appearance, 'wcct_triggers_sticky_header_and_footer' ], 50 );
			add_action( 'wfacp_internal_css', [ $appearance, 'wcct_triggers_sticky_header_and_footer' ], 50 );

		}


	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WCCT(), 'wcct' );

