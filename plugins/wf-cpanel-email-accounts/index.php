<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;

/**
 * Plugin Name:     	WebFacing™ - Email Accounts management for cPanel®
 * Description:     	🕸️ By WebFacing™. Lets you manage all email accounts, forwarders, autoresponders and also backup your hosting account. Let users manage their own email account, from admin or frontend, just by clicking a button.
 * Plugin URI:      	https://webfacing.eu/
 * Version:         	5.2.6
 * Author:          	Knut Sparhell
 * Author URI:      	https://profiles.wordpress.org/knutsp/
 * License:         	GPLv3
 * License URI:     	https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP:    	7.4
 * Requires at least:   6.3
 * Tested up to:    	6.4.1
 * Tested up to PHP:	8.2
 * Text Domain:     	wf-cpanel-email-accounts
 */

/**
 * Exit if accessed directly
 */
\defined( 'ABSPATH' ) || exit;

/**
 * Define non-magic constants inside the namespace pointing to this main plugin dir and file
 */
const PLUGIN_DIR  = __DIR__;

const PLUGIN_FILE = __FILE__;

require_once 'compat-functions.php';
require_once 'includes/Main.php';
include_once 'includes/utils.php';
require_once 'includes/UAPI.php';
require_once 'includes/ShortCode.php';

if ( \is_admin() ) {

	if ( ! \function_exists( 'WP_Filesystem' ) ) {
		require_once ( \ABSPATH . '/wp-admin/includes/file.php' );
	}

	if ( ! \class_exists( 'WP_List_Table' ) ) {
		require_once \ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}
	require_once 'includes/AccountsTable.php';
	require_once 'includes/BoxesTable.php';
	require_once 'includes/BackupsTable.php';
	require_once 'includes/TokensTable.php';
	require_once 'includes/AccountsPage.php';
	require_once 'includes/NewEmail.php';
	require_once 'includes/BoxesPage.php';
	require_once 'includes/ContactsPage.php';
	require_once 'includes/BackupsPage.php';
	require_once 'includes/TokensPage.php';
	require_once 'includes/SiteHealth.php';
}

if ( ! \function_exists( 'get_plugin_data' ) ) {
	require_once \ABSPATH . 'wp-admin/includes/plugin.php';
}
Main::load();

if ( \is_admin() ) {
	Main::admin();
}
