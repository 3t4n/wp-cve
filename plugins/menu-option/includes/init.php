<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MENU_OPTION_Admin' ) ) {

	final class MENU_OPTION_Admin extends MENU_OPTION_Functions {

		protected static $instance = null;

		public function __construct() {
			parent::__construct();
		}

		/**
		 * Main MENU_OPTION_Admin Instance
		 *
		 * Ensures only one instance of MENU_OPTION_Admin is loaded.
		 *
		 * @since 1.0
		 * @static
		 * @see MENU_OPTION_Admin()
		 * @return MENU_OPTION_Admin - Main instance
		 */
		static public function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->MO_Admin_callinit();
			}

			return self::$instance;
		}

		/**
		 * Main MENU_OPTION_Admin init
		 *
		 * @since 1.0
		 */
		function MO_Admin_callinit() {

			$this->MO_option_style_scripts();
		}

		/**
		 * Load JavaScript, CSS and jQuery code
		 *
		 * @since 1.0
		 */
		function MO_option_style_scripts() {
			
			add_action('admin_enqueue_scripts', array($this, 'MO_option_scripts'));
		}

		/**
		 * Load Admin JavaScript code
		 *
		 * @since 1.0
		 */
		function MO_option_scripts() {

			$current_page = get_current_screen()->base;
			if($current_page == "nav-menus"){

		        wp_register_script( 'mo_option_admin_scripts', MENU_OPTION_PLUGIN_URI . '/assets/js/mo-option-js.js',  array('jquery' ), MENU_OPTION_VERSION, true );
		        wp_enqueue_script( 'mo_option_admin_scripts' );
		    }
		}

		/**
		 * Deactivation code
		 *
		 * @since 1.0
		 */
		static function deactivation() {

			delete_post_meta_by_key( '_mo-option' );
    		delete_post_meta_by_key( '_mo-option-roles' );
    		delete_post_meta_by_key( '_mo-option-redirect' );
		}

	}

}

/**
 * Function for calling MENU_OPTION_Admin methods and variables
 *
 * @since 1.0
 *
 * @return MENU_OPTION_Admin
 */
function MO_Admin_call() {
	return MENU_OPTION_Admin::instance();
}
$GLOBALS['MOMO'] = MO_Admin_call();