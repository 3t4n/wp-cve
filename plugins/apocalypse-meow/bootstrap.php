<?php
/**
 * Apocalypse Meow - Bootstrap
 *
 * Set up the environment.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! \defined('ABSPATH')) {
	exit;
}

use blobfolio\wp\meow\admin;
use blobfolio\wp\meow\ajax;
use blobfolio\wp\meow\options;

// Bootstrap.
// phpab -e "./node_modules/*" -o ./lib/autoload.php .
require \MEOW_PLUGIN_DIR . 'lib/autoload.php';

// So many actions!
\add_action('admin_enqueue_scripts', array(\MEOW_BASE_CLASS . 'admin', 'enqueue_scripts'));
\add_action('admin_init', array(\MEOW_BASE_CLASS . 'admin', 'privacy_policy'));
\add_action('admin_notices', array(\MEOW_BASE_CLASS . 'admin', 'update_notice'));
\add_action('admin_notices', array(\MEOW_BASE_CLASS . 'admin', 'warnings'));
\add_action('init', array(\MEOW_BASE_CLASS . 'core', 'init'));
\add_action('init', array(\MEOW_BASE_CLASS . 'login', 'init'));
\add_action('plugins_loaded', array(\MEOW_BASE_CLASS . 'admin', 'localize'));
\add_action('plugins_loaded', array(\MEOW_BASE_CLASS . 'admin', 'server_name'));
\add_action('plugins_loaded', array(\MEOW_BASE_CLASS . 'db', 'check'));
\add_action('plugins_loaded', array(\MEOW_BASE_CLASS . 'hooks', 'init'));

// WP-CLI functions.
if (\defined('WP_CLI') && \WP_CLI) {
	require \MEOW_PLUGIN_DIR . 'lib/blobfolio/wp/meow/cli.php';
}

// A few things run once at first install.
\register_activation_hook(\MEOW_INDEX, array(\MEOW_BASE_CLASS . 'db', 'check'));

// And a couple things we can go ahead and run right away.
admin::register_menus();
ajax::init();

// Admin user columns.
\add_filter('manage_users_columns', array(\MEOW_BASE_CLASS . 'admin', 'users_columns'));
\add_filter('manage_users_sortable_columns', array(\MEOW_BASE_CLASS . 'admin', 'users_sortable_columns'));
\add_filter('manage_users_custom_column', array(\MEOW_BASE_CLASS . 'admin', 'users_custom_column'), 10, 3);

// Load bcrypt pluggable overrides, but only if needed.
if (
	! \function_exists('wp_check_password') &&
	! \function_exists('wp_hash_password') &&
	options::get('password-bcrypt')
) {
	require \MEOW_PLUGIN_DIR . 'lib/blobfolio/wp/meow/bcrypt.php';
}
