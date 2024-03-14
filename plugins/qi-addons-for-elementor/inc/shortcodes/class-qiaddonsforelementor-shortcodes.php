<?php

if ( ! class_exists( 'QiAddonsForElementor_Shortcodes' ) ) {
	class QiAddonsForElementor_Shortcodes {
		private static $instance;
		private $allowed_shortcodes = array();

		public function __construct() {

			// Set properties value
			$this->set_enabled_shortcodes();

			// Include shortcode abstract classes
			add_action( 'qi_addons_for_elementor_action_framework_before_shortcodes_register', array( $this, 'include_shortcode_classes' ), 5 );

			// Include shortcodes
			add_action( 'qi_addons_for_elementor_action_framework_before_shortcodes_register', array( $this, 'include_shortcodes' ) );

			// Register shortcodes
			add_action( 'qi_addons_for_elementor_action_framework_before_shortcodes_register', array( $this, 'register_shortcodes' ), 11 ); // Priority 11 set because include of files is called on default action 10
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function get_allowed_shortcodes() {
			return $this->allowed_shortcodes;
		}

		public function set_allowed_shortcodes( $allowed_shortcodes ) {
			$this->allowed_shortcodes[] = $allowed_shortcodes;
		}

		function set_enabled_shortcodes() {

			foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/*', GLOB_ONLYDIR ) as $shortcode ) {
				$this->set_allowed_shortcodes( $shortcode );
			}
		}

		function include_shortcode_classes() {
			include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/class-qiaddonsforelementor-shortcode.php';
			include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/class-qiaddonsforelementor-list-shortcode.php';
			include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/class-qiaddonsforelementor-slider-shortcode.php';
		}

		function include_shortcodes() {
			$shortcodes = $this->get_allowed_shortcodes();

			$additional_shortcodes = apply_filters( 'qi_addons_for_elementor_filter_include_shortcodes', array() );

			$shortcodes = array_merge( $shortcodes, $additional_shortcodes );

			if ( ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					foreach ( glob( $shortcode . '/include.php' ) as $shortcode ) {
						include_once $shortcode;
					}
				}
			}
		}

		function register_shortcodes() {
			$qi_addons_for_elementor_framework = qi_addons_for_elementor_framework_get_framework_root();
			$shortcodes                        = apply_filters( 'qi_addons_for_elementor_filter_register_shortcodes', $shortcodes = array() );

			if ( ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					$qi_addons_for_elementor_framework->add_shortcode( new $shortcode() );
				}
			}
		}
	}

	QiAddonsForElementor_Shortcodes::get_instance();
}
