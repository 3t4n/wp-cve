<?php
/**
 * Customizer footer contact settings for this theme.
 *
 * 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Aqwa_Footer_Contact_Customize' ) ) {

	class Aqwa_Footer_Contact_Customize{

		public $default = '';		

		public function __construct() {	

			$this->default = aqwa_footer_section_default();

			add_action( 'customize_register', array( $this, 'customizer_panels' ) );
			add_action( 'customize_register', array( $this, 'customizer_sections' ) );			
			add_action( 'customize_register', array( $this, 'footer_contact_area_customizer_settings' ) );
			add_action( 'customize_register', array( $this, 'customizer_partials' ) );
		}

		// add panels
		public function customizer_panels( $wp_customize ){			
			if ( ! $wp_customize->get_panel( 'footer_section' ) ) {
				$wp_customize->add_panel( 'footer_section', 
					array(
						'priority'      => 160,
						'capability'    => 'edit_theme_options',
						'title'			=> __('Footer', 'amigo-extensions'),
					) 
				);
			}
		}

		// add section
		public function customizer_sections( $wp_customize ) {				

			// section footer contact area
			$wp_customize->add_section( 'footer_contact' , array(
				'title' =>  __( 'Footer Callout', 'amigo-extensions' ),
				'panel' => 'footer_section',
				'priority'      => 2,
			) );

		}
		

		public function footer_contact_area_customizer_settings( $wp_customize ) {			

			$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

			// display header contact details
			$wp_customize->add_setting(	'aqwa_display_footer_contact_detail',
				array(
					'default' => $this->default['aqwa_display_footer_contact_detail'],
					'capability'     	=> 'edit_theme_options',
					'sanitize_callback' => 'aqwa_sanitize_checkbox',
					'priority'      => 1,
				)
			);	

			$wp_customize->add_control( 'aqwa_display_footer_contact_detail',
				array(
					'label'   		=> __('Show/Hide Section','amigo-extensions'),
					'section'		=> 'footer_contact',
					'type' 			=> 'checkbox',
					'transport'         => $selective_refresh,
				)  
			);	

			// footer contact widgets
			$wp_customize->add_setting( 'aqwa_footer_contacts_items', array(
				'sanitize_callback' => 'amigo_repeater_sanitize',
				'default' => aqwa_default_footer_contact_items(),
			));
			$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'aqwa_footer_contacts_items', array(
				'label'   => esc_html__('Footer Callout Item','amigo-extensions'),
				'item_name' => esc_html__( 'Item', 'amigo-extensions' ),
				'section' => 'footer_contact',
				'priority' => 10,				
				'customizer_repeater_title_control' => true,				
				'customizer_repeater_text_control' => true,				
				'customizer_repeater_icon_control' => true,								
				
			) ) );		

		}

		public static function customizer_partials( $wp_customize ){			
			
			$wp_customize->selective_refresh->add_partial( 'aqwa_footer_contacts_items', array(
				'selector'            => '.footer-wrapper .footer-contact .row',			
				
			) );			

		}		
	}

	new Aqwa_Footer_Contact_Customize();
}