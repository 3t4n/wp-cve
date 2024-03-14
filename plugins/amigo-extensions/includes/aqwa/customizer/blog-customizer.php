<?php
/**
 * Customizer Post section settings
 *
 * 
 */

if ( ! class_exists( 'Aqwa_Blog_Section_Customizer' ) ) {

	class Aqwa_Blog_Section_Customizer{

		public function __construct() {

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );
			add_action( 'customize_register', array( $this, 'home_blog_section_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'customizer_partials' ) );
		}

		public function customizer_panels( $wp_customize ) {	

			// add panel
			if ( ! $wp_customize->get_panel( 'frontpage_panel' ) ) {
				$wp_customize->add_panel('frontpage_panel',array(

					'priority'      => 24,
					'capability'    => 'edit_theme_options',
					'title'			=> __('Homepage Sections', 'amigo-extensions'),
				));
			}
		}

		public function customizer_sections( $wp_customize ) {	

			// add blog section
			$wp_customize->add_section('home_blog_section', array(
				'title'    => esc_html__( 'Blog', 'amigo-extensions' ),
				'panel'	=> 'frontpage_panel',
				'priority' => 1,		

			));	
			
		}

		public function home_blog_section_customizer_settings( $wp_customize ) {

			// default settings
			$default = aqwa_blog_section_default();

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			// blog is display
			$wp_customize->add_setting(
				'aqwa_display_blog_section',
				array(
					'default' => $default['aqwa_display_blog_section'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_blog_section',
				array(
					'label'   		=> __('Show/Hide Post Section','amigo-extensions'),
					'section'		=> 'home_blog_section',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// blog section title
			$wp_customize->add_setting(
				'aqwa_blog_section_title',
				array(
					'default' => esc_html( $default['aqwa_blog_section_title'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_blog_section_title',
				array(
					'label'   		=> __('Title','amigo-extensions'),
					'section'		=> 'home_blog_section',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);		

			// blog section sub title
			$wp_customize->add_setting(
				'aqwa_blog_section_subtitle',
				array(
					'default' => esc_html( $default['aqwa_blog_section_subtitle'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_blog_section_subtitle',
				array(
					'label'   		=> __('Sub Title','amigo-extensions'),
					'section'		=> 'home_blog_section',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);	

			// blog section text
			$wp_customize->add_setting(
				'aqwa_blog_section_text',
				array(
					'default' => esc_html( $default['aqwa_blog_section_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_blog_section_text',
				array(
					'label'   		=> __('Text','amigo-extensions'),
					'section'		=> 'home_blog_section',
					'type' 			=> 'textarea',
					'transport'         => $selective_refresh,
				)  
			);

			// column settings
			$wp_customize->add_setting('aqwa_blog_section_column',
				array(
					'default' => $default['aqwa_blog_section_column'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_radio',
					'priority'      => 1,
				)
			);
			$wp_customize->add_control( 'aqwa_blog_section_column',
				array( 
					'type' => 'radio',
					'section' => 'home_blog_section', 
					'label' => __( 'Blog Column', 'aqwa' ),
					'description' => __( 'Change the home blog column','aqwa' ),
					'choices' => array(
						'2' => __( 'Two Column','aqwa' ),
						'3' => __( 'Three Column' ,'aqwa' ),
						'4' => __( 'Four Column' ,'aqwa' ),
					),
				)
			); 

			
		}

		public function customizer_partials( $wp_customize ) {
			// about title
			$wp_customize->selective_refresh->add_partial( 'aqwa_blog_section_title', array(
				'selector'            => '.our-blog .section-title h5',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_blog_section_title' ); },
			) );

			// about sub-title
			$wp_customize->selective_refresh->add_partial( 'aqwa_blog_section_subtitle', array(
				'selector'            => '.our-blog .section-title h2',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_blog_section_subtitle' ); },

			) );

			// about text
			$wp_customize->selective_refresh->add_partial( 'aqwa_blog_section_text', array(
				'selector'            => '.our-blog .section-title p',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_blog_section_text' ); },

			) );		
			
		}			
	}

	new Aqwa_Blog_Section_Customizer();
}