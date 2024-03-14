<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: 8 Degree Availability Calendar
Plugin URI:  http://8degreethemes.com/wordpress-plugins/8-degree-availability-calendar/
Description: A plugin which is used to display availability calendar on the site:
Version:     1.1.0
Author:      8 Degree Themes
Author URI:  http://8degreethemes.com
License:     GPL2
Domain Path: /languages/
Text Domain: edac-plugin
*/

/**
 * Declartion of necessary constants for plugin
 * */
if (!defined('EDAC_IMAGE_DIR')) {
    define('EDAC_IMAGE_DIR', plugin_dir_url(__FILE__) . 'images');
}
if (!defined('EDAC_JS_DIR')) {
    define('EDAC_JS_DIR', plugin_dir_url(__FILE__) . 'js');
}
if (!defined('EDAC_CSS_DIR')) {
    define('EDAC_CSS_DIR', plugin_dir_url(__FILE__) . 'css');
}
if (!defined('EDAC_VERSION')) {
    define('EDAC_VERSION', '1.1.0');
}
/**
 * Register of widgets
 * */
include_once('inc/backend/widget.php');
if (!class_exists('Edac_Class')) {

    class Edac_Class {
        var $edac_settings;
        /**
         * Initializes the plugin functions 
         */
         function __construct() {
            $this->edac_settings = get_option('edac_settings');
            register_activation_hook( __FILE__, array($this,'edac_activation' ));// Loads activating the EDN plugin
            add_action('init', array($this, 'edac_plugin_load_textdomain')); //loads text domain for translation ready
            add_action('init', array($this, 'session_init')); //starts the session
            add_action('admin_menu', array($this, 'edac_menu')); //Register The Plugin Menu
            add_action('admin_enqueue_scripts',array($this,'edac_admin_scripts')); //Registration of admin assets
            add_action( 'wp_enqueue_scripts', array($this,'edac_frontend_scripts' )); //Registration of Frontend assets
            add_action('admin_post_edac_settings_action',array($this,'edac_save_settings_action')); //recieves the posted values from settings form
            add_action('admin_post_edac_restore_default', array($this, 'edac_restore_default')); //restores default settings
            add_action( 'wp_ajax_ajax_book', array($this,'edac_action_callback' )); // Registration of subscribe ajax
            add_action( 'wp_ajax_nopriv_ajax_book', array($this,'edac_action_callback' )); // Registration of subscribe ajax
            add_shortcode('edac-availability', array($this, 'edac_availiability_shortcode')); //adds a shortcode
            add_action('widgets_init', array($this, 'register_edac_widget')); //registers the widget
         }
         
         /**
         * Load plugin textdomain.
         *
         * @since 1.0.7
         */
        function edac_plugin_load_textdomain() {
          load_plugin_textdomain( 'edac-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
        }
        
        /**
         * Registration of admin assets 
         * */
        function edac_admin_scripts(){
            if(isset($_GET['page']) && $_GET['page']=='edac-plugin'){
                wp_enqueue_style('edac-admin-style', EDAC_CSS_DIR . '/backend/backend.css', array(), EDAC_VERSION);
                wp_enqueue_style('edac-evol-colorpicker-style', EDAC_CSS_DIR . '/backend/evol.colorpicker.min.css', array(),'3.2.2');
                wp_enqueue_script('edac-calendar-js',EDAC_JS_DIR.'/backend/edac-calendar.js',array('jquery'),'1.8.16');
                wp_enqueue_script('edac-admin-js',EDAC_JS_DIR.'/backend/backend.js',array('jquery'),EDAC_VERSION);                 
                wp_enqueue_script('edac-evol-colorpicker-picker-js',EDAC_JS_DIR.'/backend/evol.colorpicker.min.js',array('jquery'),'3.2.2');
                wp_localize_script( 'edac-admin-js', 'ajaxbook', array(
            		'ajaxurl' => admin_url( 'admin-ajax.php' )
            	));    
            }
            wp_enqueue_style('fontawesome-css', EDAC_CSS_DIR . '/font-awesome/font-awesome.min.css',false,EDAC_VERSION);
            
        }
        
        /**
         * Registration of Frontend assets 
         * */
         function edac_frontend_scripts() {
            wp_enqueue_style('edac-font-awesome',EDAC_CSS_DIR.'/font-awesome/font-awesome.css');
            wp_enqueue_style('edac-frontend-style', EDAC_CSS_DIR . '/frontend/frontend.css');
            //wp_enqueue_script('edac-frontend-calendar-js',EDAC_JS_DIR.'/frontend/edac-calendar.js',array('jquery'),'1.8.16');
            wp_enqueue_script('edac-datepicker-js',EDAC_JS_DIR.'/frontend/jquery.ui.datepicker.js',array('jquery'),'1.8.16');
            wp_enqueue_script('edac-doutch-lan-js',EDAC_JS_DIR.'/frontend/lan/jquery-ui-i18n.js',array('jquery'),'');
            wp_enqueue_script('edac-frontend-js',EDAC_JS_DIR.'/frontend/frontend.js',array('jquery'),EDAC_VERSION);
            wp_enqueue_style('fontawesome-css', EDAC_CSS_DIR . '/font-awesome/font-awesome.min.css',false,EDAC_VERSION);
         }
        
        /**
         * Plugin Menu Registration
         * */
         
         function edac_menu(){
            add_menu_page( __('Availability Calendar','edac-plugin'), __('Availability Calendar','edac-plugin'),'manage_options','edac-plugin', array($this, 'edac_setting_page'), 'dashicons-calendar-alt' );
         }
         
         /**
          * Plugin Setting Page
          * */
          
          function edac_setting_page(){
            include_once('inc/backend/settings-page.php');
          }
          
         /**
          *  Saves settings to database
          **/
         function edac_save_settings_action(){
            if(!empty($_POST) && wp_verify_nonce($_POST['edac_nonce_field'],'edac-nonce')){
                include_once('inc/backend/save-settings.php');
            }
            else{
            die('No script kiddies please!');
            }
         }
         
         /**
          * Prints array in pre format
          * */   
         function print_array($array){
            echo "<pre>";
            print_r($array);
            echo "</pre>";
         }
         
         /**
         * Starts the session
         */
         function session_init() {
            if (!session_id()) {
                session_start();
            }
         }
         /**
         * activating the EDN plugin
         **/
        function edac_activation(){
            /**
             * Load Default Settings
             * */
            if (!get_option('edac_settings')) {
                $edac_settings = $this->get_default_settings();
                update_option('edac_settings', $edac_settings);
            }
        }
         
        /**
         * Restores the default 
         */
         function edac_restore_default() {
            $nonce = $_REQUEST['_wpnonce'];
            if(!empty($_GET) && wp_verify_nonce( $nonce, 'edac-restore-default-nonce' ))
            {
                $edac_settings = $this->get_default_settings();
                update_option('edac_settings', $edac_settings);
                $_SESSION['edac_message'] = __('Default Settings Restored Successfully', 'edac-plugin');
                wp_redirect(admin_url() . 'admin.php?page=edac-plugin');
            }
         }
         /**
          * Function of book availability ajax
          * */
         function edac_action_callback() {
            if(!empty($_POST) && wp_verify_nonce($_POST['nonce'],'edac-book-nonce')){
                $edac_settings = $this->edac_settings;
                $booked_date = sanitize_text_field($_POST['id']);
                $booked_date_array = $edac_settings['booked_date'];
                if($key = array_search($booked_date,$booked_date_array)){
                    unset($booked_date_array[$key]);
                }else{
                    $booked_date_array[] = $booked_date;
                }
                $edac_settings['booked_date'] = $booked_date_array;
                update_option('edac_settings',$edac_settings);
                die('success');
            }
            else{
            die('No script kiddies please!');
            }
            
         }
         
        /**
         * Returns Default Settings
         */
         function get_default_settings() {
            $from_year = date('Y');
            $to_date = $from_year+1;
            $edac_settings = array(
                                'edac_layout'=>1,
                                'edac_from'=>$from_year,
                                'edac_to'=>$to_date,
                                'edac_unavailable_color'=>'#ff3a3a',
                                'booked_date' => array(),
                                'edac_legend' => '',
                                'edac_legend_text' => __('Booked Date','edac-plugin'),
                                'edac_language' => '',
            );
            return $edac_settings;
         }
         
         /**
         * AccessPress Social Counter Widget
         */
        function register_edac_widget() {
            register_widget('EDAC_Widget');
        }
        
        /**
          * function for adding shortcode of a plugin
          * */
         function edac_availiability_shortcode($atts){
            ob_start();
            include('inc/frontend/shortcode.php');
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
         }
        
    }
    $edac_object = new Edac_Class(); //initialization of plugin
}
