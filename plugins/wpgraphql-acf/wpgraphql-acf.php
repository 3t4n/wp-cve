<?php
/**
 * Plugin Name: WPGraphQL for ACF
 * Description: WPGraphQL for ACF seamlessly integrates Advanced Custom Fields with WPGraphQL.
 * Author: WPGraphQL, Jason Bahl
 * Author URI: https://www.wpgraphql.com
 * Version: 2.1.2
 * Text Domain: wpgraphql-acf
 * Requires PHP: 7.3
 * Requires at least: 5.9
 * Tested up to: 6.2
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package  WPGraphQL\ACF
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPGraphQLAcf' ) ) {
	require_once __DIR__ . '/src/WPGraphQLAcf.php';
}

// If this file doesn't exist, the plugin was likely installed from Composer
// and the autoloader is included in the parent project
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! defined( 'WPGRAPHQL_FOR_ACF_VERSION' ) ) {
	define( 'WPGRAPHQL_FOR_ACF_VERSION', '2.1.2' );
}

if ( ! defined( 'WPGRAPHQL_FOR_ACF_VERSION_WPGRAPHQL_REQUIRED_MIN_VERSION' ) ) {
	define( 'WPGRAPHQL_FOR_ACF_VERSION_WPGRAPHQL_REQUIRED_MIN_VERSION', '1.14.4' );
}

if ( ! defined( 'WPGRAPHQL_FOR_ACF_VERSION_PLUGIN_DIR' ) ) {
	define( 'WPGRAPHQL_FOR_ACF_VERSION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! function_exists( 'graphql_acf_init' ) ) {
	/**
	 * Function that instantiates the plugins main class
	 *
	 * @return void
	 */
	function graphql_acf_init() {
		$wp_graphql_acf = new \WPGraphQLAcf();
		add_action( 'plugins_loaded', [ $wp_graphql_acf, 'init' ], 50 );
	}
}
graphql_acf_init();

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function graphql_acf_init_appsero_telemetry() {
	// If the class doesn't exist, or code is being scanned by PHPSTAN, move on.
	if ( ! class_exists( 'Appsero\Client' ) || defined( 'PHPSTAN' ) ) {
		return;
	}

	$client   = new Appsero\Client( '4988d797-77ee-4201-84ce-1d610379f843', 'WPGraphQL for Advanced Custom Fields', __FILE__ );
	$insights = $client->insights();

	// If the Appsero client has the add_plugin_data method, use it
	if ( method_exists( $insights, 'add_plugin_data' ) ) {
		// @phpstan-ignore-next-line
		$insights->add_plugin_data();
	}

	// @phpstan-ignore-next-line
	$insights->init();
}

graphql_acf_init_appsero_telemetry();
