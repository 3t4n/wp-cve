<?php

/**
 *
 * Plugin Name:       SimpleForm
 * Plugin URI:        https://wpsform.com
 * Description:       Create a basic contact form for your website. Lightweight and very simple to manage, SimpleForm is immediately ready to use.
 * Version:           2.1.9
 * Requires at least: 5.6
 * Requires PHP:      5.6
 * Author:            WPSForm Team
 * Author URI:        https://wpsform.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simpleform
 *
 */

if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Plugin constants.
 *
 * @since    1.0
 */
 
define( 'SIMPLEFORM_NAME', 'SimpleForm' );
define( 'SIMPLEFORM_VERSION', '2.1.9' );
define( 'SIMPLEFORM_DB_VERSION', '2.1.8' );
define( 'SIMPLEFORM_PATH', plugin_dir_path( __FILE__ ) );
define( 'SIMPLEFORM_URL', plugin_dir_url( __FILE__ ) );
define( 'SIMPLEFORM_BASENAME', plugin_basename( __FILE__ ) );
define( 'SIMPLEFORM_BASEFILE', __FILE__ );
define( 'SIMPLEFORM_ROOT', dirname( plugin_basename( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 *
 * @since    1.0
 */
 
function activate_simpleform($network_wide) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	SimpleForm_Activator::activate($network_wide);
}

/** 
 * Create table when a new site into a network is created.
 *
 * @since    1.2
 */ 

function sform_on_create_blog($params) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	SimpleForm_Activator::on_create_blog($params);
}

add_action( 'wp_insert_site', 'sform_on_create_blog'); 

/**
 * The code that runs during plugin deactivation.
 *
 * @since    1.0
 */
 
function deactivate_simpleform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	SimpleForm_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simpleform' );
register_deactivation_hook( __FILE__, 'deactivate_simpleform' );

/**
 * The core plugin class.
 *
 * @since    1.0
 */
 
require plugin_dir_path( __FILE__ ) . 'includes/class-core.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
 
function run_SimpleForm() {
	$plugin = new SimpleForm();
	$plugin->run();
}

run_SimpleForm();