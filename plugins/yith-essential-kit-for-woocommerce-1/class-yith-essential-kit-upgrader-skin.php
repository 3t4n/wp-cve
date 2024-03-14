<?php
/**
 * Override default WP class
 *
 * @package YITH Essential Kit for Woocommerce #1
 */

if ( ! class_exists( 'YITH_Essential_Kit_Upgrader_Skin' ) ) {
	/**
	 * Upgrader class
	 */
	class YITH_Essential_Kit_Upgrader_Skin extends WP_Upgrader_Skin {
		/**
		 * Header function override
		 *
		 * @return void
		 */
		public function header() {
		}

		/**
		 * Footer function override
		 *
		 * @return void
		 */
		public function footer() {
		}

		/**
		 * Feedback function override
		 *
		 * @param string $string feedback string.
		 * @param array  ...$args Various args array.
		 * @return void
		 */
		public function feedback( $string, ...$args ) {
		}

		/**
		 * Errror function override
		 *
		 * @param string $string feedback string.
		 *
		 * @return void
		 */
		public function error( $string ) {
		}
	}
}
