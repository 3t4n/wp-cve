<?php
/**
 * Plugin Name:     Featured Image in RSS Feed by MailerLite
 * Plugin URI:      https://wordpress.org/plugins/mailerlite-featured-image-in-rss-feed/
 * Description:     This plugin automatically adds featured images of your posts into the RSS feed.
 * Version:         1.0.7
 * Author:          MailerLite
 * Author URI:      https://mailerlite.com
 * Text Domain:     mailerlite-featured-image-in-rss-feed
 * License:         GPL v3
 *
 * @package         MailerLiteFIRSS
 * @author          MailerLite
 * @copyright       Copyright (c) MailerLite
 *
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'MailerLite_Featured_Image_In_RSS_Feed' ) ) {

    /**
     * Main MailerLite_Featured_Image_In_RSS_Feed class
     *
     * @since       1.0.0
     */
    class MailerLite_Featured_Image_In_RSS_Feed {

        /**
         * @var         MailerLite_Featured_Image_In_RSS_Feed $instance The one true MailerLite_Featured_Image_In_RSS_Feed
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true MailerLite_Featured_Image_In_RSS_Feed
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new MailerLite_Featured_Image_In_RSS_Feed();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {

            // Plugin name
            define( 'MAILERLITE_FIRSS_NAME', 'Featured Image in RSS Feed by MailerLite' );

            // Plugin version
            define( 'MAILERLITE_FIRSS_VER', '1.0.7' );

            // Plugin path
            define( 'MAILERLITE_FIRSS_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'MAILERLITE_FIRSS_URL', plugin_dir_url( __FILE__ ) );
        }
        
        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {

            // Basic
            require_once MAILERLITE_FIRSS_DIR . 'includes/helper.php';
	        require_once MAILERLITE_FIRSS_DIR . 'includes/functions.php';

            // Admin only
            if ( is_admin() ) {
                require_once MAILERLITE_FIRSS_DIR . 'includes/admin/plugins.php';
                require_once MAILERLITE_FIRSS_DIR . 'includes/admin/class.settings.php';
            }

            // Anything else
	        require_once MAILERLITE_FIRSS_DIR . 'includes/hooks.php';
        }

        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = MAILERLITE_FIRSS_DIR . '/languages/';
            $lang_dir = apply_filters( 'mailerlite_rss_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'mailerlite-featured-image-in-rss-feed' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'mailerlite-featured-image-in-rss-feed', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/plugin-name/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/mailerlite-featured-image-in-rss-feed/ folder
                load_textdomain( 'mailerlite-featured-image-in-rss-feed', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/plugin-name/languages/ folder
                load_textdomain( 'mailerlite-featured-image-in-rss-feed', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'mailerlite-featured-image-in-rss-feed', false, $lang_dir );
            }
        }
    }
} // End if class_exists check

/**
 * The main function responsible for returning the one true MailerLite_Featured_Image_In_RSS_Feed
 * instance to function everywhere
 *
 * @since       1.0.0
 * @return      \MailerLite_Featured_Image_In_RSS_Feed The one true MailerLite_Featured_Image_In_RSS_Feed
 *
 */
function mailerlite_firss_load() {
    return MailerLite_Featured_Image_In_RSS_Feed::instance();
}
add_action( 'plugins_loaded', 'mailerlite_firss_load' );