<?php
/**
 * A simple, light-weight collection of tools to harden WordPress security and help mitigate common types of attacks.
 *
 * @package Apocalypse Meow
 * @version 21.7.5
 *
 * @wordpress-plugin
 * Plugin Name: Apocalypse Meow
 * Version: 21.7.5
 * Plugin URI: https://wordpress.org/plugins/apocalypse-meow/
 * Description: A simple, light-weight collection of tools to harden WordPress security and help mitigate common types of attacks.
 * Text Domain: apocalypse-meow
 * Domain Path: /languages/
 * Author: Blobfolio, LLC
 * Author URI: https://blobfolio.com/
 * License: WTFPL
 * License URI: http://www.wtfpl.net
 */

// phpcs:disable SlevomatCodingStandard.Namespaces

/**
 * Do not execute this file directly.
 */
if (! defined('ABSPATH')) {
	exit;
}

// ---------------------------------------------------------------------
// Setup
// ---------------------------------------------------------------------

// Constants.
define('MEOW_VERSION', '21.7.5');
define('MEOW_MIN_PHP', '7.2.0');
define('MEOW_PLUGIN_DIR', __DIR__ . '/');
define('MEOW_INDEX', __FILE__);
define('MEOW_BASE_CLASS', 'blobfolio\\wp\\meow\\');
define('MEOW_URL', 'https://blobfolio.com/plugin/apocalypse-meow/');
define('MEOW_API', 'https://shitlist.blobfolio.com/1/');
define('MEOW_EMAIL', 'orders@blobfolio.com');

// Is this installed as a Must-Use plugin?
$meow_must_use = (
	defined('WPMU_PLUGIN_DIR') &&
	@is_dir(WPMU_PLUGIN_DIR) &&
	(0 === strpos(MEOW_PLUGIN_DIR, WPMU_PLUGIN_DIR))
);
define('MEOW_MUST_USE', $meow_must_use);

// Now the URL root.
if (! MEOW_MUST_USE) {
	define(
		'MEOW_PLUGIN_URL',
		preg_replace('/^https?:/i', '', trailingslashit(plugins_url('/', MEOW_INDEX)))
	);
}
else {
	define(
		'MEOW_PLUGIN_URL',
		preg_replace(
			'/^https?:/i',
			'',
			trailingslashit(str_replace(
				WPMU_PLUGIN_DIR,
				WPMU_PLUGIN_URL,
				MEOW_PLUGIN_DIR
			))
		)
	);
}

// --------------------------------------------------------------------- end setup



// ---------------------------------------------------------------------
// Requirements
// ---------------------------------------------------------------------

// If the server doesn't meet the requirements, load the fallback
// instead.
if (
	version_compare(PHP_VERSION, MEOW_MIN_PHP) < 0 ||
	(function_exists('is_multisite') && is_multisite()) ||
	(! function_exists('hash_algos') || ! in_array('sha512', hash_algos(), true)) ||
	(! extension_loaded('bcmath') && ! extension_loaded('gmp')) ||
	! extension_loaded('date') ||
	! extension_loaded('filter') ||
	! extension_loaded('json') ||
	! extension_loaded('pcre')
) {
	require MEOW_PLUGIN_DIR . 'bootstrap-fallback.php';
	return;
}

// --------------------------------------------------------------------- end requirements



// Otherwise we can continue as normal!
require MEOW_PLUGIN_DIR . 'bootstrap.php';
