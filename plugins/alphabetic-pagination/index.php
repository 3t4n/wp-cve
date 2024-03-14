<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/*
Plugin Name: Alphabetic Pagination
Plugin URI: http://androidbubble.com/blog/wordpress/plugins/alphabetic-pagination
Description: Alphabetic pagination is a great plugin to filter your posts/pages and WooCommerce products with alphabets. It is compatible with custom taxonomies.
Version: 3.2.0
Author: Fahad Mahmood 
Author URI: https://www.androidbubbles.com
Text Domain: alphabetic-pagination
Domain Path: /languages/
License: GPL2

This plugin is a free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or any later version. This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/ 
	
        
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        

	global $ap, $arg, $ap_implementation, $rendered, $ap_premium_link, $ap_datap, $ap_dir, $alphabets_bar, $ap_customp,
           $ap_queries, $ap_query, $css_arr, $disabled_letters, $ap_current_cat, $ap_disable, $ap_group, $rendered_alphabets_arr,
           $ap_lang, $ap_allowed_pages, $ap_query_number, $ap_post_types, $ap_all_plugins, $ap_plugins_activated, $ap_url,
           $ap_langin, $ap_js_enabled, $ap_js_func, $ap_css_link, $ap_compability_arr, $ap_auto_post_types, $ap_auto_post_statuses, $ap_wc_shortcodes,
           $ap_langs_multiple, $ap_language_selected, $ap_android_settings, $ap_settings_saved, $ap_reset_theme;
	
	function sanitize_ap_data( $input ) {
		if(is_array($input)){		
			$new_input = array();	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?sanitize_ap_data($val):sanitize_text_field( $val ));
			}			
		}else{
			$new_input = sanitize_text_field($input);			
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}
			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = esc_url_raw($new_input);
			}			
		}	
		return $new_input;
	}	
	if(!function_exists('ap_update_option')){
		function ap_update_option($name, $value=''){
			$ap_get_option = ap_get_option('all');
			$ap_get_option[$name] = $value;
			update_option('alphabetic_pagination_options', sanitize_ap_data($ap_get_option));
		}
	}	
	if(!function_exists('ap_get_option')){
		function ap_get_option($option_name='all', $value=''){
			$ap_get_option = get_option('alphabetic_pagination_options');
			$ap_get_option = is_array($ap_get_option)?$ap_get_option:array();
			
			switch($option_name){
				default:
				
					
					if(array_key_exists($option_name, $ap_get_option)){
						$value = $ap_get_option[$option_name];
					}else{
						$old_value = get_option($option_name);
						if($old_value){
							ap_update_option($option_name, $old_value);							
							//delete_option($option_name);
							$value = $old_value;
						}
					}
				break;
				
				case 'all':
					$value = $ap_get_option;
				break;
			}
			
			return $value;
		}
	}
			
	$ap_reset_theme = (in_array(ap_get_option('ap_reset_theme'), array('dark', 'light'))?ap_get_option('ap_reset_theme'):'dark');
    $ap_settings_saved = false;
	$ap_compability_arr = array();
	$ap_js_enabled = false;
	$ap_langin = $ap_js_func = $ap_css_link = array();
	$ap_url = plugin_dir_url( __FILE__ );
	$ap_all_plugins = get_plugins();
	$ap_plugins_activated = apply_filters( 'active_plugins', get_option( 'active_plugins' ));
    //second_reload
    if(!function_exists('ap_refresh_saved_settings')){
        function ap_refresh_saved_settings(){

            global $ap_allowed_pages, $ap_query_number, $ap_post_types, $ap_wc_shortcodes;
            $ap_allowed_pages = ap_get_option('ap_allowed_pages', array());
            $ap_allowed_pages = is_array($ap_allowed_pages)?$ap_allowed_pages:array();
            $ap_allowed_pages = array_filter($ap_allowed_pages, 'strlen');
            $ap_allowed_pages = is_array($ap_allowed_pages)?$ap_allowed_pages:array();
            $ap_query_number = ap_get_option('ap_query', array());
            $ap_query_number = is_array($ap_query_number)?$ap_query_number:array();
            $ap_post_types = ap_get_option('ap_post_types', array());
            $ap_post_types = is_array($ap_post_types)?$ap_post_types:array();
            $ap_wc_shortcodes = (ap_get_option('ap_wc_shortcodes')==0?false:true);

        }
    }
    ap_refresh_saved_settings();

    $ap_auto_post_types = ap_get_option('ap_auto_post_types', array());
	$ap_auto_post_types = is_array($ap_auto_post_types)?$ap_auto_post_types:array();	

	$ap_auto_post_statuses = ap_get_option('ap_auto_post_statuses', array());
	$ap_auto_post_statuses = is_array($ap_auto_post_statuses)?$ap_auto_post_statuses:array();		
	
	$ap_compability_arr['marketpress'] = array('activated'=>in_array('wordpress-ecommerce/marketpress.php', $ap_plugins_activated), 'ap_query'=>2);
	
	//print_r($ap_plugins_activated);
	//print_r($ap_post_types);
	
	$ap_lang = ap_get_option('ap_lang');
	$ap_lang = strtolower((is_array($ap_lang)?current($ap_lang):$ap_lang));
	

	
	$disabled_letters = array();
	$arg = 'ap';
	$ap_dir = plugin_dir_path( __FILE__ );
	
	$ap_lang_file = $ap_dir.'inc/languages.php';
	
	if(file_exists($ap_lang_file))
	require_once($ap_lang_file);
	
	//pree($ap_lang_file);
	
	
	$css_arr = array('');
	$ap_customp = file_exists($ap_dir.'pro/ap_extended.php');
	$ap_queries = 0;
	$ap_query = false;
	$rendered = FALSE;
	$ap_premium_link = 'https://shop.androidbubbles.com/product/alphabetic-pagination-pro';//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439507533979';//
	define('AP_CUSTOM', 'custom');
	$ap_disable = (ap_get_option('ap_disable')==0?false:true);
	$ap_group = (ap_get_option('ap_grouping')==0?false:true);

	$ap_datap = get_plugin_data(__FILE__);
	$ap = isset($_GET[$arg])?sanitize_ap_data($_GET[$arg]):'';
	
	
	include('inc/functions.php');
        
	register_activation_hook(__FILE__, 'ap_start');

	//KBD END WILL REMOVE .DAT FILES	

	register_deactivation_hook(__FILE__, 'ap_end' );



	add_action( 'admin_enqueue_scripts', 'register_ap_scripts' );
	add_action( 'wp_enqueue_scripts', 'register_ap_scripts' );
	
	//add_filter('found_posts_query', 'ap_pagination', 1);

	//pre_get_posts
	


	if($ap_customp){					
		include($ap_dir.'pro/ap_extended.php');
	}	
		
	if(is_admin()){
		add_action( 'admin_menu', 'ap_menu' );	
		add_action( 'wp_ajax_ap_tax_types', 'ap_tax_types_callback' );
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'ap_plugin_links' );	
		
		add_action( 'admin_enqueue_scripts', 'ap_admin_style', 99 );
		
		if($ap_customp)
		add_action( 'admin_enqueue_scripts', 'ap_pro_admin_style', 99 );
		
		
		
		
	}else{
		
		add_action('wp_head', 'ap_init_actions');
		$ap_implementation = ap_get_option('ap_implementation');
		
		
		
		if($ap_implementation=='' || $ap_implementation=='auto'){
			add_filter('pre_get_posts', 'ap_pagination', 1);
			//WILL WORK FROM SETTINGS
			//add_action('wp_footer', 'ap_ready');
		}

		
		add_shortcode('ap_pagination', 'ap_pagination_custom');
		add_shortcode('ap_results', 'ap_pagination_results');		
	}


	