<?php

/**
 * The plugin bootstrap file
 *
 * @link              wpautosave@gmail.com
 * @since             1.0.0
 * @package           Wp_Autosave
 *
 * @wordpress-plugin
 * Plugin Name:       wp-autosave
 * Description:       Auto-save your post to draft at regular intervals
 * Version:           1.1.1
 * Author:            wp-autosave team
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-autosave
 * Domain Path:       /languages
 */


// The code that disable autosave function from wp core.
add_action( 'wp_print_scripts', 'autosave_disable_wp_save' );
function autosave_disable_wp_save(){
    wp_deregister_script( 'autosave' );
}

// The code that add action for js editor script
add_action( 'edit_form_after_title', 'autosave_add_editor_script' );
function autosave_add_editor_script(){
    wp_register_script( 'wp-autosave-editor-script', plugins_url( '/public/js/wp-autosave-editor-script.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'wp-autosave-editor-script' );
}

// The code that pass options values to javascript
function autosave_pass_options ( $data ) {
    $str = '<script type="text/javascript">';
    $str .= 'var optionsArray = ';
    $str .= json_encode( $data );
    $str .= '; </script>';
    echo $str;
}

// Function that set and parse options from wp-admin-console
add_action( 'edit_form_after_title', 'autosave_set_options' );
function autosave_set_options(){	
    /* default values */
    $defaults = array(
        'time_mark' => 1,
        'interval'  => 300,
        'type_save' => 1,
    );
	$options = get_option( 'wp-autosave' );
    $options = wp_parse_args( $options, $defaults );
    autosave_pass_options( $options );
}


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-autosave-activator.php
 */
function activate_wp_autosave() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-autosave-activator.php';
	Wp_Autosave_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-autosave-deactivator.php
 */
function deactivate_wp_autosave() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-autosave-deactivator.php';
	Wp_Autosave_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_autosave' );
register_deactivation_hook( __FILE__, 'deactivate_wp_autosave' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-autosave.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_autosave() {

	$plugin = new Wp_Autosave();
	$plugin->run();

}
run_wp_autosave();
