<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Conditions_Archive_All' ) ) {

	/**
	 * Define Soft_template_Core_Conditions_Archive_All class
	 */
	class Soft_template_Core_Conditions_Archive_All extends Soft_template_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-all';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'All Archives', 'soft-template-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'archive';
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {
			return is_archive() || is_home() && 'posts' == get_option( 'show_on_front' );
		}

	}

}