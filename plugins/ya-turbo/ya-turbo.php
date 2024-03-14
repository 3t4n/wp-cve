<?php

/**
 * Plugin Name:       Yandex turbo
 * Plugin URI:        https://wordpress.org/plugins/ya-turbo
 * Description:       Yandex Turbo модуль позволяет гибко настроить RSS 2.0. выгрузку для сервиса «Яндекс Турбо» страницы (https://yandex.ru/)
 * Version:           1.0.1
 * Author:            hardkod
 * Author URI:        https://profiles.wordpress.org/hardkod
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ya-turbo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

// Path to plugin index file
if ( ! defined( 'YATURBO_FILE' ) ) {
	define( 'YATURBO_FILE', __FILE__ );
}

// Path to plugin
if ( ! defined( 'YATURBO_PATH' ) ) {
	define( 'YATURBO_PATH', plugin_dir_path( YATURBO_FILE ) );
}

// Plugin index file base name
if ( ! defined( 'YATURBO_BASENAME' ) ) {
	define( 'YATURBO_BASENAME', plugin_basename( YATURBO_FILE ) );
}

// Current version.
if ( ! defined( 'YATURBO_VERSION' ) ) {
	define( 'YATURBO_VERSION', '1.0.0' );
}

/* Database schemas */

if ( ! defined( 'YATURBO_DB_FEEDS' ) ) {
	define( 'YATURBO_DB_FEEDS', 'ya_turbo_feed' );
}

/* Feed status */

if ( ! defined( 'YATURBO_FEED_STATUS_ACTIVE' ) ) {
	define( 'YATURBO_FEED_STATUS_ACTIVE', '1' );
}

if ( ! defined( 'YATURBO_FEED_STATUS_DISABLED' ) ) {
	define( 'YATURBO_FEED_STATUS_DISABLED', '2' );
}

/* Feed  */

if ( ! defined( 'YATURBO_FEED' ) ) {
	define( 'YATURBO_FEED', 'ya-turbo');
}

if ( ! defined( 'YATURBO_FEED_TYPE_NEWS' ) ) {
	define( 'YATURBO_FEED_TYPE_NEWS', 1);
}

if ( ! defined( 'YATURBO_FEED_TYPE_DZEN' ) ) {
	define( 'YATURBO_FEED_TYPE_DZEN', 2);
}

if ( ! defined( 'YATURBO_FEED_TYPE_TURBO' ) ) {
	define( 'YATURBO_FEED_TYPE_TURBO', 3);
}

if ( ! defined( 'YATURBO_FEED_TYPE_DEFAULT' ) && defined( 'YATURBO_FEED_TYPE_TURBO' )) {
	define( 'YATURBO_FEED_TYPE_DEFAULT', YATURBO_FEED_TYPE_TURBO);
}

/* Cache */
if ( ! defined( 'YATURBO_CACHE_TTL' ) ) {
	define( 'YATURBO_CACHE_TTL', 30);
}

if ( ! defined( 'YATURBO_FEED_LIMIT' ) ) {
	define( 'YATURBO_FEED_LIMIT', 1000);
}

/**
 * The code that runs during plugin activation.
 */
function activate_ya_turbo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ya-turbo-activator.php';
	Ya_Turbo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_ya_turbo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ya-turbo-deactivator.php';
	Ya_Turbo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ya_turbo' );
register_deactivation_hook( __FILE__, 'deactivate_ya_turbo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ya-turbo.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_ya_turbo() {

	$plugin = new Ya_Turbo();
	$plugin->run();
}
run_ya_turbo();