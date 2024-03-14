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
class Plugin_DAE {

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
                add_action('elementor/init', array($this, 'dae_elementor_init'));
	}
        
        /**
        * Init elementor finction
        *
        * @since 0.0.1
        *
        * @access public
        */
       public function dae_elementor_init() {
           
            // Se è attivo DCE il plugin si disabilita
            //$proModule = WP_PLUGIN_DIR.'/dynamic-content-for-elementor/dynamic-content-for-elementor.php';
            //var_dump($proModule); die();
            $plugin = 'dynamic-content-for-elementor/dynamic-content-for-elementor.php';
            $plugins = get_site_option( 'active_sitewide_plugins');
            $is_plugin_active = in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || (is_multisite() && isset($plugins[$plugin]));
            //var_dump($is_plugin_active);
            if (!$is_plugin_active)  {
                
                define('DAE__FILE__', __FILE__);
                define('DAE_URL', plugins_url('/', __FILE__));
                define('DAE_PATH', plugin_dir_path(__FILE__));
                define('DAE_PLUGIN_BASE', plugin_basename( DAE__FILE__ ) );
                if (!defined('DCE_TEXTDOMAIN')) {
                    define('DCE_TEXTDOMAIN', 'dynamic-content-for-elementor');
                }
                if (!defined('DCE__FILE__')) {
                    define('DCE__FILE__', DAE__FILE__);
                }

                if (!class_exists('DynamicContentForElementor\DynamicContentForElementor_Helper')) {
                    $this->dce_file_include( 'class/helper.php' );
                }
                
                // ANIMATION
                $this->dce_file_include( 'class/controls/groups/animation.php' );
                \Elementor\Plugin::instance()->controls_manager->add_group_control( 'animation-element', new Group_Control_AnimationElement() );

                add_action( 'elementor/frontend/after_enqueue_styles', function(){
                    wp_register_style(
                        'dce-animations', plugins_url('/assets/css/dce-animations.css', DAE__FILE__), [], DAE_VERSION
                    );
                    // Enqueue DCE Elementor Style
                    wp_enqueue_style('dce-animations');
                });

                if (!class_exists('DynamicContentForElementor\Extensions\Extension_Prototype')) {
                    $this->dce_file_include( 'extensions/extension_Prototype.php' ); // obbligatorio in quanto esteso dagli altri
                }
                if (!class_exists('DynamicContentForElementor\Extensions\Extension_Animations')) {
                    $this->dce_file_include( 'extensions/animations.php' );       
                }
                //var_dump( get_declared_classes ()); die();
                $advancedAnimations = new Extensions\Extension_Animations();
            }

       }
       
       public function dce_file_include( $file ) {
            $path = DAE_PATH . $file;
            //echo $path;
            if ( file_exists( $path ) ) {
                include_once( $path );
            }
        }

}

new Plugin_DAE();
