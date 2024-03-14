<?php
 /**
  * Plugin Name: Cryptocurrencies and Bitcoin Prices
  * Text Domain: coinmotion
  * Plugin URI: https://wordpress.org/plugins/precios-bitcoin-criptomonedas/
  * Description: Free plugin with several widgets with multiple features about cryptocurrencies. Customize language, colors, currencies, data, among 10 other options.
  * Version: 2.2
  * Author: Coinmotion
  * Requires at least: 5.2.3
  * Tested up to: 6.0.2
  * Text Domain: coinmotion
  * Domain Path: languages
  * License: GPLv2 or later
  */

defined( 'ABSPATH' ) or die ( 'Bye' );

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'COINMOTION_VERSION', '1.0.7' );
define( 'COINMOTION__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'COINMOTION_OPTION_NAME_WIDGET_0', 'widget_coinmotion_widget_0' );
define( 'COINMOTION_OPTION_NAME_WIDGET_RATE_PERIOD', 'widget_coinmotion_widget_rate_period' );
define( 'COINMOTION_OPTION_NAME_WIDGET_CURRENCY_DETAILS', 'widget_coinmotion_widget_currency_details' );
define( 'COINMOTION_OPTION_NAME_WIDGET_CURRENCY_CONVERSOR', 'widget_coinmotion_widget_currency_conversor' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/settings.php');
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_comm.php');
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_values.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_widget_0.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_widget_rate_period.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_widget_currency_details.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_widget_currency_conversor.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_affiliate_button.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/shortcode.currency_carousel.php' );
require_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_get_currencies.php' );
require_once( COINMOTION__PLUGIN_DIR . '/admin/coinmotion.php' );

$p = __('price', 'coinmotion');
$i = __('interest', 'coinmotion');
$h = __('hour', 'coinmotion');
$t1 = __( 'Real time prices', 'coinmotion' );
$t2 = __( 'WIDGET_REFERRAL', 'coinmotion' );
$t3 = __( 'Buy now', 'coinmotion' );
$t4 = __( 'Cryptocurrencies and Bitcoin Prices', 'coinmotion');
$t5 = __( 'Plugin with several widgets with multiple features about cryptocurrencies. Customize language, colors, currencies, data, among 10 other options.', 'coinmotion');

function set_anchor(){
    if (!get_option('coinmotion_anchor', false)){
        $anchors = ['Powered by <a href="https://coinmotion.com/_LANG_/_TRACK_CODE_" rel="nofollow" target="_blank">Coinmotion</a>',
                    'Powered by <a href="https://coinmotion.com/_LANG_/_TRACK_CODE_" rel="nofollow" target="_blank">Coinmotion.com</a>',
                    'Powered by <a href="https://coinmotion.com/_LANG_/_TRACK_CODE_" rel="nofollow" target="_blank">www.coinmotion.com</a>',
                    'Powered by <a href="https://coinmotion.com/_LANG_/_TRACK_CODE_" rel="nofollow" target="_blank">https://coinmotion.com</a>'];
        $rand = rand(0, count($anchors) - 1);
        update_option('coinmotion_anchor', $anchors[$rand]);
    }

}
function coinmotion_activation() { 
	file_put_contents(COINMOTION__PLUGIN_DIR . '/logs/coinmotion_loging.txt', ob_get_contents() );
} 

function coinmotion_get_currencies() { 
  $curr = new CoinmotionGetCurrencies();
  $curr->getCurrencies();
} 

function coinmotion_admin_styles() {
 	wp_enqueue_style( 'coin_motion_admin_theme',  plugins_url( '/admin/css/global.css ', __FILE__ ) );
}

function coinmotion_public_styles() {
 	wp_enqueue_style( 'coinmotion_public_theme',  plugins_url( '/admin/css/global.css ', __FILE__ ) );
}

// TODO: Icon pending
function coinmotion_admin_configuration() {
    add_menu_page(__('Coinmotion Settings', 'coinmotion'), __('Coinmotion', 'coinmotion'), 'manage_options', 'coinmotion_plugin_config_page', 'coinmotion_admin_settings_page', 'dashicons-welcome-view-site', 24);
}

function coinmotion_register_widget_rate_period() {
    return register_widget( 'Coinmotion_Widget_Rate_Period' );
}

function coinmotion_register_widget_currency_details() {
    return register_widget( 'Coinmotion_Widget_Currency_Details' );
}

function coinmotion_register_widget_currency_conversor() {
    return register_widget( 'Coinmotion_Widget_Currency_Conversor' );
}

function coinmotion_plugin_config_page(){
	include( 'admin/coinmotion.php' );
}

function coinmotion_shortcode_definition( $atts, $content = null ) {
	$params = coinmotion_get_widget_data();
 	shortcode_atts( $params, $atts );
 	  
 	return "<div class='coinmotion-widget-container'></div>";
}

function coinmotion_public_scripts() {
	$params = coinmotion_get_widget_data();
	wp_enqueue_script( 'coinmotion_public_js', plugins_url('/public/js/coinmotion.js', __FILE__), false, '1.0', true );
 	  
 	wp_localize_script( 'coinmotion_public_js','vars_inline', $params );
}
function conmotion_shortcodes_init(){
    add_shortcode('coinmotion', 'coinmotion_currency_carousel_shortcode');
    add_shortcode('coinmotion_conversor', 'coinmotion_widget_currency_conversor_shortcode');
    add_shortcode('coinmotion_details', 'coinmotion_widget_currency_details_shortcode');
    add_shortcode('coinmotion_rate_period', 'coinmotion_widget_rate_period_shortcode');
}

register_activation_hook( __FILE__, 'coinmotion_activation' );

add_action( 'plugins_loaded', 'coinmotion_load_textdomain' );

add_action( 'plugins_loaded', 'coinmotion_get_currencies' );

add_action( 'plugins_loaded', 'set_anchor' );

add_action( 'admin_enqueue_scripts', 'coinmotion_admin_styles' );

add_action( 'login_enqueue_scripts', 'coinmotion_public_styles' );

add_action('admin_menu', 'coinmotion_admin_configuration');

add_action( 'widgets_init', 'coinmotion_register_widget_rate_period' );

add_action( 'widgets_init', 'coinmotion_register_widget_currency_details' );

add_action( 'widgets_init', 'coinmotion_register_widget_currency_conversor' );

add_shortcode( 'coinmotion', 'coinmotion_shortcode_definition' );

add_action( 'wp_enqueue_scripts', 'coinmotion_public_scripts' );

add_action('widgets_init', 'conmotion_shortcodes_init');

function coinmotion_load_textdomain() {
    load_plugin_textdomain( 'coinmotion', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function coinmotion_action_links( $links ) {
  $links = array_merge( array(
    '<a rel="nofollow" href="' . esc_url( admin_url( '/admin.php?page=coinmotion_plugin_config_page' ) ) . '">' . __( 'Settings', 'coinmotion' ) . '</a>'
  ), $links );

  return $links;

}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'coinmotion_action_links' );
