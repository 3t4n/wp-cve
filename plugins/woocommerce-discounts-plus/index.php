<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: Discounts Manager for Products
	Plugin URI: https://shop.androidbubbles.com/product/woocommerce-discounts-plus/
	Description: An amazing WooCommerce extension to implement multiple discount criterias with ultimate convenience.
	Author: Fahad Mahmood
	Version: 3.5.1
	Author URI: https://profiles.wordpress.org/fahadmahmood/
	Text Domain: wcdp
	Domain Path: /languages/
	License: GPL3

    Copyright (C) 2013  Fahad Mahmood

    This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
//return;
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$plugins_activated = apply_filters( 'active_plugins', get_option( 'active_plugins' ));

    if ( !in_array( 'woocommerce/woocommerce.php', $plugins_activated )) return; // Check if WooCommerce is active
	
	if(!function_exists('wcdp_pree')){
	function wcdp_pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 
	
	if(!function_exists('wcdp_pre')){
	function wcdp_pre($data){
			if(isset($_GET['debug'])){
				wcdp_pree($data);
			}
		}	 
	} 	
	
	global 
			$wdp_pro, 
			$wdp_dir,
			$wdp_url,
			$wdp_plugin_basename,
			$s2_enabled,
			$s2_pro, 
			$s2_discounts, 
			$wdp_new_price, 
			$wcdp_data, 
			$wdpp_obj, 
			$wdp_discount_condition, 
			$wdp_discount_types, 
			$wdp_halt, 
			$wdp_new_price_sp, 
			$wdp_new_price_shop, 
			$woocommerce_variations_separate, 
			$product_variations_qty,
			$wdp_pricing_scale,
			$wdp_tiers_status,
			$wdp_premium_label,
			$wdp_price_num_decimals,
			$wcdp_settings_saved,
			$wcdp_enqueue_scripts,
			$wdp_premium_link,
			$wdp_cart_total_discount;
			
	$wcdp_enqueue_scripts = false;
	$wdp_cart_total_discount = 0;
	
	$possible_post_id = ((isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['post']) && is_numeric($_GET['post']))?$_GET['post']:0);
	
	$post = (object)array('post_type'=>'');
	//pree($post);
	if(is_admin()){
		$post = get_post($possible_post_id);
	}else{
		
	}

	if(

		(isset($_GET['page']) && isset($_GET['tab']) && $_GET['page'] == 'wc-settings' && $_GET['tab'] == 'plus_discount') ||

		(isset($_GET['page']) && $_GET['page'] == 'wc_wcdp') ||
		
		(is_admin() && (is_object($post) && $post->post_type=='product')) ||
		
		(isset($_GET['page']) && $_GET['page'] == 'wdp-s2member-settings')
		
		
		
	){
		
		$wcdp_enqueue_scripts = true;

	}
	//pree($wcdp_enqueue_scripts);
	
	$wdp_premium_link = 'https://shop.androidbubbles.com/product/woocommerce-discounts-plus';//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439507632283';//
	$wdp_price_num_decimals = get_option('woocommerce_price_num_decimals', 2);
	//pree($wdp_price_num_decimals);
	$wdp_premium_check = '('.__('Go Premium for this Feature', "wcdp").')';
	$wdp_pricing_scale = false;
	$wcdp_settings_saved = false;
	$product_variations_qty = array();
	$woocommerce_variations_separate = get_option( 'woocommerce_variations_separate', 'yes' );
	$s2_pro = $wdp_halt = false;
	if(class_exists('c_ws_plugin__s2member_utils_conds')){
		$s2_pro = c_ws_plugin__s2member_utils_conds::pro_is_installed();
	}
	$wcdp_data = get_plugin_data(__FILE__);
	$s2_enabled = in_array( 's2member/s2member.php',  $plugins_activated);
	$wdp_dir = plugin_dir_path(__FILE__) ;
	$wdp_url = plugin_dir_url(__FILE__) ;
    $wdp_plugin_basename = plugin_basename(__FILE__);
	$pro_class = $wdp_dir.'pro/wdp_pro.php';
	$wdp_pro = file_exists($pro_class);
	
	$s2_discounts = get_option('wdp_s2member')?true:false;
	$wdp_discount_condition = get_option('woocommerce_plus_discount_condition', 'default');
	
	$wdp_new_price = (get_option( 'woocommerce_show_discounted_price' ) == 'yes' );
	//pre(get_option( 'woocommerce_tiers' ));
	$wdp_tiers_status = (get_option( 'woocommerce_tiers' ) == 'yes' );
	
	$wdp_new_price_sp = (get_option( 'woocommerce_show_discounted_price_sp' ) == 'yes' );
	$wdp_new_price_shop = (get_option( 'woocommerce_show_discounted_price_shop' ) == 'yes' );
	//pree($wdp_pro);

    include_once('inc/functions.php');
    include_once( 'inc/classes/wdp_core_factory.php' );
    include_once( 'inc/classes/Woo_Discounts_Plus_Plugin.php' );
	
	if($wdp_pro){
		
		include_once(realpath($pro_class));
		
		if(class_exists('Woo_Discounts_Plus_Pro'))
		$wdpp = new Woo_Discounts_Plus_Pro();

		//add_filter( 'woocommerce_short_description', 'filter_woocommerce_short_description_pro', 10, 1 );
		//add_action('admin_menu', 'wdpp_admin_menu');

	} else{

		$wdpp = new Woo_Discounts_Plus_Plugin();
		
	}

	$wdpp_obj = $wdpp;
	
	add_filter( 'woocommerce_short_description', array($wdpp, 'filter_woocommerce_short_description_free'), 10, 1 );
	//add_action('woocommerce_before_cart_totals', 'wpdp_woocommerce_cart_totals_before_order_total');
    add_action('admin_head', 'wdp_head');
	add_action('admin_init', 'wdp_admin_init');
	add_action('admin_init', 'wdp_settings_posted');
	add_action('admin_init', 'wdp_settings_posted_pro');
