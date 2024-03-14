<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_With_Divi_Theme
 */
if ( ! class_exists( 'WFFN_Compatibility_With_Divi_Theme' ) ) {
	class WFFN_Compatibility_With_Divi_Theme {

		public function __construct() {
			add_filter( 'et_builder_add_outer_content_wrap', array( $this, 'maybe_filter' ), 999 );
			add_filter( 'wffn_container_attrs', array( $this, 'add_id_for_wffn_container' ) );
		}

		public function is_enable() {
			if ( defined( 'ET_CORE_VERSION' ) ) {
				return true;
			}

			return false;
		}

		public function maybe_filter( $add_outer_wrap ) {

			global $post;

			if ( ! is_null( $post ) && in_array( $post->post_type, array(
					'wfacp_checkout',
					'wfocu_offer',
					'wffn_landing',
					'wffn_ty',
					'wffn_optin',
					'wffn_oty',

				), true ) ) {
				return true;
			}

			return $add_outer_wrap;
		}

		/**
		 * @param $attrs
		 *
		 * @return mixed
		 */
		public function add_id_for_wffn_container( $attrs ) {
			if ( ! $this->is_enable() ) {
				return $attrs;
			}
			$attrs['id'] = 'page-container';

			return $attrs;
		}

	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Divi_Theme(), 'divi_theme' );
}