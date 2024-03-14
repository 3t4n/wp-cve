<?php
/**
 * Customizer header bar settings for this theme.
 *
 * 
 * 
 */

if ( ! class_exists( 'Aqwa_Header_Bar_Customize' ) ) {

	class Aqwa_Header_Bar_Customize{

		private $default = '';

		public function __construct() {

			$this->default = aqwa_default_settings();

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );
			add_action( 'customize_register', array( $this, 'top_header_bar_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'social_icons_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'header_contact_area_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'customizer_partials' ) );
		}

		public function customizer_panels( $wp_customize ) {	

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

			// abover header 
			$wp_customize->add_section( 'header_top' , array(
				'title' =>  __( 'Header Top Bar', 'amigo-extensions' ),
				'panel' => 'my_header',
				'priority'      => 1,
			) );	

			// header contacts
			$wp_customize->add_section( 'header_contacts' , array(
				'title' =>  __( 'Header Contacts', 'amigo-extensions' ),
				'panel' => 'my_header',
				'priority'      => 2,
			) );	
		}

		public function top_header_bar_customizer_settings( $wp_customize ) {

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			// is above text active
			$wp_customize->add_setting(
				'aqwa_is_header_top_bar',
				array(
					'default' => $this->default['aqwa_is_header_top_bar'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_is_header_top_bar',
				array(
					'label'   		=> __('Show/Hide Section','amigo-extensions'),
					'section'		=> 'header_top',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);

			// header above text
			$wp_customize->add_setting(
				'aqwa_header_above_text',
				array(
					'default' => esc_html( $this->default['aqwa_header_above_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_html',
					'priority'      => 2,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_header_above_text',
				array(
					'label'   		=> __('Above Text','amigo-extensions'),
					'section'		=> 'header_top',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);	
			

			// header above text schedule
			$wp_customize->add_setting(
				'aqwa_header_schedule_text',
				array(
					'default' => esc_html( $this->default['aqwa_header_schedule_text'] ),
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_text',
					'priority'      => 4,
				)
			);	

			$wp_customize->add_control( 
				'aqwa_header_schedule_text',
				array(
					'label'   		=> __('Schedule Text','amigo-extensions'),
					'section'		=> 'header_top',
					'type' 			=> 'text',
					'transport'         => $selective_refresh,
				)  
			);
		}


		public function social_icons_customizer_settings( $wp_customize ) {

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			
			// seprator			
			$wp_customize->add_setting('separator_social_icons', array('priority'=> 10));
			$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
				'separator_social_icons', array(
					'label' => __('Social Icons','amigo-extensions'),
					'settings' => 'separator_social_icons',
					'section' => 'header_top',					
				)));



			// is above text active
			$wp_customize->add_setting(
				'aqwa_display_social_icons',
				array(
					'default' => $this->default['aqwa_display_social_icons'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',			
				)
			);	

			$wp_customize->add_control( 
				'aqwa_display_social_icons',
				array(
					'label'   		=> __('Show/Hide Social Icons','amigo-extensions'),
					'section'		=> 'header_top',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
					'priority'      => 10,
				)  
			);

			// header contact widgets
			$wp_customize->add_setting( 'aqwa_social_icons', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => aqwa_default_social_icons(),
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_social_icons', array(
				'label'   => esc_html__('Social Icon','amigo-extensions'),
				'section' => 'header_top',
				'priority' => 10,						
				'customizer_repeater_icon_control' => true,								
				'customizer_repeater_link_control' => true,						
				
			) ) );	
			
		}


		public function header_contact_area_customizer_settings( $wp_customize ) {	

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
			

			// display header contact details
			$wp_customize->add_setting(	'aqwa_display_header_contact_detail',
				array(
					'default' => $this->default['aqwa_display_header_contact_detail'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 6,
				)
			);	

			$wp_customize->add_control( 'aqwa_display_header_contact_detail',
				array(
					'label'   		=> __('Show/Hide Section','amigo-extensions'),
					'section'		=> 'header_contacts',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// header contact widgets
			$wp_customize->add_setting( 'aqwa_header_contacts_items', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => aqwa_default_header_contact_items(),
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_header_contacts_items', array(
				'label'   => esc_html__('Header Info','amigo-extensions'),
				'item_name' => esc_html__( 'Info', 'amigo-extensions' ),
				'section' => 'header_contacts',
				'priority' => 10,				
				'customizer_repeater_title_control' => true,				
				'customizer_repeater_text_control' => true,				
				'customizer_repeater_icon_control' => true,								
				
			) ) );		

			
		}

		public static function customizer_partials( $wp_customize ){			

			$wp_customize->selective_refresh->add_partial( 'aqwa_header_above_text', array(
				'selector'            => '.topbar-left',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_header_above_text' ); },

			) );

			$wp_customize->selective_refresh->add_partial( 'aqwa_header_schedule_text', array(
				'selector'            => '.topbar-right',				
				'render_callback'  => function() { return get_theme_mod( 'aqwa_header_schedule_text' ); },

			) );

			// one contact
			$wp_customize->selective_refresh->add_partial( 'aqwa_header_contacts_items', array(
				'selector'            => '.main-header .h-c',					

			) );
			

			// social section
			$wp_customize->selective_refresh->add_partial( 'aqwa_social_icons', array(
				'selector'            => '.topbar-right .social-media',				
				

			) );	
		}	

	}	
}

new Aqwa_Header_Bar_Customize();

?>