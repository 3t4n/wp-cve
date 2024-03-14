<?php

defined('ABSPATH') or die('No script kiddies please!');
/*
  Plugin Name: Subscribe To Unlock Lite
  Plugin URI:  http://demo.wpshuffle.com/subscribe-to-unlock-lite/
  Description: A plugin to collect email of your site visitors by locking certain content of your site until visitors subscribes through the subscription forms
  Version:     1.2.7
  Author:      WP Shuffle
  Author URI:  http://wpshuffle.com
  Domain Path: /languages
  Text Domain: subscribe-to-unlock-lite
 */



/**
 * Plugin's main class
 */
if (!class_exists('STUL_Class')) {

    class STUL_Class {

        function __construct() {
            $this->define_constants();
            $this->includes();
        }

        /**
         * Necessary constants
         */
        function define_constants() {

            global $wpdb;
            defined('STUL_VERSION') or define('STUL_VERSION', '1.2.7'); // Plugin's active version
            defined('STUL_PATH') or define('STUL_PATH', plugin_dir_path(__FILE__)); // plugin's absolute path
            defined('STUL_URL') or define('STUL_URL', plugin_dir_url(__FILE__)); // plugin's absolute path
            defined('STUL_IMG_DIR') or define('STUL_IMG_DIR', plugin_dir_url(__FILE__) . '/images'); // plugin's image directory URL
            defined('STUL_CSS_DIR') or define('STUL_CSS_DIR', plugin_dir_url(__FILE__) . '/css'); // plugin's image directory URL
            defined('STUL_JS_DIR') or define('STUL_JS_DIR', plugin_dir_url(__FILE__) . '/js'); // plugin's image directory URL
            defined('STUL_TD') or define('STUL_TD', 'subscribe-to-unlock-lite'); //plugin's translation text domain
            defined('STUL_FORM_TABLE') or define('STUL_FORM_TABLE', $wpdb->prefix . 'stul_forms'); //plugin's translation text domain
            defined('STUL_SUBSCRIBERS_TABLE') or define('STUL_SUBSCRIBERS_TABLE', $wpdb->prefix . 'stul_subscribers'); //plugin's subscriber table
            defined('STUL_TOTAL_TEMPLATES') or define('STUL_TOTAL_TEMPLATES', 5); //Total number of templates available
            defined('STUL_LANGUAUGE_PATH') or define('STUL_LANGUAUGE_PATH', basename(dirname(__FILE__)) . '/languages/');
        }

        /**
         * Includes necessary classes & files
         */
        function includes() {
            include(STUL_PATH . 'inc/classes/class-stul-library.php');
            include(STUL_PATH . 'inc/classes/class-stul-init.php');
            if (is_admin()) {
                include(STUL_PATH . 'inc/classes/class-stul-admin.php');
                include(STUL_PATH . 'inc/classes/class-stul-activation.php');
                include(STUL_PATH . 'inc/classes/class-stul-ajax-admin.php');
                include(STUL_PATH . 'inc/classes/class-stul-review.php');
            }

            include(STUL_PATH . 'inc/classes/class-stul-enqueue.php');
            include(STUL_PATH . 'inc/classes/class-stul-ajax.php');
            include(STUL_PATH . 'inc/classes/class-stul-shortcode.php');
            include(STUL_PATH . 'inc/classes/class-stul-hooks.php');
            include(STUL_PATH . 'inc/classes/class-stul-mobile-detect.php');
        }
    }

    $stul_obj = new STUL_Class(); //initialization of plugin
}
