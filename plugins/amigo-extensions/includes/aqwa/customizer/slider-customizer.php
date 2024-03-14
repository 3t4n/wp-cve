<?php
/**
 * Customizer header bar settings for this theme.
 * 
 * 
 */

if ( ! class_exists( 'Aqwa_Slider_Customizer' ) ) {

	class Aqwa_Slider_Customizer{

		public function __construct() {

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );
			add_action( 'customize_register', array( $this, 'slider_section_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'partials' ) );
		}

		public function customizer_panels( $wp_customize ) {	

			if ( ! $wp_customize->get_panel( 'frontpage_panel' ) ) {
				$wp_customize->add_panel('frontpage_panel',array(

					'priority'      => 24,
					'capability'    => 'edit_theme_options',
					'title'			=> __('Homepage Sections', 'amigo-extensions'),
				));
			}
		}

		public function customizer_sections( $wp_customize ) {	

			// add section
			$wp_customize->add_section('slider_section', array(
				'title'    => esc_html__( 'Slider', 'amigo-extensions' ),
				'panel'	=> 'frontpage_panel',
				'priority' => 1,		

			));		
		}

		public function slider_section_customizer_settings( $wp_customize ) {

			$default_slider = aqwa_slider_section_default();

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			// home slider
			$wp_customize->add_setting( 'aqwa_slider_items', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => $default_slider
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_slider_items', array(
				'label'   => esc_html__('Slide Item','amigo-extensions'),
				'section' => 'slider_section',
				'priority' => 1,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_title_control' => true,				
				'customizer_repeater_text_control' => true,
				'customizer_repeater_link2_control' => true,
				'customizer_repeater_text2_control'=> true,				
				'customizer_repeater_checkbox_control' => true,								
				
			) ) );

		}

		public function partials( $wp_customize ) {

			$wp_customize->selective_refresh->add_partial( 'aqwa_slider_items', array(
				'selector'            => '.main-slider',
			) );
		}

		public function sanitize_text( $text ) {
			return wp_filter_nohtml_kses( $text );
		}	

		public function sanitize_checkbox( $checked = null ) {
			return (bool) isset( $checked ) && true === $checked;
		}	
	}

	new Aqwa_Slider_Customizer();
}