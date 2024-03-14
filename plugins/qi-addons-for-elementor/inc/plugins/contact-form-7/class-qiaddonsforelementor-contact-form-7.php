<?php

if ( ! class_exists( 'QiAddonsForElementor_Contact_Form_7' ) ) {
	class QiAddonsForElementor_Contact_Form_7 {
		private static $instance;

		public function __construct() {

			if ( qi_addons_for_elementor_framework_is_installed( 'contact_form_7' ) ) {
				// Include files
				$this->include_files();
			}
		}

		/**
		 * @return QiAddonsForElementor_Contact_Form_7
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function include_files() {

			// Include helper functions
			include_once QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/contact-form-7/helper.php';

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
			foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/contact-form-7/shortcodes/*/include.php' ) as $shortcode ) {
				include_once $shortcode;
			}
		}

		function override_templates() {
			// Remove <p> and <br/> from Contact Form 7
			add_filter( 'wpcf7_autop_or_not', '__return_false' );
		}
	}

	QiAddonsForElementor_Contact_Form_7::get_instance();
}
