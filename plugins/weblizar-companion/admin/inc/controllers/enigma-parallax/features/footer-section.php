<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_customizer {
	
	public static function wl_enigma_parallax_footer_customizer( $wp_customize ) {
		
		/* Footer Options */
		$wp_customize->add_section(
			'footer_section',array(
				'title'=>__("Footer Options",WL_COMPANION_DOMAIN),
				'panel'=>'enigma_parallax_theme_option',
				'capability'=>'edit_theme_options',
			    'priority' => 50,
			)
		);
		
		$wp_customize->add_setting( 'enigma_widget_column',
		   array(
			  'type'              => 'theme_mod',
			  'default' => '',
			  'sanitize_callback' => 'absint',
			  'capability'        => 'edit_theme_options',
		   )
		);
		
		$wp_customize->add_control( 'enigma_widget_column',
		   array(
			  'label' => __( 'Footer Widget Column','enigma-parallax' ),
			  'section' => 'footer_section',
			  'priority' => 10,
			  'type' => 'select',
			  'capability' => 'edit_theme_options',
			  'choices' => array( 
				 '6' => __( '2-Column','enigma-parallax' ),
				 '4' => __( '3-Column','enigma-parallax' ),
				 '3' => __( '4-Column','enigma-parallax' ),
			  )
		   )
		);

		$wp_customize->add_setting(
			'footer_customizations',
			array(
				'default'			=>' © Copyright 2020. All Rights Reserved',
				'type'				=>'theme_mod',
				'sanitize_callback' =>'enigma_parallax_sanitize_text',
				'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'footer_customizations', array(
			'label'      => __( 'Footer Customization Text', WL_COMPANION_DOMAIN ),
			'type'		 =>'text',
			'section'    => 'footer_section',
			'settings'   => 'footer_customizations'
		) );

		$wp_customize->selective_refresh->add_partial( 'footer_customizations', array(
			'selector' => '.enigma_footer_copyright_info',
		) );

		$wp_customize->add_control( 'developed_by_text', array(
			'label'    => __( 'Developed By Text', WL_COMPANION_DOMAIN ),
			'type'     => 'text',
			'section'  => 'footer_section',
			'settings' => 'developed_by_text'
		) );
		$wp_customize->add_setting(
			'developed_by_weblizar_text',
			array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'enigma_parallax_sanitize_text',
				'capability'        => 'edit_theme_options'
			)
		);
		$wp_customize->add_control( 'developed_by_weblizar_text', array(
			'label'    => __( 'Developed By Link Text', WL_COMPANION_DOMAIN ),
			'type'     => 'text',
			'section'  => 'footer_section',
			'settings' => 'developed_by_weblizar_text'
		) );

		$wp_customize->add_setting(
			'developed_by_link',
			array(
				'default'           => '',
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw'
			)
		);
		$wp_customize->add_control( 'developed_by_link', array(
			'label'    => __( 'Developed By Link', WL_COMPANION_DOMAIN ),
			'type'     => 'url',
			'section'  => 'footer_section',
			'settings' => 'developed_by_link'
		) );
	}
}

?>