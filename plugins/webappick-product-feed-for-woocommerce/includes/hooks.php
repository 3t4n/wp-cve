<?php
/**
 * Default Hooks
 * @package WooFeed
 * @subpackage WooFeed_Helper_Functions
 * @version 1.0.0
 * @since WooFeed 3.3.0
 * @author KD <mhamudul.hk@gmail.com>
 * @copyright WebAppick
 */

if ( ! defined( 'ABSPATH' ) ) {
	die(); // Silence...
}
/** @define "WOO_FEED_FREE_ADMIN_PATH" "./../admin/" */ // phpcs:ignore

// Admin Page Form Actions.

// The Editor.
//add_filter( 'woo_feed_parsed_rules', 'woo_feed_filter_parsed_rules', 10, 2 );
//
//// Mics.
//
//// Product Loop Start.
//add_action( 'woo_feed_before_product_loop', 'woo_feed_apply_hooks_before_product_loop', 10, 2 );
//add_action( 'woo_feed_after_product_loop', 'woo_feed_remove_hooks_before_product_loop', 10, 2 );
//
//// In The Loop
//add_filter( 'woo_feed_product_type_separator', 'woo_feed_product_taxonomy_term_separator', 10, 2 );
//add_filter( 'woo_feed_tags_separator', 'woo_feed_product_taxonomy_term_separator', 10, 2 );
//add_filter( 'woo_feed_get_availability_attribute', 'woo_feed_get_availability_attribute_filter', 10, 3 );
//
//// Discounted price filter // Move to CTXFeed\V5\Compatibility\DynamicDiscount
//add_filter( 'woo_feed_filter_product_sale_price', 'woo_feed_get_dynamic_discounted_product_price', 9, 4 );
//add_filter( 'woo_feed_filter_product_sale_price_with_tax', 'woo_feed_get_dynamic_discounted_product_price', 9, 4 );
//
//// Price With Tax
//add_filter( 'woo_feed_price_with_tax', 'woo_feed_get_price_with_tax', 9, 2);
//
//// Product Loop End.
//add_action( 'woo_feed_after_product_loop', 'woo_feed_remove_hooks_after_product_loop', 10, 2 );
//
//// Exclude Feed files from caching.
//add_filter( 'rocket_cdn_reject_files', 'woo_feed_exclude_feed_from_wp_rocket_cache', 10, 3 );//WP Rocket Cache
//add_action( 'litespeed_init', 'woo_feed_exclude_feed_from_litespeed_cache', 10, 0);//LiteSpeed Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_wp_fastest_cache', 10, 0);//WP Fastest Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_wp_super_cache', 10, 0);//WP Super Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_breeze_cache', 10, 0);//BREEZE Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_wp_optimize_cache', 10, 0);//WP Optimize Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_cache_enabler_cache', 10, 0);//Cache Enabler Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_swift_performance_cache', 10, 0);//Cache Enabler Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_speed_booster_cache', 10, 0);//Cache Enabler Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_comet_cache', 10, 0);//Cache Enabler Cache
//add_action("admin_init", 'woo_feed_exclude_feed_from_hyper_cache', 10, 0);//Cache Enabler Cache
////add_filter( 'w3tc_save_options', 'woo_save_w3tc_opt', 10, 3 );//TODO
//
//
//
//#==== MERCHANT TEMPLATE OVERRIDE START ==============
//add_filter( 'woo_feed_get_attribute', 'woo_feed_modify_google_color_attribute_value', 9, 4 );
//add_filter( 'woo_feed_get_weight_attribute', 'woo_feed_modify_weight_attribute_value', 9, 3 );
//add_filter( 'woo_feed_get_bestprice_product_type_attribute', 'woo_feed_get_bestprice_categoryPath_attribute_value_modify', 9, 3 );
//add_filter( 'woo_feed_get_availability_attribute', 'woo_feed_availability_attribute_value_modify', 9, 3 );
//add_filter( 'woo_feed_get_type_attribute', 'woo_feed_spartoo_attribute_value_modify', 9, 3 );
//add_filter( 'woo_feed_get_pinterest_rss_date_created_attribute', 'woo_feed_get_pinterest_rss_date_attribute_callback', 9, 3 );
//add_filter( 'woo_feed_get_pinterest_rss_date_updated_attribute', 'woo_feed_get_pinterest_rss_date_attribute_callback', 9, 3 );
//add_filter( 'woo_feed_filter_product_description', 'woo_feed_filter_product_description_callback', 1, 3 );
//add_filter( 'woo_feed_filter_product_yoast_wpseo_metadesc', 'woo_feed_filter_product_description_callback', 1, 3 );
//add_filter( 'woo_feed_filter_product_rank_math_description', 'woo_feed_filter_product_description_callback', 1, 3 );
//add_filter( 'woo_feed_filter_product_aioseop_description', 'woo_feed_filter_product_description_callback', 1, 3 );
//add_filter( 'woo_feed_filter_product_title', 'woo_feed_filter_product_title', 1, 3 );
//add_filter( 'woo_feed_filter_product_parent_title', 'woo_feed_filter_product_title', 1, 3 );
//add_filter( 'woo_feed_filter_product_yoast_wpseo_title', 'woo_feed_filter_product_title', 1, 3 );
//add_filter( 'woo_feed_filter_product_rank_math_title', 'woo_feed_filter_product_title', 1, 3 );
//add_filter( 'woo_feed_filter_product_aioseop_title', 'woo_feed_filter_product_title', 1, 3 );
//#==== MERCHANT TEMPLATE OVERRIDE END ================
//
//#==== NOTICE HOOKS START ==============
//add_action( 'woocommerce_after_product_attribute_settings', 'woo_feed_add_product_attribute_is_highlighted', 10, 2 );
//add_action( 'wp_ajax_woocommerce_save_attributes', 'woo_feed_ajax_woocommerce_save_attributes', 0 );
//
////add_action( 'transition_post_status', 'woo_feed_publish_product', 10, 3 );
//
////add_action( 'wcml_saved_mc_options', 'woo_feed_saved_mc_options' );
//
////add_action( 'wp_ajax_wcml_save_currency', 'woo_feed_wcml_save_currency', 10 );
//
//#==== NOTICE HOOKS END ================
//
//// End of file hooks.php.
