<?php
/**
Plugin Name: Info Boxes Shortcode And Widgets
Plugin URI: http://OTWthemes.com
Description:  Create Inbo Boxes. Nice and easy interface. Insert anywhere in your site - page/post editor, sidebars, template files.
Author: OTWthemes
Version: 1.15
Author URI: https://codecanyon.net/user/otwthemes/portfolio?ref=OTWthemes
*/

load_plugin_textdomain('otw_ibsw',false,dirname(plugin_basename(__FILE__)) . '/languages/');

load_plugin_textdomain('otw-shortcode-widget',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$wp_ibsw_tmc_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_ibsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_ibsw' ) )
);

$wp_ibsw_agm_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_ibsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_ibsw' ) )
);

$wp_ibsw_cs_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_ibsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_ibsw' ) )
);

$otw_ibsw_plugin_url = plugin_dir_url( __FILE__);
$otw_ibsw_css_version = '1.8';
$otw_ibsw_js_version = '1.8';

$otw_ibsw_plugin_id = '95efbaf95b96af27aa17fdf364117233';

$otw_ibsw_plugin_options = get_option( 'otw_ibsw_plugin_options' );

//include functons
require_once( plugin_dir_path( __FILE__ ).'/include/otw_ibsw_functions.php' );

//otw components
$otw_ibsw_shortcode_component = false;
$otw_ibsw_form_component = false;
$otw_ibsw_validator_component = false;
$otw_ibsw_factory_component = false;
$otw_ibsw_factory_object = false;


//load core component functions
@include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_ibsw_plugin_url.'include/otw_components/otw_form/' );

//register validator component
otw_register_component( 'otw_validator', dirname( __FILE__ ).'/include/otw_components/otw_validator/', $otw_ibsw_plugin_url.'include/otw_components/otw_validator/' );

//register factory component
otw_register_component( 'otw_factory', dirname( __FILE__ ).'/include/otw_components/otw_factory/', $otw_ibsw_plugin_url.'/include/otw_components/otw_factory/' );

//register shortcode component
otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_ibsw_plugin_url.'include/otw_components/otw_shortcode/' );

/** 
 *call init plugin function
 */
add_action('init', 'otw_ibsw_init' );
add_action('widgets_init', 'otw_ibsw_widgets_init' );

?>