<?php
/**
 * Customizer service section settings
 *
 * 
 */

if ( ! class_exists( 'Aqwa_Service_Section_Customizer' ) ) {

	class Aqwa_Service_Section_Customizer{

		public function __construct() {

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );	
			add_action( 'customize_register', array( $this, 'service_section_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'add_partials' ) );
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
			$wp_customize->add_section('service_section', array(
				'title'    => esc_html__( 'Services', 'amigo-extensions' ),
				'panel'	=> 'frontpage_panel',
				'priority' => 1,		

			));	
		}

		public function service_section_customizer_settings( $wp_customize ) {

			// default settings
			$default = aqwa_service_section_default();

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
			

			// service is display
			$wp_customize->add_setting(
				'aqwa_display_service_section',
				array(
					'default' => $default['aqwa_display_service_section'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_service_section',
				array(
					'label'   		=> __('Show/Hide Service Section','amigo-extensions'),
					'section'		=> 'service_section',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// service section title
			$wp_customize->add_setting(
				'aqwa_service_section_title',
				array(
					'default' => esc_html( $default['aqwa_service_section_title'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_service_section_title',
				array(
					'label'   		=> __('Title','amigo-extensions'),
					'section'		=> 'service_section',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);		

			// service section sub title
			$wp_customize->add_setting(
				'aqwa_service_section_subtitle',
				array(
					'default' => esc_html( $default['aqwa_service_section_subtitle'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_service_section_subtitle',
				array(
					'label'   		=> __('Sub Title','amigo-extensions'),
					'section'		=> 'service_section',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);	

			// service section text
			$wp_customize->add_setting(
				'aqwa_service_section_text',
				array(
					'default' => esc_html( $default['aqwa_service_section_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_service_section_text',
				array(
					'label'   		=> __('Text','amigo-extensions'),
					'section'		=> 'service_section',
					'type' 			=> 'textarea',
					'transport'         => $selective_refresh,
				)  
			);	

			// column settings
			$wp_customize->add_setting('aqwa_service_section_column',
				array(
					'default' => $default['aqwa_service_section_column'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_radio',
					'priority'      => 1,
				)
			);
			$wp_customize->add_control( 'aqwa_service_section_column',
				array( 
					'type' => 'radio',
					'section' => 'service_section', 
					'label' => __( 'Services Column', 'aqwa' ),
					'description' => __( 'Change the home service column','aqwa' ),
					'choices' => array(
						'col-lg-6' => __( 'Two Column','aqwa' ),
						'col-lg-4' => __( 'Three Column' ,'aqwa' ),
						'col-lg-3' => __( 'Four Column' ,'aqwa' ),
					),
				)
			); 		

			// items
			$wp_customize->add_setting( 'aqwa_service_items', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => aqwa_default_service_items(),
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_service_items', array(
				'label'   => esc_html__('Service Item','amigo-extensions'),
				'section' => 'service_section',
				'priority' => 10,				
				'customizer_repeater_image_control' => true,
				'customizer_repeater_icon_control' => true,	
				'customizer_repeater_title_control' => true,				
				'customizer_repeater_text_control' => true,
				'customizer_repeater_link2_control' => true,
				'customizer_repeater_text2_control'=> true,	

			) ) );							

		}

		public function add_partials( $wp_customize ) {
			// about title
			$wp_customize->selective_refresh->add_partial( 'aqwa_service_section_title', array(
				'selector'            => '.our-services .section-title h5',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_service_section_title' ); },
			) );

			// about sub-title
			$wp_customize->selective_refresh->add_partial( 'aqwa_service_section_subtitle', array(
				'selector'            => '.our-services .section-title h2',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_service_section_subtitle' ); },

			) );

			// about text
			$wp_customize->selective_refresh->add_partial( 'aqwa_service_section_text', array(
				'selector'            => '.our-services .section-title p',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_service_section_text' ); },

			) );			

			// repeter control edit
			$wp_customize->selective_refresh->add_partial( 'aqwa_service_items', array(
				'selector'            => '.our-services .service-items',
			) );
			
		}		
	}

	new Aqwa_Service_Section_Customizer();
}