<?php 
/*
Plugin Name: Goolytics - Simple Google Analytics
Version: 1.1.2
Plugin URI: https://wordpress.org/plugins/goolytics-simple-google-analytics/
Description: A simple Google Analytics solution that works without slowing down your WordPress installation.
Author: Oliver Schl&ouml;be
Author URI: https://www.schloebe.de/
Text Domain: goolytics-simple-google-analytics
Domain Path: /languages

Copyright 2013-2022 Oliver Schlöbe (email : scripts@schloebe.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The main plugin file
 *
 * @package WordPress_Plugins
 * @subpackage Goolytics
 */
 
/**
 * Define the plugin version
 */
define("GOOLYTICSVERSION", "1.1.1");

/**
 * Define the global var GOOLYTICSMINWP, returning bool if at least WP 3.0 is running
 */
define('GOOLYTICSMINWP', version_compare($GLOBALS['wp_version'], '2.9.999', '>'));


/** 
* The Goolytics class
*
* @package 		WordPress_Plugins
* @subpackage 	Goolytics
* @since 		1.0
* @author 		scripts@schloebe.de
*/
class Goolytics {
	
	/**
 	* _NAMESPACE is used mainly for gettext purposes
 	*/
	const _NAMESPACE = 'goolytics';
	
	/**
 	* _SETTINGS_AUTH_LEVEL controls who can see the plugin's options page
 	*/
	const _SETTINGS_AUTH_LEVEL = 'manage_options';
	
	
	/**
 	* The Goolytics class constructor
 	* initializing required stuff for the plugin
 	* 
	* PHP 5 Constructor
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/		
	function __construct() {
		$this->textdomain_loaded = false;
		
		if ( !GOOLYTICSMINWP ) {
			add_action('admin_notices', array(&$this, 'require_wpversion_message'));
			return;
		}
		
		if( get_option('goolytics_web_property_id') == '' )
			add_action('admin_notices', array(&$this, 'user_setup_notice'));
		
		add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);
		
		add_action('plugins_loaded', array(&$this, 'load_textdomain'));
		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'admin_menu_goolytics'));
		
		if (!is_admin())
			add_action('wp_head', array(&$this, 'print_code'));
	}
	
	
	/**
 	* The Goolytics class constructor
 	* initializing required stuff for the plugin
 	* 
	* PHP 4 Compatible Goolytics
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function Goolytics() {
		$this->__construct();
	}
	
	
	/**
 	* Initialize and load the plugin stuff for the admin area
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function admin_init() {
		global $pagenow;
		
		if ( !function_exists("add_action") ) return;
		
		register_setting(self::_NAMESPACE, 'goolytics_web_property_id', array(
			'type' => 'string',
			'sanitize_callback'	=> array(&$this, 'sanitize_web_property_id')
		));
		register_setting(self::_NAMESPACE, 'goolytics_anonymize_ip');
		register_setting(self::_NAMESPACE, 'goolytics_usercentrics_support');
		
		if( $pagenow == 'options-general.php' && isset( $_GET['page'] ) && $_GET['page'] == 'goolytics' )
			require_once( trailingslashit(dirname (__FILE__)) . 'inc/authorplugins.inc.php');
	}
	
	
	/**
 	* Adds the admin menu
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function admin_menu_goolytics() {
		add_options_page('Goolytics - Simple Google Analytics', 'Goolytics', self::_SETTINGS_AUTH_LEVEL, self::_NAMESPACE, array(&$this, 'options_page_goolytics'));
	}
	
	
	/**
 	* Generates the admin menu
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function options_page_goolytics() {
		include( trailingslashit( dirname( __FILE__ ) ) . 'inc/options.php' );
	}
	
	
	/**
 	* Prints the Google Analyitcs code for the frontend
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function print_code() {
		$web_property_id = get_option('goolytics_web_property_id');
		$anonymize_ip = get_option('goolytics_anonymize_ip');
		$usercentrics_support = get_option('goolytics_usercentrics_support');
		
		$usercentrics_additional = '';
		if( $usercentrics_support ) {
			$usercentrics_additional = ' type="text/plain" data-usercentrics="Google Analytics"';
		}

		$code = '<!-- Goolytics - Simple Google Analytics Begin -->' . PHP_EOL;
		$code .= '<script' . $usercentrics_additional . ' async src="//www.googletagmanager.com/gtag/js?id=' . $web_property_id . '"></script>' . PHP_EOL;
		$code .= "<script" . $usercentrics_additional . ">";
		$code .= "window.dataLayer = window.dataLayer || [];" . PHP_EOL;
		$code .= "function gtag(){dataLayer.push(arguments);}" . PHP_EOL;
		$code .= "gtag('js', new Date());" . PHP_EOL;
		$code .= PHP_EOL;
		if( $anonymize_ip ) {
			$code .= "gtag('config', '" . $web_property_id . "', { 'anonymize_ip': true });" . PHP_EOL;
		} else {
			$code .= "gtag('config', '" . $web_property_id . "');" . PHP_EOL;
		}
		$code .= "</script>" . PHP_EOL;
		$code .= "<!-- Goolytics - Simple Google Analytics End -->" . PHP_EOL;

		if( $web_property_id != '' )
			echo PHP_EOL . $code . PHP_EOL;
	}
	
	
	/**
 	* Initialize and load the plugin textdomain
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function load_textdomain() {
		if($this->textdomain_loaded) return;
		load_plugin_textdomain('goolytics-simple-google-analytics', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		$this->textdomain_loaded = true;
	}
	
	
	/**
 	* Adds Settings link to the action links on plugin listing panel
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
	* @param		array $links
	* @param		string $file
	* @return		array $links
 	*/
	function plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename(__FILE__) ) {
			$settings_link = '<a href="' . menu_page_url('goolytics', false) . '">' . __('Settings', 'goolytics-simple-google-analytics') .'</a>' ;
			array_unshift($links, $settings_link);
		}
		
		return $links;
	}
	
	
	/**
 	* Points the user to setup the plugin after activation
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function user_setup_notice() {
		echo "<div id='wpversionfailedmessage' class='updated settings-error'><p>" . sprintf(__('Thanks for activating Goolytics - Simple Google Analytics! Now head to the <a href="%s">settings page</a>, finish setting up the plugin and you are good to go!', 'goolytics-simple-google-analytics'), menu_page_url('goolytics', false)) . "</p></div>";
	}
	
	
	/**
 	* Checks for the version of WordPress,
 	* and adds a message to inform the user
 	* if required WP version is less than 2.6
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function require_wpversion_message() {
		echo "<div id='wpversionfailedmessage' class='error fade'><p>" . __('Goolytics - Simple Google Analytics requires at least WordPress 3.0!', 'goolytics-simple-google-analytics') . "</p></div>";
	}
	
	
	/**
 	* Sanitize web_property_id input
 	*
 	* @since 		1.1.2
 	* @author 		scripts@schloebe.de
 	*/
	function sanitize_web_property_id( $input ) {
		if( preg_match('/^[A-Z][A-Z0-9]?-[A-Z0-9]{4,10}(?:\-[1-9]\d{0,3})?$/', $input) ) {
			return $input;
		} else {
			if( empty($input) ) {
				add_settings_error(
					'goolytics_web_property_id',
					'goolytics_web_property_id',
					__('The Google Analytics ID is empty.', 'goolytics-simple-google-analytics'),
					'error'
				);
			} else {
				add_settings_error(
					'goolytics_web_property_id',
					'goolytics_web_property_id',
					__('The Google Analytics ID you entered is invalid. Please check your input and try again.', 'goolytics-simple-google-analytics'),
					'error'
				);
			}
			return '';
		}
	}
	
}

new Goolytics;
?>