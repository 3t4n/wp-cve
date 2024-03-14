<?php
/*
Plugin Name: Ad Invalid Click Protector
Plugin URI: https://wordpress.org/plugins/ad-invalid-click-protector/
Description: A WordPress plugin to protect your AdSense ads from unusual click bombings and invalid clicks
Author: Saumya Majumder, Acnam Infotech
Author URI: https://acnam.com/
Version: 1.2.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aicp
Domain Path: /languages
*/

/*
Copyright 2012-2022 by Saumya Majumder 

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

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Let's include the all the setup files */
if( ! class_exists( 'AICP_SETUP' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'inc/setup.php';
}
if( ! class_exists( 'AICP_ADMIN' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'inc/admin_setup.php';
}

/* Define Constants */
define( 'AICP_BASE', plugin_basename(__FILE__) );
define( 'AICP_DIR_URL', plugin_dir_url( __FILE__ ) );

/* Let's handle the plugin setup */
register_activation_hook( __FILE__, array( 'AICP_SETUP', 'on_activation' ) );
register_uninstall_hook( __FILE__, array( 'AICP_SETUP', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'AICP', 'get_instance' ) );
/* Main Class for AICP aka Ad Invalid Click Protector */
if( ! class_exists( 'AICP' ) ) {
	class AICP {
		/*--------------------------------------------*
	     * Attributes
	     *--------------------------------------------*/
	  
	    /** Refers to a single instance of this class. */
	    private static $instance = null;
	    private static $refresh_check = 0;
	     
	    /* Saved options */
	    public $table_name;
	  
	    /*--------------------------------------------*
	     * Constructor
	     *--------------------------------------------*/
	  
	    /**
	     * Creates or returns an instance of this class.
	     *
	     * @return  AICP A single instance of this class.
	     */
	    public static function get_instance() {
	  
	        if ( null == self::$instance ) {
	            self::$instance = new self;
	        }
	  
	        return self::$instance;
	    } // end get_instance;
	  
	    /**
	     * Initializes the plugin by setting localization, filters, and administration functions.
	     */
	    public function __construct() {
	    	global $wpdb;
	    	//Set the Table name
	    	$this->table_name = $wpdb->prefix . 'adsense_invalid_click_protector';

	    	// Let's load the styles and scripts now
	    	add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	    	add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
	    	add_action( 'wp_ajax_process_data', array( $this, 'process_data' ) );
				add_action( 'wp_ajax_nopriv_process_data', array( $this, 'process_data' ) );

				$aicpAdminOBJ = new AICP_ADMIN();
				// Handeling the calls for wp-admin side
				add_action( 'plugins_loaded', array( $aicpAdminOBJ, 'table_structure_update' ) );
				add_action( 'admin_enqueue_scripts', array( $aicpAdminOBJ, 'admin_scripts' ) );
				add_action( 'wp_dashboard_setup', array( $aicpAdminOBJ, 'aicp_dashboard' ) );
				/* First lets initialize an admin settings link inside WP dashboard */
				/* It will show under the SETTINGS section */
				add_action( 'admin_menu', array( $aicpAdminOBJ, 'create_admin_menu' ) );
				// Register page options
				add_action( 'admin_init', array( $aicpAdminOBJ, 'register_page_options' ) );
				// Admin notice
				add_action( 'admin_notices', array( $aicpAdminOBJ, 'show_admin_notice' ) );
				// Welcome Donate Notice
				add_action( 'wp_ajax_handle_aicp_donate_notice', array( $aicpAdminOBJ, 'handle_aicp_donate_notice' ) );
				add_action( 'wp_ajax_nopriv_handle_aicp_donate_notice', array( $aicpAdminOBJ, 'handle_aicp_donate_notice' ) );
	    	// Hourly cleanup job to delete blocked users which is more than 7 days
	    	add_action( 'aicp_hourly_cleanup', array( $aicpAdminOBJ, 'do_this_hourly' ) );
	    	// Adding settings link to the installed plugin page
	    	add_filter( "plugin_action_links_" . AICP_BASE, array( $aicpAdminOBJ, 'plugin_add_settings_link' ) );
	    }
	  
	    /*--------------------------------------------*
	     * Functions
	     *--------------------------------------------*/
	    
	    /**
	     * Function to load the text domain
	     * @return Nothing
	    **/
	    public function load_textdomain() {
	    	load_plugin_textdomain( 'aicp', false, basename( dirname( __FILE__ ) ) . '/languages/' ); 
	    }

	    /**
	     * Function to fetch visitor's IP address
	     * @return Visitor's IP address
	    **/
	    public function visitor_ip() {
	    	$ipArr = array();

	    	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_ORIGINATING_IP', 'HTTP_X_REMOTE_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ) as $key ) {
		        if ( array_key_exists( $key, $_SERVER ) === true ) {
		            foreach ( explode( ',', $_SERVER[$key] ) as $ip ) {
		                $ip = trim( $ip ); // just to be safe

		                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
		                	array_push($ipArr, $ip);
		                    //return $ip;
		                }
		            }
		        }
		    }

		    $currentIP = '';
			$actualIP = '';

			foreach ($ipArr as $ip) {
				if($ip !== $_SERVER['SERVER_ADDR']) {
					if($currentIP !== $ip) {
						$currentIP = $ip;
						$actualIP = $ip;
					} 
				}
			}

			return $actualIP;
	    }

	    /**
	     * Function to fetch visitor's country
	     * @return Visitor's Country Name & Country Code  (returns Array)
	    **/
	    public function visitor_country( $ip ) {
	    	$aicpAdminOBJ = new AICP_ADMIN();
	    	$aicpAdminOBJ->fetch_data();
	    	$ipapi_pro_key = trim( $aicpAdminOBJ->ipapi_pro_key );
	    	if( $aicpAdminOBJ->ipapi_pro_check == 'Yes' && !empty( $ipapi_pro_key ) ) { // For the paid IP-API users
	    		$locQuery = @unserialize( file_get_contents( 'https://pro.ip-api.com/php/' . $ip . '?key=' . $ipapi_pro_key ) );
	    	} else { // for the free IP-API users
	    		$locQuery = @unserialize( file_get_contents( 'http://ip-api.com/php/'.$ip ) );
	    	}
	    	if( $locQuery && $locQuery['status'] == 'success' ) {
				$visitor_country['name'] = $locQuery['country'];
				$visitor_country['code'] = $locQuery['countryCode'];
				return $visitor_country;
			} else {
				return false; //some problem happened with the IP API Call
			}
	    }

	    /**
	     * Function to load styles & scripts
	     * @return Nothing
	    **/
	    public function load_scripts() {
	    	/* Create an Object of the AICP_ADMIN class to fetch some data */
	    	$aicpAdminOBJ = new AICP_ADMIN();
	    	/* Call the Fetch Data method from the admin class to get the updated result */
	    	$aicpAdminOBJ->fetch_data();

	    	/* JS */
	    	wp_register_script( 'js-cookie', plugins_url( '/assets/js/js.cookie.min.js' , __FILE__ ), array(), '3.0.0', true );
	    	wp_enqueue_script( 'js-cookie' );
	    	wp_register_script( 'js-iframe-tracker', plugins_url( '/assets/js/jquery.iframetracker.min.js' , __FILE__ ), array( 'jquery' ), '2.1.0', true );
	    	wp_enqueue_script( 'js-iframe-tracker' );

	    	wp_register_script( 'aicp', plugins_url( '/assets/js/aicp.min.js' , __FILE__ ) , array( 'jquery', 'js-cookie', 'js-iframe-tracker' ), '1.0', true );
	    	wp_enqueue_script( 'aicp' );
	    	wp_localize_script( 
	    		'aicp', //id
	    		'AICP', // The name using which data will be fetched at the JS side
	    		array( 
		    		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		    		'nonce' => wp_create_nonce( "aicp_nonce" ),
		    		'ip' => $this->visitor_ip(),
		    		'clickLimit' => $aicpAdminOBJ->click_limit,
		    		'clickCounterCookieExp' => $aicpAdminOBJ->click_counter_cookie_exp,
		    		'banDuration' => $aicpAdminOBJ->ban_duration,
		    		'countryBlockCheck' => $aicpAdminOBJ->country_block_check,
		    		'banCountryList' => $aicpAdminOBJ->ban_country_list
	    		) // all data that are being passed to the js file
	    	);
	    }

	    /**
	     * Function to process the data via the jQuery AJAX call
	     * @return Nothing
	    **/
	    public function process_data() {
	    	global $wpdb;
	    	check_ajax_referer( 'aicp_nonce', 'nonce' );

	    	//Let's grab the data from the AJAX POST request
	    	$ip = sanitize_text_field( $_POST['ip'] );
	    	$countryName = sanitize_text_field( $_POST['countryName'] );
	    	$countryCode = sanitize_text_field( $_POST['countryCode'] );
	    	$clickCount = sanitize_text_field( $_POST['aicp_click_count'] );

	    	//Now it's time to insert the data into the database
	    	$wpdb->insert( 
				$this->table_name, 
				array( 
					'ip' => $ip,
					'click_count' => $clickCount,
					'timestamp' => current_time( 'mysql' )
				) 
			);
	    }
	} // AICP Class Ends
} //end of checking if AICP class exists

/**
 * Global PHP Function to check if the current visitor is
 * blocked from seeing the ads or not.
 * @return true (if the visitor IP is blocked) / false (otherwise)
**/
if( ! function_exists( 'aicp_can_see_ads' ) ) {
	function aicp_can_see_ads() {
		global $wpdb;
		$flag = 0;
		$aicpOBJ = new AICP();
		$visitorIP = $aicpOBJ->visitor_ip();

		$match = $wpdb->get_var( "SELECT COUNT(id) FROM $aicpOBJ->table_name WHERE ip = '$visitorIP'" );

		$fetched_data = get_option( 'aicp_settings_options' );
		$blocked_countries = trim( $fetched_data['ban_country_list'] );
		$blocked_country = explode( ',', $blocked_countries );

		if( $fetched_data["country_block_check"] == 'Yes' ) {
			$country_data = $aicpOBJ->visitor_country( $aicpOBJ->visitor_ip() );
			$visitor_country = $country_data['code'];
		}

		//This section will run when the country ban is enabled
		if( ( !empty( $blocked_countries ) ) && $fetched_data["country_block_check"] == 'Yes' ) {
			foreach ( $blocked_country as $key => $value ) {
				if( trim( $value ) == $visitor_country ) {
					$flag++;
				}
			}
			if( $flag > 0 ) { // This means that the user is visiting the site from a banned country
				return false; // No visitor cannot see ads as he is in our block list
			} else { // This means that the user is not visiting from a banned country
				if( $match > 0 ) { //So, it's time to check if the visitor's IP is blocked or not
					return false; // No visitor cannot see ads as he is in our block list
				} else {
					return true; // Yes, he can
				}
			}
		} else {
			//This section will run when there is no country ban, so there is only IP based ban
			if( $match > 0 ) {
				return false; // No visitor cannot see ads as he is in our block list
			} else {
				return true; // Yes, he can
			}
		}
	} // end of function aicp_can_see_ads
} //end of checking if aicp_can_see_ads function already exists