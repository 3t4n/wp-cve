<?php
/**
 * Plugin Name:       Speed Kit
 * Plugin URI:        https://www.baqend.com/guide/topics/wordpress/
 * Description:       Easily use Baqend Cloud for your WordPress site and make it lightning-fast.
 * Version:           2.0.1
 * Author:            Baqend GmbH
 * Author URI:        https://www.baqend.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       baqend
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Check PHP version
$using_php    = phpversion();
$required_php = '7.3.0';
if ( version_compare( $using_php, $required_php, '<' ) ) {
    /* translators: 1: Current PHP version 2: Required PHP version */
    $message = __( 'Your PHP version %1$s is not compatible with this plugin. You need at least PHP %2$s. Please consider updating as older versions of PHP have reached their End-of-Life.', 'baqend' );
    die( sprintf( $message, $using_php, $required_php ) );
}

// The class that contains the plugin info.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * @param string $hash The site's hash.
 *
 * @return string The URL to the site on Baqend settings.
 */
function baqend_admin_url( $hash ) {
    return admin_url( 'admin.php?page=' . $hash );
}

/** @var \Baqend\WordPress\Plugin $plugin */
$pluginClass = 'Baqend\WordPress\Plugin';
$plugin      = new $pluginClass();

// Setup plugin lifecycle hooks
register_activation_hook( __FILE__, array( $plugin, 'activate' ) );
register_deactivation_hook( __FILE__, array( $plugin, 'deactivate' ) );

$plugin->run();
