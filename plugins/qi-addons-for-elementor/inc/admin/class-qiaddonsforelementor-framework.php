<?php

if ( ! class_exists( 'QiAddonsForElementor_Framework' ) ) {
	class QiAddonsForElementor_Framework {
		private static $instance;

		function __construct() {
			// Hook to include additional modules before plugin loaded
			do_action( 'qi_addons_for_elementor_action_framework_before_framework_plugin_loaded' );

			$this->require_core();

			add_action( 'qi_addons_for_elementor_action_on_activation', array( $this, 'set_activation_transient' ) );

			// Make plugin available for other plugins
			add_action( 'plugins_loaded', array( $this, 'init_framework_root' ) );

			// Hook to include additional modules when plugin loaded
			do_action( 'qi_addons_for_elementor_action_framework_after_framework_plugin_loaded' );
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function require_core() {
			require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/helpers/include.php';
			require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/class-qiaddonsforelementor-frameworkroot.php';
		}

		function init_framework_root() {
			do_action( 'qi_addons_for_elementor_action_framework_load_dependent_plugins' );

			$GLOBALS['qi_addons_for_elementor_framework'] = qi_addons_for_elementor_framework_get_framework_root();
		}

		function set_activation_transient() {
			set_transient( QI_ADDONS_FOR_ELEMENTOR_ACTIVATED_TRANSIENT, 1 );
		}

	}

	QiAddonsForElementor_Framework::get_instance();
}
