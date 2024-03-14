<?php
/**
 * Customizer info section.
 *
 * @package Aqwa WordPress Theme
 * 
 * 
 */

if ( ! class_exists( 'Aqwa_Info_Section_Customize' ) ) {

	class Aqwa_Info_Section_Customize{

		private $default = '';

		public function __construct() {

			$this->default = aqwa_info_section_default();

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );
			add_action( 'customize_register', array( $this, 'info_section_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'customizer_partials' ) );
		}

		public function customizer_panels( $wp_customize ) {	

			if ( ! $wp_customize->get_panel( 'frontpage_panel' ) ) {
				$wp_customize->add_panel('frontpage_panel',array(

					'priority'      => 10,
					'capability'    => 'edit_theme_options',
					'title'			=> __('Homepage Sections', 'aqwa-pro'),
				));
			}
		}

		public function customizer_sections( $wp_customize ) {	

			// add section
			$wp_customize->add_section('info_section', array(
				'title'    => esc_html__( 'Info', 'aqwa-pro' ),
				'panel'	=> 'frontpage_panel',
				'priority' => 1,		

			));		
		}

		public function info_section_customizer_settings( $wp_customize ) {			

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			// is display
			$wp_customize->add_setting(
				'aqwa_display_info_section',
				array(
					'default' => $this->default['aqwa_display_info_section'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_info_section',
				array(
					'label'   		=> __('Show/Hide About Section','aqwa-pro'),
					'section'		=> 'info_section',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// info repeter
			$wp_customize->add_setting( 'aqwa_info_items', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => aqwa_default_info_items(),
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_info_items', array(
				'label'   => esc_html__('Info Items','aqwa-pro'),
				'item_name' => esc_html__( 'Item', 'aqwa-pro' ),
				'section' => 'info_section',
				'priority' => 10,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_title_control' => true,				
				'customizer_repeater_text_control' => true,
				'customizer_repeater_icon_control' => true,	
				'customizer_repeater_link2_control' => true,
				'customizer_repeater_text2_control'=> true,				
				'customizer_repeater_checkbox_control' => true,								
				
			) ) );

		}

		public function customizer_partials( $wp_customize ) {

			$wp_customize->selective_refresh->add_partial( 'aqwa_info_items', array(
				'selector'            => '.few-service .container > .row',
			) );
		}
		
	}

	new Aqwa_Info_Section_Customize();
}