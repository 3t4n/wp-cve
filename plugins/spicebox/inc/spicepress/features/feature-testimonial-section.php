<?php
if ( ! function_exists( 'spiceb_spicepress_testimonial_customize_register' ) ) :
function spiceb_spicepress_testimonial_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Testimonial Section */
	$wp_customize->add_section( 'testimonial_section' , array(
			'title'      => __('Testimonial settings', 'spicebox'),
			'panel'  => 'section_settings',
			'priority'   => 7,
		) );
		
		// Enable testimonial section
		$wp_customize->add_setting( 'testimonial_section_enable' , array( 'default' => 'on') );
		$wp_customize->add_control(	'testimonial_section_enable' , array(
				'label'    => __( 'Enable Home Testimonial section', 'spicebox' ),
				'section'  => 'testimonial_section',
				'type'     => 'radio',
				'choices' => array(
					'on'=>__('ON', 'spicebox'),
					'off'=>__('OFF', 'spicebox')
				)
		));
		
		//Testimonial Background Image
			$wp_customize->add_setting( 'testimonial_callout_background',array('default' => SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testimonial-bg.jpg',
			'sanitize_callback' => 'esc_url_raw', 'transport'         => $selective_refresh,));
			
			
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'testimonial_callout_background', array(
			  'label'    => __( 'Background Image', 'spicebox' ),
			  'section'  => 'testimonial_section',
			  'settings' => 'testimonial_callout_background',
			) ) );
			
			// Image overlay
		$wp_customize->add_setting( 'testimonial_image_overlay', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control('testimonial_image_overlay', array(
			'label'    => __('Enable testimonial image overlay', 'spicebox' ),
			'section'  => 'testimonial_section',
			'type' => 'checkbox',
		) );
		
		
		//Testimonial Background Overlay Color
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0,0,0,0.6)',
            ) );	
            
            $wp_customize->add_control(new SpiceBox_Customize_Alpha_Color_Control( $wp_customize,'testimonial_overlay_section_color', array(
               'label'      => __('Testimonial image overlay color','spicebox' ),
                'palette' => true,
                'section' => 'testimonial_section')
            ) );
			
		
		// testimonial section title
		$wp_customize->add_setting( 'home_testimonial_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Lorem ipsum dolor','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_testimonial_section_title',array(
		'label'   => __('Title','spicebox'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));	
		
		//testimonial section discription
		$wp_customize->add_setting( 'home_testimonial_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default'=> __('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_testimonial_section_discription',array(
		'label'   => __('Description','spicebox'),
		'section' => 'testimonial_section',
		'type' => 'textarea',
		));

		//testimonial one image
		if(wp_get_theme()->name == 'SpicePress Dark'){

		$wp_customize->add_setting( 'home_testimonial_thumb',array('default' => SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testi-2.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport'         => $selective_refresh,));
	 
		}
		else{
		$wp_customize->add_setting( 'home_testimonial_thumb',array('default' => SPICEB_PLUGIN_URL .'inc/spicepress/images/testimonial/testi1.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport'         => $selective_refresh,));
	 	}
	 	
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_testimonial_thumb',
				array(
					'label' => __('Image','spicebox'),
					'section' => 'example_section_one',
					'settings' =>'home_testimonial_thumb',
					'section' => 'testimonial_section',
					'type' => 'upload',
				)
			)
		);
		
		//testimonial description
		$wp_customize->add_setting( 'home_testimonial_desc',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_testimonial_desc',array(
		'label'   => __('Description','spicebox'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		
		// testimonial section title
		$wp_customize->add_setting( 'home_testimonial_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Ipsum dolor','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		//'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_testimonial_title',array(
		'label'   => __('Title','spicebox'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		
		$wp_customize->add_setting( 'home_testimonial_designation',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Ligula Eget','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_testimonial_designation',array(
		'label'   => __('Designation','spicebox'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
                
                 $theme = wp_get_theme(); // gets the current theme
if ($theme->name == 'Stacy' && version_compare(wp_get_theme()->get('Version'), '1.3.3') > 0):
    
    // Service Layout settings
    if(get_option('stacy_user', 'new')=='old') {
        $wp_customize->add_setting('testimonial_design', array(
            'default' => 'default',
            'sanitize_callback' => 'stacy_image_radio_button_sanitization',
        ));
        
    } else {
        $wp_customize->add_setting('testimonial_design', array(
            'default' => 'center-effect',
            'sanitize_callback' => 'stacy_image_radio_button_sanitization',
        ));
        
    }
    
        $wp_customize->add_control(new Spicebox_Image_Radio_Button_Custom_Control($wp_customize, 'testimonial_design',
            array(
                'label' => esc_html__('Testimonial Design', 'stacy'),
                'section' => 'testimonial_section',
                'choices' => array(
                    'default' => array(
                        'image' => SPICEB_PLUGIN_URL . '/inc/spicepress/images/stacy/stacy-testimonial-default.png',
                        'name' => esc_html__('Standard', 'stacy')
                    ),
                    'center-effect' => array(
                        'image' => SPICEB_PLUGIN_URL . '/inc/spicepress/images/stacy/stacy-testimonial-center.png',
                        'name' => esc_html__('Center-effect', 'stacy')
                    )
                )
            )
    ));
    
    endif;
		
		
}

add_action( 'customize_register', 'spiceb_spicepress_testimonial_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_spicepress_register_home_testimonial_section_partials( $wp_customize ){


	
		//Testimonial
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_section_title', array(
		'selector'            => '.testimonial-section .section-header h1',
		'settings'            => 'home_testimonial_section_title',
		'render_callback'  => 'spiceb_spicepress_testimonial_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_section_discription', array(
		'selector'            => '.testimonial-section .section-header p',
		'settings'            => 'home_testimonial_section_discription',
		'render_callback'  => 'spiceb_spicepress_testimonial_section_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_desc', array(
		'selector'            => '.author-description p, .home .testmonial-block p',
		'settings'            => 'home_testimonial_desc',
		'render_callback'  => 'spiceb_spicepress_testimonial_desc_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_title', array(
		'selector'            => '.testmonial-area h4, .home , .home .testmonial-block h4',
		'settings'            => 'home_testimonial_title',
		'render_callback'  => 'spiceb_spicepress_testimonial_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_designation', array(
		'selector'            => '.testmonial-area span.designation, .home .testmonial-block span.designation',
		'settings'            => 'home_testimonial_designation',
		'render_callback'  => 'spiceb_spicepress_testimonial_designation_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_callout_background', array(
		'selector'            => 'section.testimonial-section',
		'settings'            => 'testimonial_callout_background',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_thumb', array(
		'selector'            => '.testmonial-area .author-box, .home .testmonial-block .avatar',
		'settings'            => 'home_testimonial_thumb',
	
	) );
	
	
}

add_action( 'customize_register', 'spiceb_spicepress_register_home_testimonial_section_partials' );


function spiceb_spicepress_testimonial_section_title_render_callback() {
	return get_theme_mod( 'home_testimonial_section_title' );
}

function spiceb_spicepress_testimonial_section_discription_render_callback() {
	return get_theme_mod( 'home_testimonial_section_discription' );
}

function spiceb_spicepress_testimonial_desc_render_callback() {
	return get_theme_mod( 'home_testimonial_desc' );
}

function spiceb_spicepress_testimonial_title_render_callback() {
	return get_theme_mod( 'home_testimonial_title' );
}

function spiceb_spicepress_testimonial_designation_render_callback() {
	return get_theme_mod( 'home_testimonial_designation' );
}