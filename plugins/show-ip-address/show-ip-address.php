<?php
/*
 * Plugin name: Show IP address
 * Plugin URI: https://www.keithgriffiths.co.uk
 * Description: A simple plugin to show your IP address information on any of your pages, posts or widgets. Shows your IP address on your Dashboard.
 * Version: 1.6
 * Author: Keith Griffiths
 * Author URI: https://www.keithgriffiths.co.uk
 * Licence: GPLv2
 */
/* Copyright 2015 Keith Griffiths (email : info@keith-griffiths.com)
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2,  
   as published by the Free Software Foundation.
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
   See the GNU General Public License for more details.
*/
// Style sheet
wp_enqueue_style('custom-style', plugins_url( 'css/style-show-ip-address.css', __FILE__ ), array(),'all');

// Settings
add_action('admin_menu', 'ip_plugin_settings');
function ip_plugin_settings() {
    add_menu_page('IP Address Settings', 'Show IP Info', 'administrator', 'show-ip-address', 'ip_address_display_settings');
}
function ip_address_display_settings(){
        echo '<h1>Show IP Address</h1> <div>A simple plugin to show your IP address on any of your pages, posts or widgets. <br>Shows your IP address on your dashboard from any location.</div> <hr> <div>The idea is simple, give it a go, let me know what you think of this plugin. Any suggested updates <br> I\'ll consider in each build <a href="https://www.keithgriffiths.co.uk/contact/">click here</a> to contact me anytime.</div>';
		echo '<h2>Use on pages, widgets or posts</h2> <hr> <div>Let\'s say a user lands on your page or post, and you want to show them there IP address. <br> <h3>You could use</h3> What\'s my IP address: [show_ip]</div> <br>';
		echo '<strong>Q)</strong>. What\'s the point in showing a person there IP address? <br> <strong>A)</strong>. You may want to create a secure page are on your website and show the user their IP address.<hr>';
        echo '<br><br><br><br>Thank you for using my plugin - Show IP address V.1.6 - Last Updated: 19-05-2023';
}
if(is_admin())
	{	
		function ip_dashboard_widget_function()
		{
			// admin ip address
			$admin_ip_address = $_SERVER['REMOTE_ADDR'];
			if(!$admin_ip_address)
				$admin_ip_address = 'unknown';
			
			// admin hostname
			$admin_hostname = @gethostbyaddr($admin_ip_address);
			if(!$admin_hostname OR $admin_hostname == $admin_ip_address)
				$admin_hostname = 'Not known';
			// display information
			echo '<div style="display:table; width: 100%;">';	
			echo '<div style="display:table-cell;"><big><strong>'.$admin_ip_address.'</strong></big></div>';					
			if($admin_hostname != 'unknown')
				echo '<div style="display:table-cell; text-align: right;"><small>('.__('hostname', 'admin-ip-address').' : '.$admin_hostname.')</small></div>';
			echo "</div>\n\n";
			echo '<div class="box-ip"><hr> Your IP address is something you might rarely think about, it\'s important to know what your IP address is and when it changes. Your IP address is used to identify computers on the Internet. <hr> Want to know more about IP addresses, or to get the latest updates on this plugin, go to the "Show IP Info" using the menu on the left side. <hr>Show IP address. Latest version 1.6</div>';
		}
		// function dashboard widget
		function ip_add_dashboard_widgets()
		{
			wp_add_dashboard_widget('ip_dashboard_widget', __('Your IP Address & Hostname', 'admin-ip-address'), 'ip_dashboard_widget_function');
		}
		add_action('wp_dashboard_setup', 'ip_add_dashboard_widgets' );
	}		
		
if(is_admin())
	{
		// add a Contact link on your plugin page
		function user_ip_contact_link($links)
		{ 
			$contact_link = '<a href="https://www.keithgriffiths.co.uk/" target="_blank">Contact Developer</a>'; 
			//array_unshift($links, $contact_link);
			array_push($links, $contact_link); 
			return $links; 
		}
		
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'user_ip_contact_link' );
	}
	function get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	//check ip from the web
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	//check ip from proxy
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpb_get_ip', $ip );
	}
	add_shortcode('show_ip', 'get_the_user_ip'); 
?>