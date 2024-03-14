<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_With_UX_Builder
 */
if ( ! class_exists( 'WFFN_Compatibility_With_UX_Builder' ) ) {
	class WFFN_Compatibility_With_UX_Builder {

		public function __construct() {
			add_action( 'admin_init', array( $this, 'maybe_filter' ) );

		}

		public function is_enable() {
			if ( function_exists( 'add_ux_builder_post_type' ) ) {
				return true;
			}

			return false;
		}

		public function maybe_filter() {
			if ( function_exists( 'add_ux_builder_post_type' ) ) {
				add_ux_builder_post_type( 'wfacp_checkout' );
				add_ux_builder_post_type( 'wfocu_offer' );
				add_ux_builder_post_type( 'wffn_landing' );
				add_ux_builder_post_type( 'wffn_ty' );
				add_ux_builder_post_type( 'wffn_optin' );
				add_ux_builder_post_type( 'wffn_oty' );
			}
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_UX_Builder(), 'ux_builder' );
}

