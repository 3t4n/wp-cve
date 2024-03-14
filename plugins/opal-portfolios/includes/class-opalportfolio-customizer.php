<?php
/**
 * Service Customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Select sanitization function
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function opalportfolio_theme_slug_sanitize_select( $input, $setting ){

		// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
		$input = sanitize_key( $input );

		// Get the list of possible select options.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                

}
    	
/**
 * Register individual settings through customizer's API.
 *
 * @param WP_Customize_Manager $wp_customize Customizer reference.
 */    	
if ( ! function_exists( 'opalportfolio_layout_customize_register' ) ) {
	
	function opalportfolio_layout_customize_register( $wp_customize ) {
		// Theme layout settings.
		$wp_customize->add_section( 'opalportfolio_options', array(
			'title'       => esc_html__( 'Portfolios Settings', 'opalportfolio' ),
			'capability'  => 'edit_theme_options',
			'description' => esc_html__( 'Set Service layout display in varials style and design', 'opalportfolio' ),
			'priority'    => 3,
		) );

		// archive Layout 
		$wp_customize->add_setting( 'opalportfolio_layout_archive_position', array(
			'default'           => 'classic',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_layout_archive_position', array(
					'label'       => esc_html__( 'Archive Layout', 'opalportfolio' ),
					'description' =>'',
					'section'     => 'opalportfolio_options',
					'settings'    => 'opalportfolio_layout_archive_position',
					'type'        => 'select',
					'sanitize_callback' => 'opalportfolio_theme_slug_sanitize_select',
					'choices'     => array(
						'classic' => esc_html__( 'Classic', 'opalportfolio' ),
						'boxed'   => esc_html__( 'Boxed', 'opalportfolio' ),
						'list'    => esc_html__( 'List', 'opalportfolio' ),
					),
					'priority'    => '20',
				)
		) );

		// archive Columns 
		$wp_customize->add_setting( 'opalportfolio_column_archive_position', array(
			'default'           => '3',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_column_archive_position', array(
					'label'       => esc_html__( 'Archive Column', 'opalportfolio' ),
					'description' => '',
					'section'     => 'opalportfolio_options',
					'settings'    => 'opalportfolio_column_archive_position',
					'type'        => 'select',
					'sanitize_callback' => 'opalportfolio_theme_slug_sanitize_select',
					'choices'     => array(
						'1' 	  => '1',
						'2' 	  => '2',
						'3' 	  => '3',
						'4' 	  => '4',
						'6' 	  => '6',
					),
					'priority'    => '20',
				)
		) );
		// archive Portfolios 
		$wp_customize->add_setting( 'opalportfolio_sidebar_archive_position', array(
			'default'           => 'right',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_sidebar_archive_position', array(
					'label'       => esc_html__( 'Archive Sidebar Position', 'opalportfolio' ),
					'description' => esc_html__( 'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.', 'opalportfolio' ),
					'section'     => 'opalportfolio_options',
					'settings'    => 'opalportfolio_sidebar_archive_position',
					'type'        => 'select',
					'sanitize_callback' => 'opalportfolio_theme_slug_sanitize_select',
					'choices'     => array(
						'right' => esc_html__( 'Right sidebar', 'opalportfolio' ),
						'left'  => esc_html__( 'Left sidebar', 'opalportfolio' ),
						'both'  => esc_html__( 'Left & Right sidebars', 'opalportfolio' ),
						'none'  => esc_html__( 'No sidebar', 'opalportfolio' ),
					),
					'priority'    => '20',
				)
		) );
		// archive display Category 
		$wp_customize->add_setting( 'opalportfolio_category_archive_position', array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_category_archive_position', array(
					'label'       => esc_html__( 'Archive - Show Category', 'opalportfolio' ),
					'description' => '',
					'section'     => 'opalportfolio_options',
					'type'        => 'checkbox',
					'priority'    => '20',
				)
		) );
		
		// archive display Description 
		$wp_customize->add_setting( 'opalportfolio_description_archive_position', array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_description_archive_position', array(
					'label'       => esc_html__( 'Archive - Show Description', 'opalportfolio' ),
					'description' => '',
					'section'     => 'opalportfolio_options',
					'type'        => 'checkbox',
					'priority'    => '20',
				)
		) );
		// archive display Readmore 
		$wp_customize->add_setting( 'opalportfolio_readmore_archive_position', array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_readmore_archive_position', array(
					'label'       => esc_html__( 'Archive - Show Readmore', 'opalportfolio' ),
					'description' => '',
					'section'     => 'opalportfolio_options',
					'type'        => 'checkbox',
					'priority'    => '20',
				)
		) );

		// single Layout 
		$wp_customize->add_setting( 'opalportfolio_layout_single_position', array(
			'default'           => 'layout_1',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_layout_single_position', array(
					'label'       => esc_html__( 'Single Layout', 'opalportfolio' ),
					'description' =>'',
					'section'     => 'opalportfolio_options',
					'settings'    => 'opalportfolio_layout_single_position',
					'type'        => 'select',
					'sanitize_callback' => 'opalportfolio_theme_slug_sanitize_select',
					'choices'     	=> array(
						'layout_1' 	=> esc_html__( 'Layout 1', 'opalportfolio' ),
						'layout_2'  => esc_html__( 'Layout 2', 'opalportfolio' ),
					),
					'priority'    => '20',
				)
		) );
		// single sidebar Portfolios 
		$wp_customize->add_setting( 'opalportfolio_sidebar_single_position', array(
			'default'           => 'right',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_sidebar_single_position', array(
					'label'       => esc_html__( 'Single Sidebar Position', 'opalportfolio' ),
					'description' => esc_html__( 'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
					'opalportfolio' ),
					'section'     => 'opalportfolio_options',
					'settings'    => 'opalportfolio_sidebar_single_position',
					'type'        => 'select',
					'sanitize_callback' => 'opalportfolio_theme_slug_sanitize_select',
					'choices'     => array(
						'right' => esc_html__( 'Right sidebar', 'opalportfolio' ),
						'left'  => esc_html__( 'Left sidebar', 'opalportfolio' ),
						'both'  => esc_html__( 'Left & Right sidebars', 'opalportfolio' ),
						'none'  => esc_html__( 'No sidebar', 'opalportfolio' ),
					),
					'priority'    => '20',
				)
		) );

		// single navigation Portfolios 
		$wp_customize->add_setting( 'opalportfolio_navigation_single_position', array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_navigation_single_position', array(
					'label'       => esc_html__( 'Single - Disable Navigation', 'opalportfolio' ),
					'description' => '',
					'section'     => 'opalportfolio_options',
					'type'        => 'checkbox',
					'priority'    => '20',
				)
		) );

		// single Share Portfolios 
		$wp_customize->add_setting( 'opalportfolio_share_single_position', array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'opalportfolio_share_single_position', array(
					'label'       => esc_html__( 'Single - Disable share social', 'opalportfolio' ),
					'description' => '',
					'section'     => 'opalportfolio_options',
					'type'        => 'checkbox',
					'priority'    => '20',
				)
		) );
		/// enable or disable preloader 
	}
} // endif function_exists( 'opalportfolio_theme_customize_register' ).
add_action( 'customize_register', 'opalportfolio_layout_customize_register' );

/**
 * Automatic set default values for postion and style, containner width after active the theme.
 */
add_action( 'after_setup_theme', 'opalportfolio_setup_theme_default_settings' );
if ( ! function_exists ( 'opalportfolio_setup_theme_default_settings' ) ) {
	function opalportfolio_setup_theme_default_settings() {

		// check if settings are set, if not set defaults.
		// Caution: DO NOT check existence using === always check with == .
		// Sidebar position.
		$opalportfolio_sidebar_archive_position = get_theme_mod( 'opalportfolio_sidebar_archive_position' );
		if ( '' == $opalportfolio_sidebar_archive_position ) {
			set_theme_mod( 'opalportfolio_sidebar_archive_position', 'none' );
		}

		// Container width.
		$opalportfolio_sidebar_single_position = get_theme_mod( 'opalportfolio_sidebar_single_position' );
		if ( '' == $opalportfolio_sidebar_single_position ) {
			set_theme_mod( 'opalportfolio_sidebar_single_position', 'none' );
		}
	}
}
?>