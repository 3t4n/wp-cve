<?php //Callout
if ( ! function_exists( 'icycp_industryup_callout_customize_register' ) ) :
function icycp_industryup_callout_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'home_callout_section' , array(
		'title'      => __('Callout settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 50,
   	) );
		
		// Enable slider
		
		
		
		$wp_customize->add_setting( 'homepage_callout_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_industryup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Industryup_Toggle_Switch_Custom_control( $wp_customize, 'homepage_callout_show',
		   array(
			  'label' => esc_html__( 'Callout Enable/Disable' ),
			  'section' => 'home_callout_section'
		   )
		) );

		//Callout background Image
		$wp_customize->add_setting( 'callout_background_image',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/callout/callout-back.jpg',
		'sanitize_callback' => 'esc_url_raw'));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'callout_background_image',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'callout_background_image',
					'section' => 'home_callout_section',
					
				)
			)
		);
		
		// Image overlay
		$wp_customize->add_setting( 'callout_back_image_overlay', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control('callout_back_image_overlay', array(
			'label'    => __('Enable callout image overlay', 'consultup' ),
			'section'  => 'home_callout_section',
			'type' => 'checkbox',
		) );
		
		
		//CTA Background Overlay Color
		$wp_customize->add_setting( 'callout_back_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
            ) );	
            
            $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,'callout_back_overlay_color', array(
               'label'      => __('Callout image overlay color','consultup' ),
                'palette' => true,
                'section' => 'home_callout_section')
            ) );
		
		
		// callout title
		$wp_customize->add_setting( 'callout_title',array(
		'default' => __('Trusted By Over 10,000 Worldwide Businesses. Try Today!','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'callout_title',array(
		'label'   => __('Title','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));	
		
		//callout description
		$wp_customize->add_setting( 'callout_discription',array(
		'default' => 'We must explain to you how all this mistaken idea of denouncing pleasure',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'callout_discription',array(
		'label'   => __('Description','consultup'),
		'section' => 'home_callout_section',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'callout_btn_txt',array(
		'default' => __('Get Started Now!','consultup'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'callout_btn_txt',array(
		'label'   => __('Button Text','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'callout_btn_link',array(
		'default' => 'https://demos.themeansar.com/industryup-demos/',
		));	
		$wp_customize->add_control( 'callout_btn_link',array(
		'label'   => __('Button Link','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'callout_btn_target', 
			array(
			'default'        => false,
		));
		$wp_customize->add_control('callout_btn_target', array(
			'label'   => __('Open link in new tab/window', 'consultup'),
			'section' => 'home_callout_section',
			'type' => 'checkbox',
		));
		
		
		
}

add_action( 'customize_register', 'icycp_industryup_callout_customize_register' );
endif;


/**
 * Selective refresh for testimonial section
 */
function icycp_industryup_register_testimonial_section_partials( $wp_customize ){


	//Callout title
	$wp_customize->selective_refresh->add_partial( 'callout_title', array(
		'selector'            => '.calltoaction h3',
		'settings'            => 'callout_title',
		'render_callback'  => 'icycp_consultup_callout_title_render_callback',
	
	) );	

	//Description
	$wp_customize->selective_refresh->add_partial( 'callout_discription', array(
		'selector'            => '.calltoaction h2',
		'settings'            => 'callout_discription',
		'render_callback'  => 'icycp_consultup_callout_discription_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'callout_btn_txt', array(
		'selector'            => '.calltoaction .btn-0',
		'settings'            => 'callout_btn_txt',
		'render_callback'  => 'icycp_consultup_callout_btn_txt_render_callback',
	
	) );
	
}

add_action( 'customize_register', 'icycp_industryup_register_testimonial_section_partials' );

//Callout Section
function icycp_consultup_callout_title_render_callback() {
	return get_theme_mod( 'callout_title' );
}

function icycp_consultup_callout_discription_render_callback() {
	return get_theme_mod( 'callout_discription' );
}


function icypb_consultup_news_section_description_render_callback() {
	return get_theme_mod( 'news_section_description' );
}

if ( ! function_exists( 'icycp_industryup_switch_sanitization' ) ) {
		function icycp_industryup_switch_sanitization( $input ) {
			if ( true === $input ) {
				return 1;
			} else {
				return 0;
			}
		}
}

//Sanatize text validation
function icycp_industryup_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );
}