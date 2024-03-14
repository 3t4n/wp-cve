<?php
/**
 * The file responsible for starting the Easy Hotjar WordPress plugin
 *
 * The Easy hotjar WordPress plugin helps you to set up hotjar on your site.
 *
 * @package EHW
 *
 * Plugin Name: Easy Hotjar
 * Plugin URI: http://wordpress.org/plugins/easy-hotjar-wordpress/
 * Description: Get Hotjar up and running on your WordPress with 2 clicks
 * Version: 1.0
 * Author: Joaquin Ruiz
 * Author URI: http://jokiruiz.com
 */

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-hotjar-wordpress.php';

/**
 * Instantiates the Easy Hotjar WordPress class and then
 * calls its run method officially starting up the plugin.
 */
function run_easy_hotjar_wordpress() {

    $ehw = new Easy_Hotjar_WordPress();
    $ehw->run();

}

run_easy_hotjar_wordpress();


/** THE END **/
