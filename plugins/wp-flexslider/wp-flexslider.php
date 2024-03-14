<?php
/*
 * Plugin Name: WP Flexslider
 * Plugin URI: http://mnmlthms.com/plugins/wp-flexslider
 * Description: Flexslider for WordPress Gallery
 * Author: mnmlthms
 * Version: 1.0.9
 * Author URI: http://mnmlthms.com/
 * Text domain: wp-flexslider
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WP_FLEXSLIDER_VERSION', '1.0.9' );
define( 'WP_FLEXSLIDER_OPTION', 'wp_flexslider' );

if( !class_exists( 'WP_Flexslider' ) ):

    /**
     * Gallery Settings for Media Uploader
     */
    class WP_Flexslider {

        public $gallery_types = array();
        /**
         * Constructor
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function __construct() {

            add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
            add_action( 'after_setup_theme', array( $this, 'hooks' ) );
        }

        function includes(){

        }
        /**
         * Include admin files
         *
         * These functions are included on admin pages only.
         *
         * @return    void
         *
         * @access    private
         * @since     1.0
         */
        private function admin_includes() {
          
            /* exit early if we're not on an admin page */
            if ( ! is_admin() )
                return false;

        }
        /**
         * Fire on plugins_loaded
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function plugins_loaded(){

            load_plugin_textdomain( 'wp-flexslider', false, self::get_dirname() . '/langs/' ); 
        }

        /**
         * Execute the Hooks
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function hooks() {

            add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 16 );

        }

        /**
         * JS and CSS
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function wp_enqueue_scripts(){
            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            // if( apply_filters( 'wp_flexslider_default_style', true ) )
            //     wp_enqueue_style( 'wp-flexslider', self::get_url() . 'css/style.css' , array(), '1.0' );
            wp_register_style( 'flexslider', self::get_url() . 'assets/flexslider.css' , array(), '2.6.3' );
            wp_register_style( 'wp-flexslider', self::get_url() . 'assets/css/style.css' , array('flexslider'), '1.0' );

            wp_register_script( 'flexslider', self::get_url() . "assets/jquery.flexslider$suffix.js" , array(), '2.6.3' );
            wp_register_script( 'wp-flexslider', self::get_url() . "assets/js/script$suffix.js" , array('jquery', 'flexslider'), '1.0.4' );
            
        }
        /**
         * Helpers
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        static function get_url() {
            return plugin_dir_url( __FILE__ );
        }

        static function get_dir() {
            return plugin_dir_path( __FILE__ );
        }

        static function plugin_basename() {
            return plugin_basename( __FILE__ );
        }
        
        static function get_dirname( $path = '' ) {
            return dirname( plugin_basename( __FILE__ ) );
        }

    }

    if( is_admin() ){
        require_once( 'inc/admin/settings.php' );
        require_once( 'inc/admin/editor.php' );
    }

    require_once( 'inc/public/main.php' );

endif;

// Kickstart it
$GLOBALS['wp_flexslider'] = new WP_Flexslider;