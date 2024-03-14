<?php
/**
 * Plugin Name: WooCommerce Media API
 * Description: Media endpoint for WooCommerce API. Upload and list media files by WooCommerce REST API.
 * Author: woopos
 * Author URI: https://woopos.com
 * Version: 2.8
 * License: GPL2 or later
 */
if( !defined( 'ABSPATH' ) ) exit;

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
	
class WooCommerce_Media_API_By_WooPOS{
	
	public function __construct(){
		add_action( 'rest_api_init', array( $this, 'register_routes' ) , 15 );
	}
	
	public function register_routes(){
		global $wp_version;
		if ( version_compare( $wp_version, 4.4, '<' )) {
			return;
		}
		
		require_once( __DIR__ . '/class-woocommerce-media-api-controller.php' );
		require_once( __DIR__ . '/class-woocommerce-metadata-api-controller.php' );
		require_once( __DIR__ . '/class-woocommerce-list-items-api-controller.php' );
		$api_classes = array(
			'WC_REST_WooCommerce_Media_API_By_WooPOS_Controller',
			'WC_REST_WooCommerce_Metadata_API_By_WooPOS_Controller',
			'WC_REST_List_Items_API_By_WooPOS_Controller'
		);
		foreach ( $api_classes as $api_class ) {
			$controller = new $api_class();
			$controller->register_routes();
		}
	}
}

new WooCommerce_Media_API_By_WooPOS();

function filter_woopos_modify_after_query($request) {
    $request['date_query'][0]['column'] = 'post_modified';
    return $request;
}

add_filter("woocommerce_rest_orders_prepare_object_query", 'filter_woopos_modify_after_query');
add_filter("woocommerce_rest_product_object_query", 'filter_woopos_modify_after_query');

function action_woopos_update_profile_modified( $user_id ) {
  update_user_meta( $user_id, 'woopos_profile_updated', current_time( 'mysql' ) );
}
add_action( 'profile_update', 'action_woopos_update_profile_modified' );


function action_woopos_update_variation_stock_quantity( $variation ){
	$product = wc_get_product( $variation->get_parent_id());
	update_post_meta($product->get_id(),'woopos_product_last_set_stock', current_time( 'mysql' ));
}
add_action( 'woocommerce_variation_set_stock', 'action_woopos_update_variation_stock_quantity' );

function action_woopos_update_stock_quantity( $product ){
	update_post_meta($product->get_id(),'woopos_product_last_set_stock', current_time( 'mysql' ));
}
add_action( 'woocommerce_product_set_stock', 'action_woopos_update_stock_quantity' );

