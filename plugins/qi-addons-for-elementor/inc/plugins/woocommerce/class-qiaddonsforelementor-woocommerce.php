<?php

if ( ! class_exists( 'QiAddonsForElementor_WooCommerce' ) ) {
	class QiAddonsForElementor_WooCommerce {
		private static $instance;

		public function __construct() {

			if ( qi_addons_for_elementor_framework_is_installed( 'woocommerce' ) ) {
				// Include files
				$this->include_files();
			}
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function include_files() {

			// Include helper functions
			include_once QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/helper.php';

			// Include shortcodes
			add_action( 'qi_addons_for_elementor_action_framework_before_shortcodes_register', array( $this, 'include_shortcodes' ) );

			// Include plugin addons
			foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/plugins/*/include.php' ) as $plugin ) {
				include_once $plugin;
			}
		}

		function include_shortcodes() {
			foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/shortcodes/*/include.php' ) as $shortcode ) {
				include_once $shortcode;
			}
		}
	}

	QiAddonsForElementor_WooCommerce::get_instance();
}
