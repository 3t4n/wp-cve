<?php
/*
Plugin Name: Tp Scroll Top
Plugin URI: https://www.themepoints.com
Description: Tp Scroll To Top is fully responsive plugin for WordPress.
Version: 1.3
Author: Themepoints
Author URI: https://www.themepoints.com
License: GPLv2
Text Domain: scrolltop
Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) exit;

define('TP_SCROLL_TOP_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

# Tp scroll top enqueue scripts
function tp_scroll_top_active_script(){
	wp_enqueue_style('scroll-top-css', TP_SCROLL_TOP_PLUGIN_PATH.'css/tp-scroll-top.css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('scroll-top-js', plugins_url( '/js/ap-scroll-top.js', __FILE__ ), array('jquery'), '1.0', false);
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('scrolltop-wp-color-picker', plugins_url(), array( 'wp-color-picker' ), false, true );
}
add_action('init', 'tp_scroll_top_active_script');

# TP Load Textdomain
function tp_scroll_top_load_textdomain() {
	load_plugin_textdomain( 'scrolltop', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
}
add_action( 'plugins_loaded', 'tp_scroll_top_load_textdomain' );

# Tp scroll top admin init
function tp_scroll_to_top_option_init(){
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_option_enable');
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scroll_fade_speed');	
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_visibility_fade_speed');	
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_visibility_trigger');	
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scroll_position');	
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scrollbg');	
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scrollbg_hover');	
	register_setting( 'tp_scroll_to_top_plugin_options', 'tp_scroll_top_scrollradious');	
}
add_action('admin_init', 'tp_scroll_to_top_option_init' );

# Tp scroll top main enqueue scripts
function tp_scroll_top_custom_css_style(){
	$tp_scroll_top_scrollbg = get_option( 'tp_scroll_top_scrollbg' );
	if( empty( $tp_scroll_top_scrollbg ) ){
		$tp_scroll_top_scrollbg = '#ffc107';
	}
	$tp_scroll_top_scrollbg_hover = get_option( 'tp_scroll_top_scrollbg_hover' );
	if( empty( $tp_scroll_top_scrollbg_hover ) ){
		$tp_scroll_top_scrollbg_hover = '#212121';
	}
	$tp_scroll_top_scrollradious = get_option( 'tp_scroll_top_scrollradious' );
	if( empty( $tp_scroll_top_scrollradious ) ){
		$tp_scroll_top_scrollradious = '50';
	}
	?>
	<style type="text/css">
		.apst-button {
			background-color: <?php echo $tp_scroll_top_scrollbg; ?>;
			border-radius: <?php echo $tp_scroll_top_scrollradious; ?>%;
			display: block;
			height: 80px;
			width: 80px;
			position: relative;
			transition: all 0.2s ease 0s;
		}
		.apst-button:hover {
			background-color: <?php echo $tp_scroll_top_scrollbg_hover; ?>;
	    }
	</style>
	<?php
}
add_action('wp_head', 'tp_scroll_top_custom_css_style');

# tp scroll top script setting
function tp_scroll_top_display_script(){
	$tp_scroll_top_option_enable         = get_option( 'tp_scroll_top_option_enable', 'true' );
	$tp_scroll_top_scroll_fade_speed     = get_option( 'tp_scroll_top_scroll_fade_speed', '100' );
	$tp_scroll_top_visibility_fade_speed = get_option( 'tp_scroll_top_visibility_fade_speed', '100' );
	$tp_scroll_top_scroll_position       = get_option( 'tp_scroll_top_scroll_position', 'bottom right' );
	?>
	<script type="text/javascript">
		// Setup plugin with default settings
		jQuery(document).ready(function($) {
			jQuery.apScrollTop({
				enabled: <?php echo $tp_scroll_top_option_enable ;?>,
				visibilityTrigger: 100,
				visibilityFadeSpeed: <?php echo $tp_scroll_top_visibility_fade_speed ;?>,
				scrollSpeed: <?php echo $tp_scroll_top_scroll_fade_speed ;?>,
				position: '<?php echo $tp_scroll_top_scroll_position ;?>',
			});
		});
	</script>
    <?php
}
add_action('wp_head', 'tp_scroll_top_display_script');

# tp scroll top option page setting
function tp_scroll_top_option_settings(){
	include('admin/tp-scroll-top-admin.php');
}

function tp_scroll_top_menu_init() {
	add_menu_page( __( 'Tp Scroll Top', 'scrolltop' ), __( 'Tp Scroll Top', 'scrolltop' ), 'manage_options', 'tp_scroll_top_option_settings', 'tp_scroll_top_option_settings');
}
add_action('admin_menu', 'tp_scroll_top_menu_init');

?>