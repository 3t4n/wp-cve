<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_With_Bricks_Theme
 */
if ( ! class_exists( 'WFFN_Compatibility_With_Bricks_Theme' ) ) {
	class WFFN_Compatibility_With_Bricks_Theme {

		public function __construct() {
			add_filter( 'wffn_optin_page_id', array( $this, 'maybe_optin_page_id' ), 10, 1 );
			add_action( 'wflp_page_design_updated', array( $this, 'update_page_template' ), 99, 1 );
			add_action( 'wfop_page_design_updated', array( $this, 'update_page_template' ), 99, 1 );
			add_action( 'wfoty_page_design_updated', array( $this, 'update_page_template' ), 99, 1 );
			add_action( 'wfty_page_design_updated', array( $this, 'update_page_template' ), 99, 1 );
		}

		public function is_enable() {
			if ( function_exists( 'bricks_is_builder' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * @param $post_types
		 *
		 * @return mixed
		 */
		public function maybe_optin_page_id( $page_id ) {
			if ( true !== $this->is_enable() ) {
				return $page_id;
			}
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'bricks_get_element_html' && isset( $_POST['postId'] ) ) {
				return $_POST['postId'];
			}

			return $page_id;
		}

		/**
		 * Set default template when bricks theme activated
		 * @param $page_id
		 *
		 * @return void
		 */
		public function update_page_template( $page_id ) {
			if ( true === $this->is_enable() && 'bricks' === get_template() ) {
				update_post_meta( $page_id, '_wp_page_template', '' );
			}
		}

	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Bricks_Theme(), 'bricks_theme' );
}

