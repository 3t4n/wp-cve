<?php
/**
 * Wordress-Laravel Integration Plugin
 *
 * @package           WordressLaravel\Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Plum: Spin Wheel & Email Pop-up
 * Plugin URI:        https://plumpopup.com/
 * Description:       Plum plugin: Create captivating popups effortlessly in 5 mins! Collect form submissions, promote products, boost sales, and grow your email list. Enhance engagement with Spin the Wheel, callback requests, and social sharing.
 * Version:           2.0
 * Author:            Upqode
 * Author URI:
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       laravel-wordpress-integration
 * Domain Path:       /languages
 */

use WordressLaravel\Wp\Start;

if (!defined('WPINC')) {
    die;
}

call_user_func(function() {
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

    $plugin = new Start(__FILE__);
    $plugin->run();

    add_action( 'plugins_loaded', array( WordressLaravel\Wp\PageTemplater::class, 'get_instance' ) );
});

register_activation_hook( __FILE__, array( WordressLaravel\Wp\Activation::class, 'do_my_hook' ) );
register_deactivation_hook( __FILE__, array( WordressLaravel\Wp\Deactivation::class, 'do_my_hook' ) );