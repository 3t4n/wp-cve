<?php
/**
 * Pronamic Mollie User Agent
 *
 * @package   PronamicMollieUserAgent
 * @author    Pronamic
 * @copyright 2023 Pronamic
 * 
 * @wordpress-plugin
 * Plugin Name: Pronamic Mollie User Agent
 * Plugin URI: https://wp.pronamic.directory/plugins/pronamic-mollie-user-agent/
 * Description: This WordPress plugin sets a specific user agent string for all HTTP API requests to Mollie for the partnership with Pronamic.
 * Version: 1.0.0
 * Requires at least: 6.1
 * Requires PHP: 8.0
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: pronamic-mollie-user-agent
 * Domain Path: /languages/
 * Update URI: https://wp.pronamic.directory/plugins/pronamic-mollie-user-agent/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoload.
 */
require_once __DIR__ . '/vendor/autoload_packages.php';

/**
 * Bootstrap.
 */
\Pronamic\MollieUserAgent\Plugin::instance()->setup();

\Pronamic\WordPress\Updater\Plugin::instance()->setup();
