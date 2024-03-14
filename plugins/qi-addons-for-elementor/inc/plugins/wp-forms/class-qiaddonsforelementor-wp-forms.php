<?php

if ( ! class_exists( 'QiAddonsForElementor_Wp_Forms' ) ) {
	class QiAddonsForElementor_Wp_Forms {
		private static $instance;

		public function __construct() {

			if ( qi_addons_for_elementor_framework_is_installed( 'wp_forms' ) ) {
				// Include files
				$this->include_files();
			}
		}

		/**
		 * @return QiAddonsForElementor_Wp_Forms
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function include_files() {

			// Include helper functions
			include_once QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/wp-forms/helper.php';

			// Include shortcodes
			add_action(
				'qi_addons_for_elementor_action_framework_before_shortcodes_register',
				array(
					$this,
					'include_shortcodes',
				)
			);

			// Override templates
			$this->override_templates();
		}

		function include_shortcodes() {
			foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/wp-forms/shortcodes/*/include.php' ) as $shortcode ) {
				include_once $shortcode;
			}
		}

		function override_templates() {
		}
	}

	QiAddonsForElementor_Wp_Forms::get_instance();
}
