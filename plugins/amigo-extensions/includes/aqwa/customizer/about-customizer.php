<?php
/**
 * Customizer settings about section  
 * 
 * 
 */

if ( ! class_exists( 'Aqwa_About_Section_Customizer' ) ) {

	class Aqwa_About_Section_Customizer{

		public function __construct() {

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );
			add_action( 'customize_register', array( $this, 'about_section_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'customizer_partials' ) );
		}

		public function customizer_panels( $wp_customize ) {	

			if( ! $wp_customize->get_panel( 'frontpage_panel' ) ){
				if ( ! $wp_customize->get_panel( 'frontpage_panel' ) ) {
					$wp_customize->add_panel('frontpage_panel',array(

						'priority'      => 24,
						'capability'    => 'edit_theme_options',
						'title'			=> __('Homepage Sections', 'amigo-extensions'),
					));
				}
			}
		}

		public function customizer_sections( $wp_customize ) {	

			//homepage about section  
			$wp_customize->add_section('about_section', array(
				'title'    => esc_html__( 'About', 'amigo-extensions' ),
				'panel'	=> 'frontpage_panel',
				'priority' => 1,		

			));	

			
		}

		public function about_section_customizer_settings( $wp_customize ) {

			// call default settings
			$default = aqwa_about_section_default();

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			// is display
			$wp_customize->add_setting(
				'aqwa_display_about_section',
				array(
					'default' => $default['aqwa_display_about_section'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_about_section',
				array(
					'label'   		=> __('Show/Hide About Section','amigo-extensions'),
					'section'		=> 'about_section',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// about section title
			$wp_customize->add_setting(
				'aqwa_about_section_title',
				array(
					'default' => esc_html( $default['aqwa_about_section_title'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_about_section_title',
				array(
					'label'   		=> __('Title','amigo-extensions'),
					'section'		=> 'about_section',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);		

			// about section sub title
			$wp_customize->add_setting(
				'aqwa_about_section_subtitle',
				array(
					'default' => esc_html( $default['aqwa_about_section_subtitle'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_about_section_subtitle',
				array(
					'label'   		=> __('Sub Title','amigo-extensions'),
					'section'		=> 'about_section',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);	

			// about section text
			$wp_customize->add_setting(
				'aqwa_about_section_text',
				array(
					'default' => esc_html( $default['aqwa_about_section_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_about_section_text',
				array(
					'label'   		=> __('Text','amigo-extensions'),
					'section'		=> 'about_section',
					'type' 			=> 'textarea',
					'transport'         => $selective_refresh,
				)  
			);	

			// is display youtube
			$wp_customize->add_setting(
				'aqwa_display_about_section_youtube',
				array(
					'default' => $default['aqwa_display_about_section_youtube'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_about_section_youtube',
				array(
					'label'   		=> __('Show/Hide Youtube Section','amigo-extensions'),
					'section'		=> 'about_section',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// youtube link
			$wp_customize->add_setting(
				'aqwa_about_section_youtube_link',
				array(
					'default' => esc_html( $default['aqwa_about_section_youtube_link'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_url',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_about_section_youtube_link',
				array(
					'label'   		=> __('Youtube Video URL','amigo-extensions'),
					'section'		=> 'about_section',
					'type' 			=> 'url',
					'transport'         => $selective_refresh,
				)  
			);	

			// image one
			$wp_customize->add_setting(
				'aqwa_about_section_image_one',
				array(
					'default' => esc_html( $default['aqwa_about_section_image_one'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_url',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( new WP_Customize_Image_Control(
				$wp_customize,
				'aqwa_about_section_image_one',
				array(
					'label'      => __( 'Image One', 'amigo-extensions' ),
					'section'    => 'about_section',
					'settings'   => 'aqwa_about_section_image_one',
					'context'    => 'your_setting_context'
				)
			)); 

			// image two
			$wp_customize->add_setting(
				'aqwa_about_section_image_two',
				array(
					'default' => esc_html( $default['aqwa_about_section_image_two'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_url',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( new WP_Customize_Image_Control(
				$wp_customize,
				'aqwa_about_section_image_two',
				array(
					'label'      => __( 'Image Two', 'amigo-extensions' ),
					'section'    => 'about_section',
					'settings'   => 'aqwa_about_section_image_two',
					'context'    => 'your_setting_context'
				)
			)); 


			// items

			$wp_customize->add_setting( 'aqwa_about_item', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => aqwa_default_about_items(),
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_about_item', array(
				'label'   => esc_html__('About Item','amigo-extensions'),
				'section' => 'about_section',
				'priority' => 10,				
				'customizer_repeater_title_control' => true,									
				'customizer_repeater_icon_control' => true,									
				
			) ) );							

		}

		public function customizer_partials( $wp_customize ) {
			// about title
			$wp_customize->selective_refresh->add_partial( 'aqwa_about_section_title', array(
				'selector'            => '.about-section .section-title h5',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_about_section_title' ); },
			) );

			// about sub-title
			$wp_customize->selective_refresh->add_partial( 'aqwa_about_section_subtitle', array(
				'selector'            => '.about-section .section-title h2',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_about_section_subtitle' ); },

			) );

			// about text
			$wp_customize->selective_refresh->add_partial( 'aqwa_about_section_text', array(
				'selector'            => '.about-section .section-title p',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_about_section_text' ); },

			) );

			// about image one
			$wp_customize->selective_refresh->add_partial( 'aqwa_about_section_image_one', array(
				'selector'            => '.about-section .about-big-img',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_about_section_image_one' ); },

			) );

			// about image two
			$wp_customize->selective_refresh->add_partial( 'aqwa_about_section_image_two', array(
				'selector'            => '.about-section .about-small-img',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_about_section_image_two' ); },

			) );

			// about items
			$wp_customize->selective_refresh->add_partial( 'aqwa_about_item', array(
				'selector'            => '.about-section .about-list',		

			) );
			
		}		
	}

	new Aqwa_About_Section_Customizer();
}