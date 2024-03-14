<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_With_Rehub_Theme
 */
if ( ! class_exists( 'WFFN_Compatibility_With_Rehub_Theme' ) ) {
	class WFFN_Compatibility_With_Rehub_Theme {

		public function __construct() {
			add_action( 'wp', [ $this, 'register_elementor_widget' ], 150 );
		}

		public function is_enable() {
			if ( defined( 'RH_MAIN_THEME_VERSION' ) ) {
				return true;
			}

			return false;
		}

		public function register_elementor_widget() {

			if ( true !== $this->is_enable() || is_admin() ) {
				return;
			}

			global $post;
			if ( is_null( $post ) || ! in_array( $post->post_type, array(
					'wffn_landing',
					'wffn_ty',
					'wffn_optin',
					'wffn_oty',

				), true ) ) {
				return;
			}

			if ( class_exists( 'Elementor\Plugin' ) ) {
				if ( class_exists( 'WFFN_Optin_Pages_Elementor' ) ) {
					$op_instance = WFFN_Optin_Pages_Elementor::get_instance();
					$op_instance->register_widgets();
				}
				if ( class_exists( 'WFFN_ThankYou_WC_Pages_Elementor' ) ) {
					$ty_instance = WFFN_ThankYou_WC_Pages_Elementor::get_instance();
					$ty_instance->register_widgets();
				}
				if ( class_exists( 'WFFN_Pro_Optin_Pages_Elementor' ) ) {
					$op_pro_instance = WFFN_Pro_Optin_Pages_Elementor::get_instance();
					$op_pro_instance->register_widgets();
				}


			}
		}

	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Rehub_Theme(), 'rehub_theme' );
}