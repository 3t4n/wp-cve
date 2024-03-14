<?php
/*
 * Plugin Name:  DD Simple Maintenance & Coming Soon Mode - Clean, Easy & 100% Free
 * Plugin URI: https://github.com/akshansh1998/dd-simplest-maintenance
 * Description: A Light Weight Free & Simple WordPress Maintenance Plugin, as Plugin is Activated, It Displays a maintenance mode page for anyone who's not logged in.  
 * Version: 2.6
 * Author: Ankush Anand
 * Author URI: https://github.com/akshansh1998
 * License: GPLv2 or later
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * @package dd-simplest-maintenance-mode
 * @copyright Copyright (c) 2021, Ankush Anand


*/

/**
 * DD Simplest Maintenance Mode
 *
 * Simple Maintenance/ Coming soon Page
 * Displays the coming soon page for anyone who's not logged in.
 * The login page gets excluded so that you can login if necessary.
 *
 * @return void
 */

// Main Function of Plugin

function ddsmm_maintenance_mode()
{
	global $pagenow;
	if ($pagenow !== 'wp-cron.php' && $pagenow !== 'wp-login.php' && $pagenow !== 'xmlrpc.php' && !current_user_can('edit_posts') && !is_admin()) {
		header("HTTP/1.1 503 Service Unavailable");
		header("Status: 503 Service Unavailable");
		if (file_exists(plugin_dir_path(__FILE__) . 'views/maintenance.php')) {
			require_once(plugin_dir_path(__FILE__) . 'views/maintenance.php');
		}
		die();
	}
}

// Adding Link
add_filter('plugin_row_meta', 'ddsmm_Donate', 10, 2);

function ddsmm_Donate($links, $file)
{
	if (plugin_basename(__FILE__) == $file) {
		$row_meta = array(
			'Support Author'    => '<a href="' . esc_url('https://ankushanand.com/donate/') . '" target="_blank" aria-label="' . esc_attr__('Plugin Additional Links', 'domain') . '" style="color:green;">' . esc_html__('Support Author', 'domain') . '</a>'
		);

		return array_merge($links, $row_meta);
	}
	return (array) $links;
}

// Admin Notice - Maintenance Mode is Active

function ddsmm_maintenance_notice()
{
	
	if(current_user_can('administrator')){
?>
	<div class="notice notice-success">
		<p><?php
			$site_url = get_site_url();


			_e('Maintenance Mode is <b>Active</b>! You can Turn it off by <a href="' . $site_url . '/wp-admin/plugins.php">deactivating</a> the DD Simplest Maintenance Mode Plugin', ''); ?></p>
	</div>
<?php
}
elseif (!current_user_can('administrator') && current_user_can('edit_posts')) {
	?>
	<div class="notice notice-success">
		<p><?php
			$site_url = get_site_url();
			$admin_email = get_option('admin_email');


			_e('The Site is currently under maintenance mode, which means general visitors and subscribers will not be able to see the website. <a href="mailto:'. $admin_email .'"> Contact Admin</a>, if you wish to turn off maintenance mode', ''); ?></p>
	</div>
<?php
}


}


add_action('admin_notices', 'ddsmm_maintenance_notice');
add_action('wp_loaded', 'ddsmm_maintenance_mode');
