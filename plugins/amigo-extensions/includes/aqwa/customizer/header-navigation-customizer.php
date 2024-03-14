<?php
/**
 * Customizer settings for this theme.
 *
 * 
 */

if ( ! class_exists( 'Aqwa_Primary_Menu_Customize' ) ) {

	class Aqwa_Primary_Menu_Customize{

		private $default = '';
		private $selective_refresh = '';

		public function __construct() {

			$this->default = aqwa_default_settings();
			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );
			add_action( 'customize_register', array( $this, 'primary_menu_link_button_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'primary_menu_search_button_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'customizer_partials' ) );
		}

		public function customizer_panels( $wp_customize ) {

			$this->selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

			if( ! $wp_customize->get_panel( 'my_header' ) ){
				$wp_customize->add_panel( 
					'my_header', 
					array(
						'priority'      => 22,
						'capability'    => 'edit_theme_options',
						'title'			=> __('Header', 'aqwa'),
					) 
				);
			}


		}

		public function customizer_sections( $wp_customize ) {	

			// add section
			$wp_customize->add_section(
				'header_navigation',
				array(
					'title' 		=> __('Header Navigation','amigo-extensions'),
					'panel'  		=> 'my_header',
					'priority'      => 1,
				)
			);
		}

		public function primary_menu_link_button_customizer_settings( $wp_customize ) {

			// seprator			
			$wp_customize->add_setting('separator_primary_menu_link_button', array('priority'=> 0));
			$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
				'separator_primary_menu_link_button', array(
					'label' => __('Link Button','amigo-extensions'),
					'settings' => 'separator_primary_menu_link_button',
					'section' => 'header_navigation',					
				)));				

			// is primary menu search 
			$wp_customize->add_setting(
				'aqwa_display_primary_menu_link_button',
				array(
					'default' => $this->default['aqwa_display_primary_menu_link_button'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_primary_menu_link_button',
				array(
					'label'   		=> __('Show/Hide link button','amigo-extensions'),
					'section'		=> 'header_navigation',
					'type' 			=> 'checkbox',
					'transport'         => $this->selective_refresh,
				)  
			);	


			// primary menu search button text 
			$wp_customize->add_setting(
				'aqwa_primary_menu_link_button_text',
				array(
					'default' => esc_html( $this->default['aqwa_primary_menu_link_button_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_primary_menu_link_button_text',
				array(
					'label'   		=> __('Button Text', 'amigo-extensions'),
					'section'		=> 'header_navigation',
					'type' 			=> 'text',
					'transport'      => $this->selective_refresh,
				)  
			);

			// link button link
			$wp_customize->add_setting(
				'aqwa_primary_menu_link_button_link',
				array(
					'default' => esc_html( $this->default['aqwa_primary_menu_link_button_link'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_primary_menu_link_button_link',
				array(
					'label'   		=> __('Link', 'amigo-extensions'),
					'section'		=> 'header_navigation',
					'type' 			=> 'text',
					'transport'      => $this->selective_refresh,
				)  
			);	

		}

		public function primary_menu_search_button_customizer_settings( $wp_customize ) {

			// seprator			
			$wp_customize->add_setting('separator_primary_menu_search_button', array('priority'=> 3));
			$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
				'separator_primary_menu_search_button', array(
					'label' => __('Search Popup Button','amigo-extensions'),
					'settings' => 'separator_primary_menu_search_button',
					'section' => 'header_navigation',					
				)));				

			// is primary menu search 
			$wp_customize->add_setting(
				'aqwa_display_primary_menu_search_button',
				array(
					'default' => $this->default['aqwa_display_primary_menu_search_button'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 4,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_primary_menu_search_button',
				array(
					'label'   		=> __('Show/Hide Button','amigo-extensions'),
					'section'		=> 'header_navigation',
					'type' 			=> 'checkbox',
					'transport'         => $this->selective_refresh,
				)  
			);	

			// label
			$wp_customize->add_setting(
				'aqwa_primary_menu_search_button_overlay_label',
				array(
					'default' => esc_html( $this->default['aqwa_primary_menu_search_button_overlay_label'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 5,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_primary_menu_search_button_overlay_label',
				array(
					'label'   		=> __('Popup Title', 'amigo-extensions'),
					'section'		=> 'header_navigation',
					'type' 			=> 'text',
					'transport'      => $this->selective_refresh,
				)  
			);	

			// text
			$wp_customize->add_setting(
				'aqwa_primary_menu_search_button_overlay_text',
				array(
					'default' => esc_html( $this->default['aqwa_primary_menu_search_button_overlay_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      =>5,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_primary_menu_search_button_overlay_text',
				array(
					'label'   		=> __('Popup Description', 'amigo-extensions'),
					'section'		=> 'header_navigation',
					'type' 			=> 'textarea',
					'transport'      => $this->selective_refresh,
				)  
			);	

		}

		public function customizer_partials( $wp_customize ) {

			$wp_customize->selective_refresh->add_partial( 'aqwa_display_primary_menu_link_button', array(
				'selector'            => '.topbar-left',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_display_primary_menu_link_button' ); },

			) );

			$wp_customize->selective_refresh->add_partial( 'aqwa_primary_menu_link_button_text', array(
				'selector'            => 'form.d-flex .btn-quote',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_primary_menu_link_button_text' ); },

			) );

			$wp_customize->selective_refresh->add_partial( 'aqwa_display_primary_menu_search_button', array(
				'selector'            => 'form.d-flex .btn-search',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_display_primary_menu_search_button' ); },

			) );

		}		

	}	

	new Aqwa_Primary_Menu_Customize();
}