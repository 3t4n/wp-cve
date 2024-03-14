<?php
/**
 * Settings page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Dashboard_Settings' ) ) {

	/**
	 * Define Soft_template_Core_Dashboard_Settings class
	 */
	class Soft_template_Core_Dashboard_Settings extends Soft_template_Core_Dashboard_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'settings';
		}

		/**
		 * Get icon
		 *
		 * @return string
		 */
		public function get_icon() {
			return 'dashicons dashicons-admin-settings';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_attr__( 'Settings', 'soft-template-core' );
		}

		/**
		 * Disable builder instance initialization
		 *
		 * @return bool
		 */
		public function use_builder() {
			return false;
		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {
			soft_template_core()->settings->render_page();
		}

		public function save_settings( $data ) {
			soft_template_core()->settings->save( $data );
		}

	}

}
