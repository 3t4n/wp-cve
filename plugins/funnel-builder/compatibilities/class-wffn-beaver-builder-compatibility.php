<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_With_Beaver_Builder
 */
if ( ! class_exists( 'WFFN_Compatibility_With_Beaver_Builder' ) ) {
	class WFFN_Compatibility_With_Beaver_Builder {

		public function __construct() {
			add_filter( 'fl_builder_post_types', array( $this, 'post_types' ) );
			add_filter( 'wffn_set_selected_template_on_duplicate', array( $this, 'set_default_template' ), 10, 2 );
		}

		public function is_enable() {
			if ( class_exists( 'FLBuilderLoader' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * @param $post_types
		 *
		 * @return mixed
		 */
		public function post_types( $post_types ) {

			$wffn_posts = array(
				'wfacp_checkout',
				'wfocu_offer',
				'wffn_landing',
				'wffn_ty',
				'wffn_optin',
				'wffn_oty',
			);
			$post_types = array_merge( $post_types, $wffn_posts );

			return $post_types;
		}

		public function set_default_template( $template, $post_id ) {
			if ( true !== $this->is_enable() ) {
				return $template;
			}
			if ( false !== strpos( get_post_field( 'post_content', $post_id ), '<!-- wp:fl-builder' ) ) {
				return array(
					'selected'        => 'wp_editor_1',
					'selected_type'   => 'wp_editor',
					'template_active' => 'yes'
				);
			}

			return $template;
		}
	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Beaver_Builder(), 'beaver_builder' );
}

