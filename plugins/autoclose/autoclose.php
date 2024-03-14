<?php
/**
 * Auto-Close Comments, Pingbacks and Trackbacks
 *
 * Automatically close Comments, Pingbacks and Trackbacks. Manage and delete revisions.
 *
 * @package AutoClose
 * @author  Ajay D'Souza
 * @license GPL-2.0+
 * @link    https://webberzone.com/
 * @copyright 2008-2022 Ajay D'Souza
 *
 * @wordpress-plugin
 * Plugin Name: Auto-Close Comments, Pingbacks and Trackbacks
 * Plugin URI:  https://webberzone.com/plugins/autoclose/
 * Description: Automatically close Comments, Pingbacks and Trackbacks. Manage and delete revisions.
 * Version:     2.2.0
 * Author:      Ajay D'Souza
 * Author URI:  https://webberzone.com/
 * Text Domain: autoclose
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/ajaydsouza/autoclose/
 */

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}


/**
 * Holds the AutoClose plugin version
 *
 * @since 2.2.0
 *
 * @var string Plugin version
 */
if ( ! defined( 'ACC_PLUGIN_VERSION' ) ) {
	define( 'ACC_PLUGIN_VERSION', '2.2.0' );
}

/**
 * Holds the filesystem directory path (with trailing slash) for AutoClose
 *
 * @since 2.0.0
 *
 * @var string Plugin folder path
 */
if ( ! defined( 'ACC_PLUGIN_DIR' ) ) {
	define( 'ACC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Holds the filesystem directory path (with trailing slash) for AutoClose
 *
 * @since 2.0.0
 *
 * @var string Plugin folder URL
 */
if ( ! defined( 'ACC_PLUGIN_URL' ) ) {
	define( 'ACC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Holds the filesystem directory path (with trailing slash) for AutoClose
 *
 * @since 2.0.0
 *
 * @var string Plugin Root File
 */
if ( ! defined( 'ACC_PLUGIN_FILE' ) ) {
	define( 'ACC_PLUGIN_FILE', __FILE__ );
}

/*
 *---------------------------------------------------------------------------*
 * AutoClose modules
 *---------------------------------------------------------------------------*
 */

require_once ACC_PLUGIN_DIR . 'includes/admin/class-settings-api.php';
require_once ACC_PLUGIN_DIR . 'includes/admin/class-autoclose-settings.php';
require_once ACC_PLUGIN_DIR . 'includes/admin/options-api.php';
require_once ACC_PLUGIN_DIR . 'includes/activation.php';
require_once ACC_PLUGIN_DIR . 'includes/main.php';
require_once ACC_PLUGIN_DIR . 'includes/comments.php';
require_once ACC_PLUGIN_DIR . 'includes/revisions.php';
require_once ACC_PLUGIN_DIR . 'includes/cron.php';
require_once ACC_PLUGIN_DIR . 'includes/l10n.php';
require_once ACC_PLUGIN_DIR . 'includes/helpers.php';


/*
 *---------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *---------------------------------------------------------------------------*
 */

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

	require_once ACC_PLUGIN_DIR . 'includes/admin/tools-page.php';

}

/*
 *---------------------------------------------------------------------------*
 * Deprecated functions
 *---------------------------------------------------------------------------*
 */

require_once ACC_PLUGIN_DIR . 'includes/deprecated.php';


/**
 * Get Settings.
 *
 * Retrieves all plugin settings
 *
 * @since  2.0.0
 * @return array AutoClose settings
 */
function acc_get_settings() {

	$settings = get_option( 'acc_settings', acc_settings_defaults() );

	/**
	 * Settings array
	 *
	 * Retrieves all plugin settings
	 *
	 * @since 2.0.0
	 * @param array $settings Settings array
	 */
	return apply_filters( 'acc_get_settings', $settings );
}

/**
 * Global variable holding the current settings for AutoClose
 *
 * @since 2.0.0
 *
 * @var array
 */
global $acc_settings;
$acc_settings = acc_get_settings();
