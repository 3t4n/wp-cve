<?php

defined('ABSPATH') or die('No script kiddies please!');
/*
  Plugin Name: WP Subscription Forms
  Plugin URI:  https://wpshuffle.com/wp-subscription-forms
  Description: A simple plugin to create subscription forms and use it to collect subscribers from site.
  Version:     1.2.1
  Author:      WP Shuffle
  Author URI:  http://wpshuffle.com
  Domain Path: /languages
  Text Domain: wp-subscription-forms
 */



/**
 * Plugin's main class
 */
if (!class_exists('WPSF_Class')) {

    class WPSF_Class {

        function __construct() {
            $this->define_constants();
            $this->includes();
        }

        /**
         * Necessary constants
         */
        function define_constants() {

            global $wpdb;
            defined('WPSF_VERSION') or define('WPSF_VERSION', '1.2.1'); // Plugin's active version
            defined('WPSF_PATH') or define('WPSF_PATH', plugin_dir_path(__FILE__)); // plugin's absolute path
            defined('WPSF_URL') or define('WPSF_URL', plugin_dir_url(__FILE__)); // plugin's absolute path
            defined('WPSF_IMG_DIR') or define('WPSF_IMG_DIR', plugin_dir_url(__FILE__) . 'images'); // plugin's image directory URL
            defined('WPSF_CSS_DIR') or define('WPSF_CSS_DIR', plugin_dir_url(__FILE__) . 'css'); // plugin's image directory URL
            defined('WPSF_JS_DIR') or define('WPSF_JS_DIR', plugin_dir_url(__FILE__) . 'js'); // plugin's image directory URL
            defined('WPSF_TD') or define('WPSF_TD', 'wp-subscription-forms'); //plugin's translation text domain
            defined('WPSF_FORM_TABLE') or define('WPSF_FORM_TABLE', $wpdb->prefix . 'wpsf_forms'); //plugin's translation text domain
            defined('WPSF_SUBSCRIBERS_TABLE') or define('WPSF_SUBSCRIBERS_TABLE', $wpdb->prefix . 'wpsf_subscribers'); //plugin's subscriber table
            defined('WPSF_TOTAL_TEMPLATES') or define('WPSF_TOTAL_TEMPLATES', 10); //Total number of templates available
            defined('WPSF_LANGUAUGE_PATH') or define('WPSF_LANGUAUGE_PATH', basename(dirname(__FILE__)) . '/languages/');
        }

        /**
         * Includes necessary classes & files
         */
        function includes() {
            include(WPSF_PATH . 'inc/classes/class-wpsf-library.php');
            include(WPSF_PATH . 'inc/classes/class-wpsf-init.php');
            if (is_admin()) {
                include(WPSF_PATH . 'inc/classes/class-wpsf-activation.php');
                include(WPSF_PATH . 'inc/classes/class-wpsf-admin.php');
                include(WPSF_PATH . 'inc/classes/class-wpsf-ajax-admin.php');
                include(WPSF_PATH . 'inc/classes/class-wpsf-review.php');
            }

            include(WPSF_PATH . 'inc/classes/class-wpsf-enqueue.php');
            include(WPSF_PATH . 'inc/classes/class-wpsf-ajax.php');
            include(WPSF_PATH . 'inc/classes/class-wpsf-shortcode.php');
            include(WPSF_PATH . 'inc/classes/class-wpsf-hooks.php');
            include(WPSF_PATH . 'inc/classes/class-wpsf-mobile-detect.php');
            include(WPSF_PATH . 'inc/classes/class-wpsf-widget.php');
        }
    }

    $wpsf_obj = new WPSF_Class(); //initialization of plugin
}
