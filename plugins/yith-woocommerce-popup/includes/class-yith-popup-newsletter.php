<?php
/**
 * Main class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Popup
 * @version 1.0.0
 */

if ( ! defined( 'YITH_YPOP_INIT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_Popup_Newsletter' ) ) {
	/**
	 * YITH WooCommerce Popup main class
	 *
	 * @since 1.0.0
	 */
	class YITH_Popup_Newsletter {
		/**
		 * Single instance of the class
		 *
		 * @var \YITH_Popup_Newsletter
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_Popup_Newsletter
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

		}


		/**
		 * Return the integration
		 *
		 * @return mixed|void
		 */
		public function get_integration() {
			$integration_types = array(
				'custom' => __( 'Custom Form', 'yit' ),
			);

			// let custom integration to appear in integration type select field.
			$integration_types = apply_filters( 'yith-popup-newsletter-integration-type', $integration_types ); //phpcs:ignore

			return $integration_types;
		}

	}

	/**
	 * Unique access to instance of YITH_Popup class
	 *
	 * @return \YITH_Popup_Newsletter
	 */
	function YITH_Popup_Newsletter() { //phpcs:ignore
		return YITH_Popup_Newsletter::get_instance();
	}

	YITH_Popup_Newsletter();
}

