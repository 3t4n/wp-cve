<?php
/*
Plugin Name: GP Social Share
Plugin URI: https://github.com/WestCoastDigital/gp-social-share
Description: Add social share icons to single posts within GeneratePress
Version: 2.2
Author: West Coast Digital
Author URI: https://westcoastdigital.com.au
Text Domain: gp-social
Domain Path: /languages
*/


require_once( plugin_dir_path( __FILE__ ) . 'inc/gp-social-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/css/gp-social-share-css.php' );

$settings = get_option('gp_social_settings');
$disable_hook = isset($settings['hook_disable']);
$gp_social_hook = isset($settings['hook_locations']) ? esc_attr($settings['hook_locations']) : 'generate_after_content';

if( !$disable_hook ) {
    add_action( $gp_social_hook, 'add_social_icons' );
}

if ( class_exists( 'WooCommerce' ) ) {
    $options = get_option( 'gp_social_settings' );
    $gp_woo_global_hook = isset($options['gp_woo_global_hook']) ? esc_attr($options['gp_woo_global_hook']) : '';
    $gp_woo_single_hook = isset($options['gp_woo_single_hook']) ? esc_attr($options['gp_woo_single_hook']) : '';
    $gp_woo_shop_hook = isset($options['gp_woo_shop_hook']) ? esc_attr($options['gp_woo_shop_hook']) : '';

    if( $gp_woo_global_hook ) {
        add_action( $gp_woo_global_hook, 'add_social_icons' );
    }
    if( $gp_woo_single_hook ) {
        add_action( $gp_woo_single_hook, 'add_social_icons' );
    }
    if( $gp_woo_shop_hook ) {
        add_action( $gp_woo_shop_hook, 'add_social_icons' );
    }
}
/** Add settings link to plugin */
function wcd_social_share_settings_link($links)
{
    $settings_link = '<a href="themes.php?page=gp-social-options-page">' . __('Settings', 'wcd') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wcd_social_share_settings_link');

function wcd_social_share_color_picker() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
	wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
}
add_action( 'admin_enqueue_scripts', 'wcd_social_share_color_picker' );