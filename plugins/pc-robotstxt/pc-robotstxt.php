<?php
/*
Plugin Name: Virtual Robots.txt
Version: 1.10
Plugin URI: http://infolific.com/technology/software-worth-using/robots-txt-plugin-for-wordpress
Description: Automatically creates a virtual robots.txt file for your site.
Author: Marios Alexandrou
Author URI: http://infolific.com/technology/
License: GPLv2 or later
Text Domain: pc-robotstxt
*/

/*
Copyright 2016 Marios Alexandrou

Originally developed by Peter Coughlin

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class pc_robotstxt {

	function __construct() {
		
		// make sure we have the right paths...
		if ( !defined( 'WP_PLUGIN_URL' ) ) {
			if ( !defined( 'WP_CONTENT_DIR' ) ) define( 'WP_CONTENT_DIR', ABSPATH.'wp-content' );
			if ( !defined( 'WP_CONTENT_URL' ) ) define( 'WP_CONTENT_URL', get_option( 'siteurl' ).'/wp-content' );
			if ( !defined( 'WP_PLUGIN_DIR' ) ) define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins' );
			define( 'WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins' );
		}// end if

		// stuff to do when the plugin is loaded
		// i.e. register_activation_hook(__FILE__, array(&$this, 'activate'));
		// i.e. register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );

		// add_filter('hook_name', 'your_filter', [priority], [accepted_args]);
		// i.e. add_filter('the_content', array(&$this, 'filter'));
		
		// add_action ('hook_name', 'your_function_name', [priority], [accepted_args]);
		// i.e. add_action('wp_head', array(&$this, 'action'));

		// only if we're public
		if ( get_option( 'blog_public' ) ) {
			remove_action( 'do_robots', 'do_robots' );
			add_action( 'do_robots', array(&$this, 'do_robots' ) );
		}// end if

		//add quick links to plugins page
		$plugin = plugin_basename( __FILE__ );
		if ( is_admin() )
			add_filter( "plugin_action_links_$plugin", array( &$this, 'settings_link' ) );

	}// end function

	function activate() {
		// stuff to do when the plugin is activated
	}// end function
	
	function deactivate() {
		// stuff to do when plugin is deactivated
		// i.e. delete_option('pc_robotstxt');
		$options = $this->get_options();
		if ( $options['remove_settings'] )
			delete_option( 'pc_robotstxt' );
	}// end function
	
	function settings_link($links) {
		$settings_link = '<a href="options-general.php?page=pc-robotstxt/admin.php">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}// end function
	
	function do_robots() {
		
		if ( is_robots() ) {
			
			$options = $this->get_options();

			$output = "# This virtual robots.txt file was created by the Virtual Robots.txt WordPress plugin: https://www.wordpress.org/plugins/pc-robotstxt/\n";

			if ( '' != $options['user_agents'] ) {
				$output .= stripcslashes( $options['user_agents'] );
			}
				
			// if there's an existing sitemap file or we're using pc-xml-sitemap plugin add a reference..
			$protocol = ( ( !empty($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";
			if ( file_exists( $_SERVER['DOCUMENT_ROOT'].'/sitemap.xml.gz' ) ) {
				$output .= "\n\n".'Sitemap: '.$protocol.$_SERVER['HTTP_HOST'].'/sitemap.xml.gz';
			} elseif ( class_exists('pc_xml_sitemap' ) || file_exists($_SERVER['DOCUMENT_ROOT'].'/sitemap.xml' ) ) {
				$output .= "\n\n".'Sitemap: '.$protocol.$_SERVER['HTTP_HOST'].'/sitemap.xml';
			}
		
			header('Status: 200 OK', true, 200);
			header('Content-type: text/plain; charset=' . get_bloginfo('charset'));
			echo $output;
			exit;

		}// end if
		
	}// end function
	
	function get_options() {
		$options = get_option( 'pc_robotstxt' );
		if ( !is_array( $options ) )
			$options = $this->set_defaults();
		return $options;
	}// end function
	
	function set_defaults() {
		$options = array(
			'user_agents' => "User-agent: *\n"
				."Disallow: /wp-admin/\n"
				."Allow: /wp-admin/admin-ajax.php\n"
				."Disallow: /wp-includes/\n"
				."Allow: /wp-includes/js/\n"
				."Allow: /wp-includes/images/\n"
				."Disallow: /trackback/\n"
				."Disallow: /wp-login.php\n"
				."Disallow: /wp-register.php",
			'remove_settings' => false
		);
		update_option( 'pc_robotstxt', $options );
		return $options;
	}// end function

}// end class
$pc_robotstxt = new pc_robotstxt;

if ( is_admin() ) {
	include_once dirname( __FILE__ ).'/admin.php';
}