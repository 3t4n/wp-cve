<?php
/**
 * Plugin Name: Freesoul Deactivate Plugins
 * Plugin URI: https://freesoul-deactivate-plugins.com/
 * Description: Disable plugins on specific pages for performance improvement and support in problem-solving even when you have many plugins.
 * Author: Jose Mortellaro
 * Author URI: https://josemortellaro.com
 * Domain Path: /languages/
 * Text Domain: freesoul-deactivate-plugins
 * Version: 2.2.1
 *
 * @package Freesoul Deactivate Plugins
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Don't run on frontend if constants defined.
if ( ! is_admin() && defined( 'FDP_EXCLUDE_FRONT' ) && FDP_EXCLUDE_FRONT && isset( $_GET[ FDP_EXCLUDE_FRONT ] ) && 'true' === $_GET[ FDP_EXCLUDE_FRONT ] ) {
	return;
}

if( defined( 'FDP_STANDARD_DISABLED' ) && FDP_STANDARD_DISABLED ){
	// Don't run if current URL is in the array defined in 'FDP_SKIP_URLS' in wp-config.php.
	return;
}

// Definitions.
define( 'EOS_DP_VERSION', '2.2.1' );
define( 'FDP_PLUGIN_FILE', __FILE__ );
define( 'EOS_DP_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'EOS_DP_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'EOS_DP_PLUGIN_BASE_NAME', untrailingslashit( plugin_basename( __FILE__ ) ) );
define( 'EOS_DP_PLUGINS_DIRNAME', basename( dirname( __DIR__ ) ) );
define( 'EOS_DP_MAIN_STYLESHEET', EOS_DP_PLUGIN_URL . '/admin/assets/css/fdp-admin-3.9.4' );
define( 'EOS_DP_MAIN_JS', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-admin-5.0.3' );
define( 'EOS_DP_SETTINGS_JS_URL', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-settings-1.1.1.js' );

require EOS_DP_PLUGIN_DIR . '/fdp-load.php'; // FDP Bootstrap file.

do_action( 'fdp_loaded' );
