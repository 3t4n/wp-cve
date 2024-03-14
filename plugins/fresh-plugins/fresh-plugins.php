<?php
/**
 * Plugin Name: Fresh Plugins - WP Fix It
 * Plugin URI: https://www.wpfixit.com
 * Description: Fresh Plugins is a simple plugin to allow plugins re-installation, by using WordPress standard plugin update process.  Use this plugin to install a fresh copy of any plugins that are on your site from the WordPress.org plugin repo.  The plugin installed will be the newest version of the plugin and delete and replace your current version.
 * Author: WP Fix It
 * Author URI: https://www.wpfixit.com
 * Version: 2.2
 *
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Copyright (c) 2016 - 2017 WP Fix It.  All rights reserved.
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-force-reinstall-activator.php
 */
function activate_force_reinstall()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-force-reinstall-activator.php';
	Force_Reinstall_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-force-reinstall-deactivator.php
 */
function deactivate_force_reinstall()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-force-reinstall-deactivator.php';
	Force_Reinstall_Deactivator::deactivate();
}
register_activation_hook(__FILE__, 'activate_force_reinstall');
register_deactivation_hook(__FILE__, 'deactivate_force_reinstall');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-force-reinstall.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_force_reinstall()
{
	$plugin = new Force_Reinstall();
	$plugin->run();
}
run_force_reinstall();
/* Activate the plugin and do something. */
function wpfi_fresh_plugin_action_links( $links ) {
    echo '<style>span#p-icon{width:23px!important}span#p-icon:before{width:32px!important;font-size:23px!important;color:#3B657D!important;background:0 0!important;box-shadow:none!important}</style>';
$links = array_merge( array(
'<a href="https://www.wpfixit.com/" target="_blank">' . __( '<b><span id="p-icon" class="dashicons dashicons-update"></span>  <span style="color:#f99568">GET HELP</span></b>', 'textdomain' ) . '</a>'
), $links );
return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpfi_fresh_plugin_action_links' );
/* Activate the plugin and do something. */
register_activation_hook( __FILE__, 'refresh_welcome_message' );
function refresh_welcome_message() {
set_transient( 'refresh_welcome_message_notice', true, 5 );
}
add_action( 'admin_notices', 'refresh_welcome_message_notice' );
function refresh_welcome_message_notice(){
/* Check transient, if available display notice */
if( get_transient( 'refresh_welcome_message_notice' ) ){
?>
<div class="updated notice is-dismissible">
	<style>div#message {display: none}</style>
<p>&#127881; <strong>WP Fix It - Fresh Plugins</strong> has been activated and you can now refresh your plugins the easy way.
</div>
<?php
/* Delete transient, only display this notice once. */
delete_transient( 'refresh_welcome_message_notice' );
}
}