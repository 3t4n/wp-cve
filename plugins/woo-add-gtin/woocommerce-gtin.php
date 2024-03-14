<?php
/**
 * Plugin Name:     WooCommerce UPC, EAN, and ISBN
 * Plugin URI:      http://hollerwp.com
 * Description:     Add GTIN including UPC, EAN, and ISBN code fields to your WooCommerce product pages and checkout.
 * Version:         0.5.1
 * Author:          Scott Bolinger
 * Text Domain:     woo-add-gtin
 * WC requires at least: 4.0
 * WC tested up to: 5.0.0
 *
 * @author          Scott Bolinger
 * @copyright       Copyright (c) Scott Bolinger 2017
 *
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'Woo_GTIN' ) ) {

    /**
     * Main Woo_GTIN class
     *
     * @since       0.1.0
     */
    class Woo_GTIN {

        /**
         * @var         Woo_GTIN $instance The one true Woo_GTIN
         * @since       0.1.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       0.1.0
         * @return      self The one true Woo_GTIN
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new Woo_GTIN();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                // self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       0.1.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'Woo_GTIN_VER', '0.5.1' );

            // Plugin path
            define( 'Woo_GTIN_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'Woo_GTIN_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       0.1.0
         * @return      void
         */
        private function includes() {

            require_once Woo_GTIN_DIR . 'includes/class-woo-gtin-functions.php';

            if( is_admin() )
                require_once Woo_GTIN_DIR . 'includes/class-woo-gtin-admin.php';
            
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       0.1.0
         * @return      void
         *
         *
         */
        private function hooks() {

        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       0.1.0
         * @return      void
         */
        public function load_textdomain() {

            load_plugin_textdomain( 'woo-add-gtin' );
            
        }

    }
} // End if class_exists check


/**
 * The main function responsible for returning the one true
 * instance to functions everywhere
 *
 * @since       0.1.0
 * @return      \Woo_GTIN The one true Woo_GTIN
 *
 */
function woo_gtin_load() {

    // Check if WooCommerce is active
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        return Woo_GTIN::instance();
    }

}
add_action( 'plugins_loaded', 'woo_gtin_load' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       0.1.0
 * @return      void
 */
function woo_gtin_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'woo_gtin_activation' );