<?php
namespace MasterCustomBreakPoint;

defined( 'ABSPATH' ) || exit;

if( !class_exists('JLTMA_Master_Custom_Breakpoint') ){

	class JLTMA_Master_Custom_Breakpoint{

		public $dir;

		public $url;

		private static $plugin_path;

	    private static $plugin_url;

	    private static $_instance = null;

		const MINIMUM_PHP_VERSION = '5.6';

	    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		public static $plugin_name = 'Elementor Breakpoints Extender';

	    public function __construct(){

			$this->jltma_mcb_include_files();

			add_action( 'init', [ $this, 'jltma_mcb_i18n' ] );

			add_action( 'plugins_loaded', [ $this, 'init' ] );

    	}

    	public function jltma_mcb_i18n(){
    		load_plugin_textdomain( 'master-custom-breakpoint' );
    	}

		public function init(){
			// If 'is_plugin_active' function not found
			if ( ! function_exists( 'is_plugin_active' ) ){
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}

			if ( is_plugin_active( 'elementor/elementor.php' ) ) {

				// Check if the Version between 2.9.0 to 3.0.0
				if ( version_compare( ELEMENTOR_VERSION, '2.9.0', '>=' ) && version_compare( ELEMENTOR_VERSION, '3.0.0', '<' ) ) {

					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v2/base.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v2/frontend.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v2/responsive.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v2/controls-stack.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v2/stylesheet.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v2/editor.php';

				} elseif ( version_compare( ELEMENTOR_VERSION, '3.0.0', '>' ) && version_compare( ELEMENTOR_VERSION, '3.2.4', '<' ) ) {

					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v32/base.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v32/responsive.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v32/controls-stack.php';
					include_once JLTMA_MCB_PLUGIN_PATH .'lib-v32/editor.php';

				} elseif ( version_compare( ELEMENTOR_VERSION, '3.2.4', '>=' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '<' ) ) {

					include_once JLTMA_MCB_PLUGIN_PATH . 'lib-v324/core/breakpoints/manager.php';
					include_once JLTMA_MCB_PLUGIN_PATH . 'lib-v324/core/breakpoints/breakpoint.php';
					include_once JLTMA_MCB_PLUGIN_PATH . 'lib-v324/core/editor/editor.php';
					include_once JLTMA_MCB_PLUGIN_PATH . 'lib-v324/includes/base/controls-stack.php';
					include_once JLTMA_MCB_PLUGIN_PATH . 'lib-v324/misc.php';

				} elseif ( version_compare( ELEMENTOR_VERSION, '3.5.1', '>=' ) ) {
					include_once JLTMA_MCB_PLUGIN_PATH . 'lib/core/breakpoints/manager.php';
					include_once JLTMA_MCB_PLUGIN_PATH . 'lib/misc.php';
				}
				
	    	}

	    	self::jltma_mcb_plugin_activation_hook();
		}


		public function jltma_mcb_include_files(){
			if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
				include JLTMA_MCB_PLUGIN_PATH . 'inc/class-tgm-plugin-activation.php';
			}
			include JLTMA_MCB_PLUGIN_PATH . 'inc/breakpoint-assets.php';
			include JLTMA_MCB_PLUGIN_PATH . 'inc/hooks.php';
		}


		// Activation Hook
	    public static function jltma_mcb_plugin_activation_hook(){

	        if (get_option('jltma_mcb_activation_time') === false)
	        	update_option('jltma_mcb_activation_time', strtotime("now") );


	        $custom_breakpoints = json_decode(file_get_contents( JLTMA_MCB_PLUGIN_PATH . '/custom_breakpoints.json'), true);
	        if (get_option('jltma_mcb_activation_time') === false)
	        	update_option('jltma_mcb', $custom_breakpoints );	        
	    }

		public function is_elementor_activated( $plugin_path = 'elementor/elementor.php' ) {
			$installed_plugins_list = get_plugins();

			return isset( $installed_plugins_list[ $plugin_path ] );
		}


	    public static function get_instance() {
	        if ( is_null( self::$_instance ) ) {
	            self::$_instance = new self();
	        }
	        return self::$_instance;
	    }
	}
}

JLTMA_Master_Custom_Breakpoint::get_instance();