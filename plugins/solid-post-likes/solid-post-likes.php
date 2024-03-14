<?php
/*
 * Plugin Name:       Solid Post Likes
 * Plugin URI:        https://wordpress.org/plugins/solid-post-likes
 * Description:       A like button for all post types. Solid and simple.
 * Version:           1.0.8
 * Author:            oacsTudio
 * Author URI:        https://oacstudio.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oaspl
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Friendly advice:  namespace declarations in root plugin file will eat plugin settings links functions that don't use namespaces ;).
function oacs_spl_myplugin_settings_link( $links ) {
    $url = get_admin_url() . 'admin.php?page=crb_carbon_fields_container_oacs_spl.php';
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'oaspl') . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'oacs_spl_myplugin_settings_link');


/**
 * Plugin version. - https://semver.org
 *
 */
define( 'SOLID_POST_LIKES_VERSION', '1.0.6' );

/**
 * Activation
 */

function activate_solid_post_likes() {
	// OACS\SolidPostLikes\Controllers\App\SolidPostLikesActivator::activate();
}


/**
 * Deactivation.
 */
function deactivate_solid_post_likes() {
	// OACS\SolidPostLikes\Controllers\App\SolidPostLikesDeactivator::deactivate();
}

/**
 * Deinstallation.
 */
function deinstall_solid_post_likes() {

}

/**
* Watch the Namespace syntax. Shoutout:
* https://developer.wordpress.org/reference/functions/register_activation_hook/#comment-2167
*/
// register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_solid_post_likes' );
// register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate_solid_post_likes' );
// register_uninstall_hook( __FILE__, __NAMESPACE__ . '\deinstall_solid_post_likes' );
/**
* Instead of: register_activation_hook( __FILE__, 'activate_solid_post_likes' );
*/


// include the Composer autoload file
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Engage.
 */
function run_solid_post_likes() {

	$plugin = new OACS\SolidPostLikes\Controllers\App\SolidPostLikesPlugin();
	// $plugin = new SolidPostLikesPlugin();
	$plugin->run();

}
run_solid_post_likes();