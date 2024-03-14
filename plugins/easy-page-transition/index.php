<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://carlile.design
 * @since             1.0.1
 * @package           Easy_Page_Transition
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Page Transition
 * Description:       The easiest solution to add page transitions to your WordPress site..
 * Version:           1.0.1
 * Author:            Carlile Design
 * Author URI:        https://carlile.design
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-page-transition
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
define( 'EASY_PAGE_TRANSITION_VERSION', '1.0.1' );



include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active('easy-page-transition/index.php')) {

  $dir = plugin_dir_path( __FILE__ );

  //Adding Admin Settings
  require_once($dir.'admin/settings.php');

  //Adding Frontend code
  require_once($dir.'public/frontend/frontend.php');

  //Add styles to admin settings
  function cd_ept_admin_stylesheet() {
      wp_enqueue_style( 'EasyPageTransitionAdminStyles', plugins_url( 'admin/lib/css/styles.css', __FILE__ ) );
  }
  add_action('admin_print_styles', 'cd_ept_admin_stylesheet');


  //Adding Settings link on plugin list page.
  add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'cd_ept_add_plugin_page_settings_link');
  function cd_ept_add_plugin_page_settings_link( $links ) {
  	$links[] = '<a href="' .
  		admin_url( 'admin.php?page=easy-page-transition' ) .
  		'">' . __('Settings') . '</a>';
  	return $links;
  }


}
