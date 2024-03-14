<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

add_action( 'carbon_fields_register_fields', 'preloader_awesome_theme_options_opt' );
function preloader_awesome_theme_options_opt() {

	// loader background
	$basic_options_container = Container::make( 'theme_options', 'preloader_awesome_opts', esc_html__( 'Preloader', 'preloader-awesome' ) )
	->add_fields( array(

		Field::make( 'checkbox', 'preloader_awesome_sitewide_global', esc_html__( 'Activate Preloader Sitewide', 'preloader-awesome' ) )
		->set_option_value( 'yes' )
		->set_default_value( 'yes' )
		->set_width( 10 ),

		// ======================= loader main ===================== //
		Field::make( 'select', 'preloader_awesome_style_global', esc_html__( 'Transition Style', 'preloader-awesome' ) )
		->set_options( array(
			'' => esc_html__('Select', 'preloader-awesome'),
			'lazy-stretch' => esc_html__('Style Hiji', 'preloader-awesome'),
			'spill' => esc_html__('Style Tilu', 'preloader-awesome'),
			'parallelogam' => esc_html__('Style Dalapan', 'preloader-awesome'),
		) )
		->set_width( 20 ),

		Field::make( 'number', 'preloader_awesome_anim_time_global', esc_html__( 'Animation Time (ms)', 'preloader-awesome' ) )
		->set_min( 1 )
		->set_width( 20 ),

		Field::make( 'color', 'preloader_awesome_bg_color_global', esc_html__( 'Background Color', 'preloader-awesome' ) )
		->set_width( 20 ),

		Field::make( 'checkbox', 'preloader_awesome_counter_global', esc_html__( 'Use Counter', 'preloader-awesome' ) )
		->set_option_value( 'yes' )
		->set_width( 10 ),

		Field::make( 'checkbox', 'preloader_awesome_progress_global', esc_html__( 'Use Progress Bar', 'preloader-awesome' ) )
		->set_option_value( 'yes' )
		->set_width( 10 ),

		// ======================= loader image ===================== //

		Field::make( 'select', 'preloader_awesome_loader_type_global', esc_html__( 'Preloader Type', 'preloader-awesome' ) )
		->set_options( array(
			'css' => esc_html__('CSS Loader', 'preloader-awesome'),
			'img' => esc_html__('Image / GIF', 'preloader-awesome'),
		) )
		->set_width( 20 ),

		Field::make( 'radio_image', 'preloader_awesome_loader_css_type_global', esc_html__( 'Choose Background Image', 'preloader-awesome' ) )
		->set_options( array(
			'loader2' => plugin_dir_url('README.txt') . PRELOADER_AWESOME_NAME . '/assets/loader2.gif',
			'loader3' => plugin_dir_url('README.txt') . PRELOADER_AWESOME_NAME . '/assets/loader3.gif',
			'loader5' => plugin_dir_url('README.txt') . PRELOADER_AWESOME_NAME . '/assets/loader5.gif',
			'loader6' => plugin_dir_url('README.txt') . PRELOADER_AWESOME_NAME . '/assets/loader6.gif',

		) )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_loader_type_global',
				'value' => 'css',
			),
		) )
		->set_width( 60 ),

		Field::make( 'color', 'preloader_awesome_css_loader_color_global', esc_html__( 'Loader Color', 'preloader-awesome' ) )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_loader_type_global',
				'value' => 'css',
			),
		) )
		->set_width( 20 ),

		Field::make( 'image', 'preloader_awesome_loader_img_global', esc_html__( 'Preloader Image (GIF)', 'preloader-awesome' ) )
		->set_value_type( 'url' )
		->set_width( 20 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_loader_type_global',
				'value' => 'img',
			),
		) ),

		Field::make( 'number', 'preloader_awesome_loader_size_global', esc_html__( 'Loader Image Size (px)', 'preloader-awesome' ) )
		->set_min( 1 )
		->set_width( 20 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_loader_type_global',
				'value' => 'img',
			),
		) ),

		// ======================= loader counter ===================== //

		// counter size
		Field::make( 'number', 'preloader_awesome_counter_size_global', esc_html__( 'Counter Font Size (px)', 'preloader-awesome' ) )
		->set_min( 1 )
		->set_max( 200 )
		->set_width( 50 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_counter_global',
				'value' => true,
			),
		) ),

		// counter color
		Field::make( 'color', 'preloader_awesome_counter_color_global', esc_html__( 'Counter Color', 'preloader-awesome' ) )
		->set_width( 50 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_counter_global',
				'value' => true,
			),
		) ),

		// ======================= loader progress ===================== //

		Field::make( 'select', 'preloader_awesome_prog_pos_global', esc_html__( 'Bar Position', 'preloader-awesome' ) )
		->set_options( array(
			'' => esc_html__('Select', 'preloader-awesome'),
			'top' => esc_html__('Top', 'preloader-awesome'),
			'center' => esc_html__('Center', 'preloader-awesome'),
			'bottom' => esc_html__('Bottom', 'preloader-awesome'),
		) )
		->set_width( 33 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_progress_global',
				'value' => true,
			),
		) ),

		Field::make( 'color', 'preloader_awesome_prog_color_global', esc_html__( 'Bar Color', 'preloader-awesome' ) )
		->set_width( 33 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_progress_global',
				'value' => true,
			),
		) ),

		Field::make( 'number', 'preloader_awesome_prog_height_global', esc_html__( 'Bar Height (px)', 'preloader-awesome' ) )
		->set_min( 1 )
		->set_max( 10 )
		->set_width( 33 )
		->set_conditional_logic( array(
			array(
				'field' => 'preloader_awesome_progress_global',
				'value' => true,
			),
		) ),

	) );
	

	Container::make( 'theme_options', esc_html__( 'Documentation', 'preloader-awesome' ) )
	->set_page_parent( $basic_options_container ) // identificator of the "Appearance" admin section
	->add_fields( array(
	) );
}