<?php
/**
 * The plugin paths defining functionality of plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/base
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\base;

if (!class_exists('THWWC_Base_Controller')) :
    /**
     * Basecontroller class
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Base_Controller
    {
        const TEXT_DOMAIN = 'wishlist-and-compare';

        public $plugin_path;

        public $plugin_url;

        public $plugin;

        /**
         * Function to define paths.
         *
         * @return void
         */
        public function __construct() 
        {
            $this->plugin_name = 'wishlist-and-compare';
            $this->plugin_url = plugins_url( '/', __FILE__ );
            $this->plugin = plugin_basename( __FILE__ );
            $this->set_locale();
        }
        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the THWWC_I18n class in order to set the domain and to register the hook
         * with WordPress.
         *
         * @access   private
         */
        private function set_locale() {
            add_action('plugins_loaded',array($this, 'load_plugin_textdomain'));
        }

        /**
         * Load the plugin text domain for translation.
         */
        public function load_plugin_textdomain() {
            $locale = apply_filters('plugin_locale', get_locale(), self::TEXT_DOMAIN);
            
            load_textdomain(self::TEXT_DOMAIN, WP_LANG_DIR.'/'.self::TEXT_DOMAIN.'/'.self::TEXT_DOMAIN.'-'.$locale.'.mo');
            load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
        }
    }
endif;