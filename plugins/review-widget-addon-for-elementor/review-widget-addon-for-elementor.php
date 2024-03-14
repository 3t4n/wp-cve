<?php
/*
Plugin Name: Review widget addon for Elementor
Plugin URI: https://wordpress.org/plugins/review-widget-addon-for-elementor/
Description: Use this Elementor addon to show your reviews (from Google, Facebook, Tripadvisor) in your site.
Tags: elementor, recommendations, reviews, elementor addon, widget
Author: Trustindex.io <support@trustindex.io>
Author URI: https://www.trustindex.io/
Contributors: trustindex
Version: 2.2
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: review-widget-addon-for-elementor
Domain Path: /languages
Donate link: https://www.trustindex.io/prices/
*/
/*
Copyright 2019 Trustindex Kft (email: support@trustindex.io)
*/
if( ! defined( 'ABSPATH' ) ) exit();
if ( ! function_exists('is_plugin_active')) { include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
class Trustindex_elementor {
public static $plugins = array(
0 => 'free-facebook-reviews-and-recommendations-widgets',
1 => 'wp-reviews-plugin-for-google',
2 => 'review-widgets-for-tripadvisor',
3 => 'reviews-widgets-for-yelp',
4 => 'review-widgets-for-booking-com',
5 => 'reviews-widgets',
6 => 'review-widgets-for-amazon',
7 => 'review-widgets-for-arukereso',
8 => 'review-widgets-for-airbnb',
9 => 'review-widgets-for-hotels-com',
10 => 'review-widgets-for-opentable',
11 => 'review-widgets-for-foursquare',
12 => 'review-widgets-for-capterra',
13 => 'review-widgets-for-szallas-hu',
14 => 'widgets-for-thumbtack-reviews',
15 => 'widgets-for-expedia-reviews',
16 => 'widgets-for-zillow-reviews',
17 => 'widgets-for-alibaba-reviews',
18 => 'widgets-for-aliexpress-reviews',
19 => 'widgets-for-sourceforge-reviews',
20 => 'widgets-for-ebay-reviews',
);
}
function check_ti_active()
{
$active_plugins = get_option('active_plugins');
$is_ti_active = false;
foreach ($active_plugins as $active_plugin)
{
$name = explode('/', $active_plugin)[0];
if (in_array($name, Trustindex_elementor::$plugins))
{
$is_ti_active = true;
break;
}
}
return $is_ti_active;
}
function ti_register_elementor_widgets(){
include_once(plugin_dir_path( __FILE__ ).'include/elementor_widgets.php');
}
if(has_action('elementor/widgets/register'))
{
add_action('elementor/widgets/register','ti_register_elementor_widgets');
}
else
{
add_action('elementor/widgets/widgets_registered','ti_register_elementor_widgets');
}
add_action('elementor/editor/before_enqueue_scripts', function() {
wp_enqueue_script('trustindex-js', 'https://cdn.trustindex.io/loader.js', [], false, true);
});
function ti_is_plugins_active( $pl_file_path = null )
{
$installed_plugins_list = get_plugins();
return isset( $installed_plugins_list[$pl_file_path] );
}
function ti_load_plugin() {
if ( ! did_action( 'elementor/loaded' ) ) {
add_action( 'admin_notices', 'ti_check_elementor_status' );
return;
}
if( !check_ti_active() ){
add_action( 'admin_notices', 'ti_check_contactform_status' );
return;
}
}
add_action( 'plugins_loaded', 'ti_load_plugin' );
function ti_check_elementor_status(){
$elementor = 'elementor/elementor.php';
if( ti_is_plugins_active( $elementor ) ) {
if( ! current_user_can( 'activate_plugins' ) ) {
return;
}
$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );
$message = '<p>Trustindex Addons not working because you need to activate the Elementor plugin.</p>';
$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, 'Activate Elementor Now' ) . '</p>';
} else {
if ( ! current_user_can( 'install_plugins' ) ) {
return;
}
$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
$message = '<p>' . __( 'Trustindex Addons not working because you need to install the Elementor plugin', 'ht-contactform' ) . '</p>';
$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, 'Install Elementor Now' ) . '</p>';
}
echo '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
}
function ti_check_contactform_status(){
$active = false;
$installed_plugin = null;
foreach (Trustindex_elementor::$plugins as $plugin)
{
if (ti_is_plugins_active( "{$plugin}/{$plugin}.php" ))
{
$installed_plugin = "{$plugin}/{$plugin}.php";
$active = true;
break;
}
}
if( $active ) {
if( ! current_user_can( 'activate_plugins' ) ) {
return;
}
$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $installed_plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $installed_plugin );
$message = '<p>For the full functionality of "Trustindex Review Widget Elementor Addon", activate one of the core Trustindex Review Plugins.</p>';
$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, 'Activate Now' ) . '</p>';
} else {
if ( ! current_user_can( 'install_plugins' ) ) {
return;
}
$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wp-reviews-plugin-for-google'), 'install-plugin_wp-reviews-plugin-for-google');
$message = '<p> For the full functionality of "Trustindex Review Widget Elementor Addon", download one of the core Trustindex Review Plugins.</p>';
$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, 'Install Now' ) . '</p>';
}
echo '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
}
?>