<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/**************************************************
 * Plugin Name: Nutshell Analytics
 * Description: This plugin provides Nutshell Analytics integration. Specific features may be disabled in the <a href="/wp-admin/options-general.php?page=nutshell-analytics-settings">settings</a>.
 *
 * Version: 2.4.1
 * Requires PHP: 5.4
 * Requires at least: 5.0
 * Tested up to: 6.1
 *
 * Author: Nutshell
 * Author URI: https://www.nutshell.com
 * Plugin URI: https://app.webfx.com/marketingcloudfx/dashboard
 * Text Domain: nutshell-analytics
 *
 * Settings: /wp-admin/options-general.php?page=nutshell-analytics-settings
 *
 * Release Notes: readme.txt
 *************************************************/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// *********************************************************************

// URLs
define( 'NUTSHELL_ANALYTICS_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'NUTSHELL_ANALYTICS_ASSETS_URL', NUTSHELL_ANALYTICS_PLUGIN_URL . '/assets' );
define( 'NUTSHELL_ANALYTICS_ASSETS_IMG_URL', NUTSHELL_ANALYTICS_ASSETS_URL . '/img' );

// Paths
define( 'NUTSHELL_ANALYTICS_PLUGIN_DIR', __DIR__ );
define( 'NUTSHELL_ANALYTICS_INCLUDES_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'includes' );
define( 'NUTSHELL_ANALYTICS_FEATURES_DIR', NUTSHELL_ANALYTICS_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'features' );
define( 'NUTSHELL_ANALYTICS_TEMPLATES_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'templates' );
define( 'NUTSHELL_ANALYTICS_ADMIN_TEMPLATES_DIR', NUTSHELL_ANALYTICS_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'admin' );
define( 'NUTSHELL_ANALYTICS_FRONTEND_TEMPLATES_DIR', NUTSHELL_ANALYTICS_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'frontend' );
define( 'NUTSHELL_ANALYTICS_INTEGRATIONS_DIR', NUTSHELL_ANALYTICS_FRONTEND_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'integrations' );

require_once NUTSHELL_ANALYTICS_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-nutshell-analytics.php';

// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN
