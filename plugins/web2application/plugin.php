<?php
namespace DynamicContentForElementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Plugin_W2A {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
        add_action('elementor/init', array($this, 'w2a_elementor_init'));
	}
        
        /**
        * Init elementor finction
        *
        * @since 0.0.1
        *
        * @access public
        */
       public function w2a_elementor_init() {
		   
            $plugin = 'dynamic-content-for-elementor/dynamic-content-for-elementor.php';
            $plugins = get_site_option( 'active_sitewide_plugins');
            $is_plugin_active = in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || (is_multisite() && isset($plugins[$plugin]));
		   
            if (!$is_plugin_active)  {
                
                define('DVE__FILE__', __FILE__);
                define('DVE_URL', plugins_url('/', __FILE__));
                define('DVE_PATH', plugin_dir_path(__FILE__));
                define('DVE_PLUGIN_BASE', plugin_basename( DVE__FILE__ ) );
                if (!defined('DCE_TEXTDOMAIN')) {
                    define('DCE_TEXTDOMAIN', 'inapp_visibility-for-elementor');
                }
                if (!defined('DCE__FILE__')) {
                    define('DCE__FILE__', DVE__FILE__);
                }
                
                //if (!class_exists('DynamicContentForElementor\DCE_Helper')) {
                    $this->dce_file_include( 'class/W2A_Helper.php' );
                //}

                add_action('elementor/frontend/after_register_styles', function() {
                    wp_register_style(
                        'w2a-style', plugins_url('/assets/css/style.css', DVE__FILE__), [], W2A_VERSION
                    );
                    // Enqueue W2A Elementor Style
                    wp_enqueue_style('w2a-style');
                });
                
                // W2A Custom Icons - in Elementor Editor
                add_action('elementor/preview/enqueue_styles', function(){
                    wp_register_style(
                        'w2a-preview', plugins_url('/assets/css/w2a-preview.css', DVE__FILE__), [], W2A_VERSION
                    );
                    // Enqueue W2A Elementor Style
                    wp_enqueue_style('w2a-preview');
                });
                add_action('elementor/editor/after_enqueue_scripts', array($this, 'w2a_editor'));

                //if (!class_exists('DynamicContentForElementor\Extensions\W2A_Extension_Prototype')) {
                    $this->dce_file_include( 'extensions/W2A_Extension_Prototype.php' );
                //}
                //if (!class_exists('DynamicContentForElementor\Extensions\W2A_Extension_Visibility')) {
                    $this->dce_file_include( 'extensions/W2A_Extension_Visibility.php' );
                //}
                $advancedVisibility = new Extensions\W2A_Extension_Visibility();
            }

       }
       
       
       /**
        * Enqueue admin styles
        *
        * @since 0.7.0
        *
        * @access public
        */
       public function w2a_editor() {
           // Register styles
           wp_register_style(
                   'w2a-style-icons', plugins_url('/assets/css/w2a-icon.css', DCE__FILE__), [], W2A_VERSION
           );
           // Enqueue styles Icons
           wp_enqueue_style('w2a-style-icons');

           // Register styles
           wp_register_style(
                   'w2a-style-editor', plugins_url('/assets/css/w2a-editor.css', DCE__FILE__), [], W2A_VERSION
           );
           // Enqueue styles Icons
           wp_enqueue_style('w2a-style-editor');

           wp_register_script(
                   'dce-script-editor-visibility', plugins_url('/assets/js/w2a-editor-visibility.js', DCE__FILE__), [], W2A_VERSION
           );
           wp_enqueue_script('w2a-script-editor-visibility');
       }
       
       public function dce_file_include( $file ) {
            $path = DVE_PATH . $file;
            //echo $path;
            if ( file_exists( $path ) ) {
                include_once( $path );
            }
        }

}

new Plugin_W2A();
