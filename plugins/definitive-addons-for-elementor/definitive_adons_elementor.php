<?php
/**
 * Plugin Name: Definitive Addons for Elementor
 * Description: Advanced Widgets for Elementor Page Builder.
 * Plugin URI:  https://softfirm.net/
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Version:     1.5.16
 * Author:      Softfirm
 * @link     https://developers.elementor.com/docs/
 * Author URI:  https://softfirm.net/definitive-addons/
 * Text Domain: definitive-addons-for-elementor
 * contributor:khuda
 */
use Definitive_Addons_Elementor\Elements\Definitive_Addons_Dashboard;
use Definitive_Addons_Elementor\Elements\Definitive_Addon_Elements;
if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

 /**
 * Definitive_Addons_Elementor  Class
 * The main class that initiates and runs the plugin.
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 * @since    1.0.0
 */ 
final class Definitive_Addons_Elementor
{

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.5.16';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Definitive_Addons_Elementor The single instance of the class.
     */
    private static $_instance = null;
    
    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Definitive_Addons_Elementor An instance of the class.
     */
    public static function instance()
    {

        if (is_null(self::$_instance) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct()
    {
        
        register_activation_hook(__FILE__, array( $this, 'definitive_addons_for_elementor_activate' ));

        add_action('admin_init', array( $this, 'definitive_addons_for_elementor_redirect'));
        
        
        add_action('plugins_loaded', array( $this, 'constants' ), 2);
        
        add_action('init', [ $this, 'i18n' ]);
        
        add_action('plugins_loaded', [ $this, 'init' ]);
        
        add_action('init', [ $this, 'initial_files' ]);
        
        add_action('elementor/elements/categories_registered', [ $this, 'register_widget_category' ]);
    
        add_action('wp_enqueue_scripts', array( $this, 'enqueue_front_scripts_styles' ));
        
    }
    /**
     * Activate the plugin
     *
     * @access public 
     *
     * @return void.
     */
    function definitive_addons_for_elementor_activate()
    {
        add_option('definitive-addons-for-elementor_do_activation_redirect', true);
    }
    /**
     * Redirect the plugin to setting page
     *
     * @access public 
     *
     * @return void.
     */
    function definitive_addons_for_elementor_redirect()
    {
        if (get_option('definitive-addons-for-elementor_do_activation_redirect', false)) {
            delete_option('definitive-addons-for-elementor_do_activation_redirect');
            
            wp_safe_redirect(admin_url('admin.php?page=definitive-addons-settings'));
            
        }
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     * @return void.
     */
    public function i18n()
    {

        load_plugin_textdomain('definitive-addons-for-elementor');

    }
    /**
     * Define constant
     *
     * @access public 
     *
     * @return void.
     */
    function constants()
    {
        $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
        $plugin_version = $plugin_data['Version'];
        define('DAFE_CURRENT_VERSION', $plugin_version);
        define('DAFE_DIR', trailingslashit(plugin_dir_path(__FILE__)));
        define('DAFE_URI', trailingslashit(plugin_dir_url(__FILE__)));
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by 'plugins_loaded' action hook.
     *
     * @access public
     * @return void.
     */
    public function init()
    {

        // Check if Elementor installed and activated
        if (! did_action('elementor/loaded') ) {
            add_action('admin_notices', [ $this, 'admin_notice_missing_main_plugin' ]);
            return;
        }

        // Check for required Elementor version
        if (! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=') ) {
            add_action('admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ]);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<') ) {
            add_action('admin_notices', [ $this, 'admin_notice_minimum_php_version' ]);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [ $this, 'dafe_init_widgets' ]);
        
    }
    
    
     /**
      * Enqueue scripts and styles
      *
      * @access public 
      *
      * @return void.
      */
    public function enqueue_front_scripts_styles()
    {
         
        wp_enqueue_style('slick-plug',  DAFE_URI . '/css/slick.css');
        
        wp_enqueue_style('animate-css',  DAFE_URI . '/css/animate.css');

        
        wp_enqueue_style('slick-themes',  DAFE_URI . '/css/slick-theme.css');
        
        wp_enqueue_style('dafe-plug',  DAFE_URI . '/css/dafe_style.css');
        wp_enqueue_script('countTo', DAFE_URI . '/js/jquery.countTo.js', array('jquery'), '', true);
        
        
        wp_enqueue_script('slick-plug-min',  DAFE_URI . '/js/slick.js', array('jquery'), '', true);
        wp_enqueue_script('custom-plug-min',  DAFE_URI . '/js/custom.js', array('jquery'), '', true);
        wp_enqueue_script('dafe_isotope', DAFE_URI . '/inc/js/fnf-isotope.js', array('jquery'), '', true);
        wp_enqueue_script('isotope.pkgd', DAFE_URI . '/js/isotope.pkgd.js', array('jquery'), '', true);
        wp_enqueue_script('typeds', DAFE_URI . '/js/typed.js', array('jquery'), '', true);
        
    }
     
    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @return string of html.
     */
    public function admin_notice_missing_main_plugin()
    {

        if (isset($_GET['activate']) ) { 
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'definitive-addons-for-elementor'),
            '<strong>' . esc_html__('Definitive Addon for Elementor', 'definitive-addons-for-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'definitive-addons-for-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @return string of html.
     */
    public function admin_notice_minimum_elementor_version()
    {

        if (isset($_GET['activate']) ) { 
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'definitive-addons-for-elementor'),
            '<strong>' . esc_html__('Definitive Addon for Elementor', 'definitive-addons-for-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'definitive-addons-for-elementor') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @return string of html.
     */
    public function admin_notice_minimum_php_version()
    {

        if (isset($_GET['activate']) ) { 
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'definitive-addons-for-elementor'),
            '<strong>' . esc_html__('Definitive Addon for Elementor', 'definitive-addons-for-elementor') . '</strong>',
            '<strong>' . esc_html__('PHP', 'definitive-addons-for-elementor') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }
    
     /**
      * Registering widget/element category
      *
      * @param array $elements_manager element title and icon
      *
      * @return array.
      */
    public function register_widget_category( $elements_manager )
    {

        $elements_manager->add_category(
            'definitive-addons',
            [
            'title' => __('Definitive Addons', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-plug',
            ]
        );

    }
    
     /**
      * Include files to definitive_adons_elementor.php
      *
      * @access public 
      *
      * @return void.
      */
    public function initial_files()
    {
        
        include_once __DIR__ . '/inc/Reuses/Reuse.php';
        include_once __DIR__ . '/inc/Reuses/Da_Post.php';
        include_once __DIR__ . '/inc/admin/da-admin-settings.php';
        
    }
    
    
    /**
     * Get updated elements to admin dashboard
     *
     * @access public 
     *
     * @return array.
     */
    public static function dafe_get_updated_elements()
    {
            
            $all_elements = array_fill_keys(Definitive_Addons_Dashboard::dafe_get_elements_file_name(), true);
            $saved_elements = get_option('dafe_admin_save_settings', $all_elements);
            $disable_elements  = array_diff_key($all_elements, $saved_elements);
            $updated_all_elements = array_merge($saved_elements, $disable_elements);
        if ($saved_elements === false ) {
            $saved_elements = [];
        }
            update_option('dafe_admin_save_settings', $updated_all_elements);
            return $saved_elements;
    }
     
    /**
     * Get enable elements
     *
     * @access public 
     *
     * @return array.
     */
    public static function dafe_get_enable_elements()
    {
        $dafe_saved_elements = self::dafe_get_updated_elements();
        $elements = Definitive_Addon_Elements::definitive_addons();
        $enable_elements = [];
        foreach ( $elements['elements'] as  $element ) {
                    
            if (isset($dafe_saved_elements[$element['file_name']]) && $dafe_saved_elements[$element['file_name']] == 1 ) {
                      
                $enable_elements[] = $element;
            }
        }
        return $enable_elements;
    }
    
     /**
      * Registering enable widgets/elements
      *
      * @access public 
      *
      * @return array.
      */
    public function dafe_init_widgets()
    {
        $enable_elements = self::dafe_get_enable_elements();
        foreach ( $enable_elements as  $element ) {
			
            $element_file = __DIR__ . '/inc/Elements/' . $element['file_name'] . '.php';
                      
            if ((file_exists($element_file)) && (($element['file_name'] != 'Products') && ($element['file_name'] != 'Category_Box'))) {
				   
                  include_once $element_file;
				  
				 $class_name = $element['class_path'];          
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $class_name());
        
				   
            }
			if(class_exists('woocommerce') && (($element['file_name'] == 'Products') || ($element['file_name'] == 'Category_Box'))){
                        
            
			     include_once $element_file;
				 $class_woo_name = $element['class_path'];          
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $class_woo_name());
        
				   
            }
			

		}
    }
   
}

Definitive_Addons_Elementor::instance();
