<?php
/**
 * Plugin Name: Social LikeBox & Feed
 * Version: 3.1.4
 * Description: Display the Facebook Feed and Like box on your website. Its completely customizable, responsive and search engine optimization feeds  and like-box contents.
 * Author: Weblizar
 * Author URI: https://www.weblizar.com
 * Plugin URI: https://www.weblizar.com/plugins/
 */

/*** Constant Values & Variables ***/
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

define("WEBLIZAR_FACEBOOK_PLUGIN_URL", plugin_dir_url(__FILE__));
define("WEBLIZAR_FACEBOOK_TEXT_DOMAIN", "wl_facebook");

/*** Get Ready Plugin Translation ***/
add_action('plugins_loaded', 'FacebookTranslation');
function FacebookTranslation() {
    load_plugin_textdomain(WEBLIZAR_FACEBOOK_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

/*** Facebook By Weblizar Menu ***/
add_action('admin_menu', 'WeblizarFacebookMenu');
function WeblizarFacebookMenu() {
    $adminmenu = add_menu_page(esc_html__('Social LikeBox & Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN), esc_html__('Social LikeBox & Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN), 'manage_options', 'facebooky-by-weblizar', 'facebooky_by_weblizar_page_function', 'dashicons-facebook-alt');

    //add hook to add styles and scripts for coming soon admin page
    add_action('admin_print_styles-' . $adminmenu, 'facebooky_by_weblizar_page_function_js_css');
}
function facebooky_by_weblizar_page_function() {
    require_once("function/facebook-by-weblizar-data.php");
    require_once("function/facebook-by-weblizar-help.php");
}
function facebooky_by_weblizar_page_function_js_css() {
    wp_enqueue_script('jquery');
    wp_register_script('popper', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'js/popper.min.js');
	wp_enqueue_script('popper');
    wp_register_script('bootstrap', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'js/bootstrap.min.js');
	wp_enqueue_script('bootstrap');
    wp_enqueue_script('weblizar-tab', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'js/option-js.js', array('jquery', 'media-upload', 'jquery-ui-sortable'));
    wp_enqueue_style('weblizar-option-style', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/weblizar-option-style.css');
    wp_register_style('bootstrap', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/bootstrap.min.css');
	wp_enqueue_style('bootstrap');
    wp_register_style('font-awesome', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/all.min.css');
	wp_enqueue_style('font-awesome');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
}
function weblizar_feed_code_script() {
    global $post;
    if (isset($post->post_content) && is_singular(array('post', 'page')) && has_shortcode($post->post_content, 'facebook_feed') || is_active_widget(false, false, 'weblizar_facebook_feed_widget')) {
        wp_enqueue_script('jquery');
        wp_enqueue_style('font-awesome', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/all.min.css');
        wp_enqueue_style('feed-facebook-feed-shortcode', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/facebook-feed-shortcode.css');
        wp_enqueue_style('feed-facebook-custom-box-slider', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/custom-box-slider.css');
        wp_enqueue_style('bootstrap', WEBLIZAR_FACEBOOK_PLUGIN_URL . 'css/bootstrap.min.css');
    }
}
add_action('wp_enqueue_scripts', 'weblizar_feed_code_script');

/*Plugin Setting Link*/
function weblizar_plugin_add_settings_link($links) {
    $fbw_pro_link = '<a href="https://weblizar.com/plugins/facebook-feed-pro/" target="_blank">Get Premium</a>';
    $settings_link = '<a href="admin.php?page=facebooky-by-weblizar">' . esc_html__('Settings', WEBLIZAR_FACEBOOK_PLUGIN_URL) . '</a>';
    array_unshift($links, $settings_link);
    array_unshift($links, $fbw_pro_link);
    return $links;
}
$plugin_fbw = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_fbw", 'weblizar_plugin_add_settings_link');

/*** Load Facebook Like Box widgets ***/
require_once("function/facebook-by-weblizar-widgets.php");
require_once("function/facebook-feed-widget.php");

/*** Load Facebook Like Box Shortcode ***/
require_once("function/facebook-by-weblizar-short-code.php");

/*** Load Facebook Page Feed Shortcode ***/
require_once("function/facebook-feed-shortcode.php");
