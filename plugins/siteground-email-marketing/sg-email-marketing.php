<?php
/**
 * SG Email Marketing
 *
 * @package           SG_Email_Marketing
 * @author            SiteGround
 * @link              http://www.siteground.com/
 *
 * @wordpress-plugin
 * Plugin Name:       SiteGround Email Marketing
 * Plugin URI:        https://siteground.com
 * Description:       Use this plugin to link your WordPress site with the SiteGround Email Marketing service and seamlessly grow your mailing list!
 * Version:           1.2.0
 * Author:            SiteGround
 * Author URI:        https://www.siteground.com
 * Text Domain:       siteground-email-marketing
 * Domain Path:       /languages
 */

// Our namespace.
namespace SG_Email_Marketing;

use SG_Email_Marketing\Loader\Loader;
use SG_Email_Marketing\Activator\Activator;
use SG_Email_Marketing\Deactivator\Deactivator;
use Dotenv;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define version constant.
if ( ! defined( __NAMESPACE__ . '\VERSION' ) ) {
	define( __NAMESPACE__ . '\VERSION', '1.2.0' );
}

// Define slug constant.
if ( ! defined( __NAMESPACE__ . '\PLUGIN_SLUG' ) ) {
	define( __NAMESPACE__ . '\PLUGIN_SLUG', 'siteground-email-marketing' );
}

// Define root directory.
if ( ! defined( __NAMESPACE__ . '\DIR' ) ) {
	define( __NAMESPACE__ . '\DIR', __DIR__ );
}

// Define root URL.
if ( ! defined( __NAMESPACE__ . '\URL' ) ) {
	$root_url = \trailingslashit( DIR );

	// Sanitize directory separator on Windows.
	$root_url = str_replace( '\\', '/', $root_url );

	$wp_plugin_dir = str_replace( '\\', '/', WP_PLUGIN_DIR );
	$root_url      = str_replace( $wp_plugin_dir, \plugins_url(), $root_url );

	define( __NAMESPACE__ . '\URL', \untrailingslashit( $root_url ) );

	unset( $root_url );
}

require_once \SG_Email_Marketing\DIR . '/vendor/autoload.php';

$env_file_path = \SG_Email_Marketing\DIR . '/.env';

if ( file_exists( $env_file_path ) ) {
	$dotenv = Dotenv\Dotenv::createImmutable( \SG_Email_Marketing\DIR );
	$dotenv->load();
}
register_activation_hook( __FILE__, array( new Activator(), 'activate' ) );
register_deactivation_hook( __FILE__, array( new Deactivator(), 'deactivate' ) );

// Initialize the loader.
global $sg_email_marketing_loader;

if ( ! isset( $sg_email_marketing_loader ) ) {
	$sg_email_marketing_loader = new Loader();
}
