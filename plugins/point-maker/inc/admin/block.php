<?php

defined( 'ABSPATH' ) || exit;

function point_maker_block_control_panel() {

	wp_register_script('point_maker_block_script', POINT_MAKER_URI . 'js/admin/point_maker_block.min.js',array('wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor'));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		
		wp_set_script_translations( 'point_maker_block_script', 'point-maker', POINT_MAKER_DIR . 'languages' );

	} else if (function_exists('gutenberg_get_jed_locale_data')) {
		

		
		$locale  = gutenberg_get_jed_locale_data( 'point-maker' );

		
		$content = 'wp.i18n.setLocaleData(' . json_encode( $locale ) . ', "point-maker" );';

		
		wp_script_add_data( 'point_maker_block_script', 'data', $content );
	}

	if(class_exists('WP_Block_Type_Registry') ){

		$block_registry = WP_Block_Type_Registry::get_instance();

		if( !array_key_exists('point-maker/point-maker-block', $block_registry->get_all_registered()) ){
			register_block_type( 'point-maker/point-maker-block', array(
				'editor_script' => 'point_maker_block_script'	) );
		}

	}





	require_once POINT_MAKER_DIR . 'inc/settings/default_type.php';
	require_once POINT_MAKER_DIR . 'inc/colors.php';
	require_once POINT_MAKER_DIR . 'inc/icons.php';




	$point_maker_block_colors = array();
	$default_colors = point_maker_base_colors_list();
	foreach ($default_colors as $key => $value) {
		$point_maker_block_colors[$key] = array(
			'slug' => $key,
			'color' => $value['base'],
			'lighter' => $value['lighter'],
			'dark' => $value['dark'],
			'darker' => $value['darker'],
		);
	}


	$default_type = point_maker_default_type_settings();
	$point_maker_block_type_colors = array();
	foreach ($default_type['type_colors'] as $key => $value) {
		$point_maker_block_type_colors[] = array(
			'value' => $key,
			'label' => $value,
		);
	}

	$point_maker_block_types = array();
	foreach($default_type['type'] as $key => $value){
		$point_maker_block_types[] = array(
			'value' => $key,
			'label' => $value,
		);
	}

	$point_maker_block_type_content = array();
	foreach($default_type['type_content'] as $key => $value){
		$point_maker_block_type_content[] = array(
			'value' => $key,
			'label' => $value,
		);
	}

	$point_maker_block_icons_path = array();
	$point_maker_block_type_icons = array();
	foreach($default_type['type_icons'] as $key => $value){
		$temp = point_maker_svg_icon_list($key);
		$point_maker_block_icons_path[$key] = $temp;
		$point_maker_block_type_icons[] = array(
			'value' => $key,
			'label' => $value,
		);
	}

	wp_localize_script( 'point_maker_block_script', 'point_maker_block_types', $point_maker_block_types );
	wp_localize_script( 'point_maker_block_script', 'point_maker_block_type_colors', $point_maker_block_type_colors );
	wp_localize_script( 'point_maker_block_script', 'point_maker_block_colors', $point_maker_block_colors );
	wp_localize_script( 'point_maker_block_script', 'point_maker_block_type_content', $point_maker_block_type_content );
	wp_localize_script( 'point_maker_block_script', 'point_maker_block_icons_path', $point_maker_block_icons_path );
	wp_localize_script( 'point_maker_block_script', 'point_maker_block_type_icons', $point_maker_block_type_icons );
}

