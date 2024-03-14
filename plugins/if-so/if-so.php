<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.if-so.com/
 * @since             1.0.0
 * @package           IfSo
 *
 * @wordpress-plugin
 * Plugin Name:       If-So
 * Plugin URI:        http://www.if-so.com/
 * Description:       Display different content to different visitors. Simple to use, just select a condition and set content accordingly.
 * Version:           1.8.0.1
 * Author:            If So Plugin
 * Author URI:        http://www.if-so.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       if-so
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-if-so-activator.php
 */
function activate_if_so() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-if-so-activator.php';
	If_So_Activator::activate();
    create_ifso_tables();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-if-so-deactivator.php
 */
function deactivate_if_so() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-if-so-deactivator.php';
	If_So_Deactivator::deactivate();
}
function uninstall_if_so() {
    //Actual uninstall routine is located in uninstall.php
	//require_once plugin_dir_path( __FILE__ ) . 'includes/class-if-so-uninstall.php';
	//If_So_Uninstall::uninstall();
}
function create_ifso_tables() {
	require_once plugin_dir_path( __FILE__ ) . 'extensions/ifso-tables/ifso-table-creator.php';
}
register_activation_hook( __FILE__, 'activate_if_so' );
register_deactivation_hook( __FILE__, 'deactivate_if_so' );
register_uninstall_hook(__FILE__, 'uninstall_if_so');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-if-so.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_if_so_plugin() {
	$plugin = new If_So();
	$plugin->run();
}
run_if_so_plugin();
// wrap function for do_shortcode
function ifso($id,$atts=null,$return=false) {
    $atts_str = '';
    if(!empty($atts) && is_array($atts)){
        foreach ($atts as $key=>$val){
            $atts_str.= "{$key}='{$val}' ";
        }
    }
	$shortcode = sprintf( '[ifso id="%1$d" %2$s]', $id,$atts_str);

    if($return)
        return $shortcode;
    else
	    echo do_shortcode($shortcode);
}
//Create If-So tables
register_activation_hook( __FILE__, 'ifso_jal_install' );

