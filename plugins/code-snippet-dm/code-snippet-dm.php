<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              devmaverick.com
 * @since             1.0.0
 * @package           Code_Snippet_Dm
 *
 * @wordpress-plugin
 * Plugin Name:       Code Snippet DM
 * Plugin URI:
 * Description:       Display your code snippets in a stylish way inside your content. The plugin uses shortcodes and also very intuitive TinyMCE interface.
 * Version:           2.0.3
 * Author:            George Cretu
 * Author URI:        devmaverick.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       code-snippet-dm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CSDM_PLUGIN_NAME_VERSION', '2.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-code-snippet-dm-activator.php
 */
function csdm_activate_code_snippet_dm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-code-snippet-dm-activator.php';
	CSDM_Activator::csdm_activate();
}

function csdm_dm_code_snippet_dm_code_snippet_block_init() {   
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'csdm_dm_code_snippet_dm_code_snippet_block_init' );

function csdm_dm_code_snippet_enqueue_to_block() {
    $plugin_name = 'code-snippet-dm';

    wp_enqueue_style( $plugin_name . '-main-min', plugin_dir_url( __FILE__ ) . 'public/css/main.min.css', array(), CSDM_PLUGIN_NAME_VERSION, 'all' );

    wp_enqueue_script( $plugin_name . '-dm-clipboard', plugin_dir_url( __FILE__ ) . 'public/js/clipboardv201.min.js', array( 'jquery' ), CSDM_PLUGIN_NAME_VERSION, false );
    wp_enqueue_script( $plugin_name . '-dm-prism', plugin_dir_url( __FILE__ ) . 'public/js/prism.js', array( 'jquery' ), CSDM_PLUGIN_NAME_VERSION, false );

    wp_enqueue_script( $plugin_name . '-dm-manually-start-prism', plugin_dir_url( __FILE__ ) . 'public/js/manually-start-prism.js', array( 'jquery' ), CSDM_PLUGIN_NAME_VERSION, false );
    wp_enqueue_script( $plugin_name, plugin_dir_url( __FILE__ ) . 'public/js/code-snippet-dm-public.js', array( 'jquery' ), CSDM_PLUGIN_NAME_VERSION, false );
}
add_action( 'enqueue_block_assets', 'csdm_dm_code_snippet_enqueue_to_block' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-code-snippet-dm-deactivator.php
 */
function csdm_deactivate_code_snippet_dm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-code-snippet-dm-deactivator.php';
	CSDM_Deactivator::csdm_deactivate();
}

register_activation_hook( __FILE__, 'csdm_activate_code_snippet_dm' );
register_deactivation_hook( __FILE__, 'csdm_deactivate_code_snippet_dm' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-code-snippet-dm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function csdm_run_code_snippet_dm() {

	$plugin = new CSDM_Code_Snippet_Dm();
	$plugin->csdm_run();

}
csdm_run_code_snippet_dm();

function register_elementor_code_snipped_widget( $widgets_manager ) {

	require_once( __DIR__ . '/elementor-widgets/code-snippet.php' );

	$widgets_manager->register( new \Elementor_DM_Code_Snippet() );
}
add_action( 'elementor/widgets/register', 'register_elementor_code_snipped_widget' );
