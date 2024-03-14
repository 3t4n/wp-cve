<?php
/*
Plugin Name: Fullestop Lock Down Admin
Description: Fullestop Lock Down Admin plugin secure your WordPress admin panel. It locks the wp-admin url and if this plugin is activated then user can't login in the admin panel using wp-admin/wp-login default URL's.
Version: 1.2
Author: Fullestop
Author URI: http://www.fullestop.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
/*
Fullestop Lock Down Admin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Fullestop Lock Down Admin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/*ADDING MENU OPTION IN THE ADMIN PANEL*/
    add_action('admin_menu','flda_fullestop_lock_down_action');
    if (!function_exists('flda_fullestop_lock_down_action')) {
        /**
         * flda_fullestop_lock_down_action()
         * 
         * @return
         */
        function flda_fullestop_lock_down_action() {
            
        	add_menu_page('Fullestop Lock Down Admin','Fullestop Lock Down','manage_options','fullestop-admin-lock-management','flda_fullestop_admin_lock_option_select');
            
        }
    }
/*ADDING MENU OPTION IN THE ADMIN PANEL*/



//include lock down Option fullestop_file.
if (!function_exists('flda_fullestop_admin_lock_option_select')) {
    /**
     * flda_fullestop_admin_lock_option_select()
     * 
     * @return
     */
    function flda_fullestop_admin_lock_option_select(){
    	include dirname( __FILE__ ) . '/include/fullestop_lock_down_options.php';
    	flda_fullestop_lock_admin_options();
    }
}


/***********************/

/*Hide wp-admin*/
if (!function_exists('flda_fullestop_fornt_pannel_management')) {
    /**
     * flda_fullestop_fornt_pannel_management()
     * 
     * @return
     */
    function flda_fullestop_fornt_pannel_management(){
    	
    	$fullestop_option			=		get_option('fullestop_hide_admin');
    	$fullestop_panel_admin		= 		get_option('fullestop_login_name');
    	if($fullestop_option and $fullestop_panel_admin){
    	// Nope, they didn't enable it.
    	if ( $fullestop_option == 'yes' ){
    	
    		$fullestop_no_check_files 	= 	array('async-upload.php', 'admin-ajax.php', 'wp-app.php');
    		$fullestop_no_check_files 	= 	apply_filters('fullestop_no_check_files', $fullestop_no_check_files);
    		
    		$fullestop_script_filename 	= 	empty($_SERVER['SCRIPT_FILENAME'])? $_SERVER['PATH_TRANSLATED']	: $_SERVER['SCRIPT_FILENAME'];
    		$fullestop_explode 			= 	explode('/', $fullestop_script_filename);
    		$fullestop_file				= 	end( $fullestop_explode );
    	    	
    	    	// Disable for WP-CLI
    		if ( defined('WP_CLI') AND WP_CLI )
    			return true;
    
    	    if ( in_array( $fullestop_file, $fullestop_no_check_files ) )
    			return true;
    			 
    		if ( is_admin() ){
    			// Non logged in users.
    			if ( ! is_user_logged_in() ){
    			    wp_redirect(get_site_url());
    				exit; // Exit if accessed directly
    			} 
    		}
    	}
    	/* call Rename the login URL */
    	
    		flda_fullestop_renameLogin(); 
    	}
    }
}

add_filter('init', 'flda_fullestop_fornt_pannel_management'); 


/***********************/

/**
 * Rename the login URL
**/
if (!function_exists('flda_fullestop_renameLogin')) {
    /**
     * flda_fullestop_renameLogin()
     * 
     * @return
     */
    function flda_fullestop_renameLogin()	{
    		
    	// The blog's URL
    	add_filter('wp_redirect', 'flda_fullestop_filterWpLogin');
    	add_filter('network_site_url', 'flda_fullestop_filterWpLogin');
    	add_filter('site_url', 'flda_fullestop_filterWpLogin');
    	$fullestop_blog_url		=	 trailingslashit( get_bloginfo('url') );
    
    	// The Current URL
    	$fullestop_schema 		= 	is_ssl() ? 'https://' : 'http://';
    	$fullestop_current_url 	= 	$fullestop_schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    	
    	$fullestop_request_url 	= 	str_replace( $fullestop_blog_url, '', $fullestop_current_url );
    	$fullestop_request_url 	= 	str_replace('index.php/', '', $fullestop_request_url);
    	
    	$fullestop_url_parts 	= 	explode( '?', $fullestop_request_url, 2 );
    	$fullestop_base 		= 	$fullestop_url_parts[0];
    	
    	// Remove trailing slash
    	$fullestop_base 		= 	rtrim($fullestop_base,"/");
    	$fullestop_exp 			= 	explode( '/', $fullestop_base, 2 );
    	$fullestop_super_base 	= 	end( $fullestop_exp );
    
    	// Are they visiting wp-login.php?
    	if ( $fullestop_super_base == 'wp-login.php'){
    	    wp_redirect(get_site_url());
            exit; // Exit if accessed directly
    	}
    		
    	
    	// Is this the "login" url?
    	if ( $fullestop_base !== 	flda_fullestop_getLoginBase() ){
    	   	return FALSE;
    	}
    	else{
    		include ABSPATH . '/wp-login.php';
    		exit; 
    	}
    }
}



/***********************/

if (!function_exists('flda_fullestop_filterWpLogin')) {
    /**
     * flda_fullestop_filterWpLogin()
     * 
     * @param mixed $str
     * @return
     */
    function flda_fullestop_filterWpLogin($str )
    {	
    	return str_replace('wp-login.php', flda_fullestop_getLoginBase(), $str);
    }
}




/***********************/

if (!function_exists('flda_fullestop_getLoginBase')) {
    /**
     * flda_fullestop_getLoginBase()
     * 
     * @param string $fullestop_default
     * @return
     */
    function flda_fullestop_getLoginBase($fullestop_default=''){	
    	$fullestop_login_panel	= 	get_option('fullestop_login_name');
    	$fullestop_log_panel	=	sanitize_title_with_dashes ( $fullestop_login_panel );
    	$fullestop_default 		= 	'';
    	$fullestop_panel		=	$fullestop_log_panel  ? $fullestop_log_panel : $fullestop_default;
    	
    	return $fullestop_panel;
    }
}


/***********************/
if (!function_exists('flda_fullestop_delete_lock_data')) {
    /**
     * flda_fullestop_delete_lock_data()
     * 
     * @return
     */
    function flda_fullestop_delete_lock_data(){
		$fullestop_loc_dele		=	array('fullestop_hide_admin','fullestop_login_name');
		foreach($fullestop_loc_dele as $fullestop_del_data)
			delete_option($fullestop_del_data);
	}
 }
register_uninstall_hook(__FILE__, 'flda_fullestop_delete_lock_data');