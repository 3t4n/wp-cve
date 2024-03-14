<?php
defined( 'ABSPATH' ) || exit;


function point_maker_do_shortcode( $atts , $content = null ){

	$atts = shortcode_atts(
		array(
			'type' => 'simple_icon',
			'base_color' => 'sky_blue',
			'content_type' => 'text',
			'title' => '',
			'title_icon' => 'info-circle-solid',
			'list_icon' => 'caret-right-solid',
			'title_color_background' => 'true',
			'title_color_border' => 'true',
			'content_color_background' => 'false',
			'content_color_border' => 'true',
			'block_editor' => 'false',
		), $atts, 'point_maker' );

	if ( !file_exists( POINT_MAKER_DIR . 'type/' . $atts['type'] . '.php' ) ) return;

	
	require_once POINT_MAKER_DIR . 'inc/functions.php';
	
	require_once POINT_MAKER_DIR . 'inc/icons.php';
	
	require_once POINT_MAKER_DIR . 'inc/colors.php';
	
	require_once POINT_MAKER_DIR . 'type/' . $atts['type'] . '.php';


	wp_enqueue_style('point_maker_base', POINT_MAKER_URI . 'css/base.min.css',array(),POINT_MAKER_VERSION);
	wp_enqueue_style('point_maker_type_' . $atts['type'], POINT_MAKER_URI . 'css/' . $atts['type'] . '.min.css',array('point_maker_base'),POINT_MAKER_VERSION);

	
	$content = do_shortcode( shortcode_unautop( $content ) );

	$type = 'point_maker_type_' . $atts['type'];

	if( !function_exists( $type ) ) return;



	return $type($atts , $content);



}

add_shortcode('point_maker', 'point_maker_do_shortcode');
