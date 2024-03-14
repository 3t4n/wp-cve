<?php
/**
Plugin Name: Quotes Shortcode And Widgets
Plugin URI: http://OTWthemes.com
Description:  Create Quotes. Nice and easy interface. Insert anywhere in your site - page/post editor, sidebars, template files.
Author: OTWthemes
Version: 1.14
Author URI: https://codecanyon.net/user/otwthemes/portfolio?ref=OTWthemes
*/

load_plugin_textdomain('otw_qtsw',false,dirname(plugin_basename(__FILE__)) . '/languages/');

load_plugin_textdomain('otw-shortcode-widget',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$wp_qtsw_tmc_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_qtsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_qtsw' ) )
);

$wp_qtsw_agm_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_qtsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_qtsw' ) )
);

$wp_qtsw_cs_items = array(
	'page'              => array( array(), esc_html__( 'Pages', 'otw_qtsw' ) ),
	'post'              => array( array(), esc_html__( 'Posts', 'otw_qtsw' ) )
);

$otw_qtsw_plugin_id = '723e662ef8b0759492eed2f756291336';

$otw_qtsw_plugin_url = plugin_dir_url( __FILE__);
$otw_qtsw_css_version = '1.8';
$otw_qtsw_js_version = '1.8';

$otw_qtsw_plugin_options = get_option( 'otw_qtsw_plugin_options' );

//include functons
require_once( plugin_dir_path( __FILE__ ).'/include/otw_qtsw_functions.php' );

//otw components
$otw_qtsw_shortcode_component = false;
$otw_qtsw_form_component = false;
$otw_qtsw_validator_component = false;
$otw_qtsw_factory_component = false;
$otw_qtsw_factory_object = false;


//load core component functions
@include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_qtsw_plugin_url.'include/otw_components/otw_form/' );

//register validator component
otw_register_component( 'otw_validator', dirname( __FILE__ ).'/include/otw_components/otw_validator/', $otw_qtsw_plugin_url.'include/otw_components/otw_validator/' );

//register shortcode component
otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_qtsw_plugin_url.'include/otw_components/otw_shortcode/' );

//register factory component
otw_register_component( 'otw_factory', dirname( __FILE__ ).'/include/otw_components/otw_factory/', $otw_qtsw_plugin_url.'/include/otw_components/otw_factory/' );


/** 
 *call init plugin function
 */
add_action('init', 'otw_qtsw_init' );
add_action('widgets_init', 'otw_qtsw_widgets_init' );

?>