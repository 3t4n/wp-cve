<?php

function section_hide_show_setting( $wp_customize ) {
	//Ordering Section
		$wp_customize->add_section( 'global_ordering_section' , array(
			'title'  => 'Home Page Ordering Section',
			'panel'  => 'theme_option_panel',	
		) );
		//add Control
			$wp_customize->add_setting('global_ordering', array(
				'default'  => array( 
						'featured_slider_activate',
						'featured_section_info_activate',	
						'woocommerce_product_section_activate',
						'about_section_activate',						
						'our_portfolio_section_activate',
						'our_services_activate',
						'our_team_activate',
						'our_testimonial_activate',							
						'our_sponsors_activate',						
					),
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'custom_sanitize_select',
		    ));
		    $wp_customize->add_control( new hide_show_custom_ordering(
		    	$wp_customize,'global_ordering',
		    	array(
			        'settings' => 'global_ordering',
			        'label'   => 'Select Section',
			        'description' => 'Hide & Show Section',
			        'section' => 'global_ordering_section',
			        'type'    => 'sortable_repeater',
			        'choices'     => array(
						'featured_slider_activate' => 'Featured Slider',
						'featured_section_info_activate' => 'Featured Section',
						'woocommerce_product_section_activate' => 'Woocommerce Product',
						'about_section_activate'	=> 'About Section',
						'our_portfolio_section_activate'	=> 'Our Portfolio',
						'our_services_activate'	=> 'Our Services',							
						'our_team_activate'	=> 'Our Team',	
						'our_testimonial_activate'	=> 'Our Testimonial',
						'our_sponsors_activate'	=> 'Our Sponsors',												
					),
			    )
			));	
		//diseble section    
			$wp_customize->add_setting('custom_ordering_diseble', array(
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'custom_ordering_diseble',
		    	array(
			        'settings' => 'custom_ordering_diseble',
			        'section' => 'global_ordering_section',
			        'type'    => 'hidden',
			    )
			));	
	    //Drag and Drop in pro option
			$wp_customize->add_setting('drag_drop_section_pro', array(
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new drag_drop_option_Control(
		    	$wp_customize,'drag_drop_section_pro',
		    	array(
			        'settings' => 'drag_drop_section_pro',
			        'section' => 'global_ordering_section',
		        )
		    ));
}
add_action( 'customize_register', 'section_hide_show_setting' );