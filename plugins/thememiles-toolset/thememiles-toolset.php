<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Thememiles Toolset
Description: Import Thememiles official Themes Demo Content, widgets and theme settings with just one click. 
Version:     1.1.2
Author:      Thememiles
Author URI:  http://www.thememiles.com
License:     GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: thememiles-toolset
*/

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
define( 'THEMEMILES_TOOLSET_PATH', plugin_dir_path( __FILE__ ) );
define( 'THEMEMILES_TOOLSET_PLUGIN_NAME', 'thememiles-toolset' );
define( 'THEMEMILES_TOOLSET_VERSION', '1.1.2' );
define( 'THEMEMILES_TOOLSET_URL', plugin_dir_url( __FILE__ ) );
define( 'THEMEMILES_TOOLSET_TEMPLATE_URL', THEMEMILES_TOOLSET_URL.'inc/demo/' );

require THEMEMILES_TOOLSET_PATH . 'inc/init.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if( !function_exists( 'run_thememiles_toolset')){

    function run_thememiles_toolset() {

        return Thememiles_Toolset::instance();
    }
    run_thememiles_toolset()->run();
}


