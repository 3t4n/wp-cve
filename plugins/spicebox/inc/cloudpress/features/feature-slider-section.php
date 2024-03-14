<?php if ( ! function_exists( 'spiceb_cloudpress_slider_customize_register' ) ) :
function spiceb_cloudpress_slider_customize_register($wp_customize){
	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

	/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider settings', 'spicebox'),
		'panel'  => 'section_settings',
		'priority'   => 1,
   	) );
		
	// Enable slider
	$wp_customize->add_setting( 'home_page_slider_enabled' , array( 'default' => 'on') );
	$wp_customize->add_control(	'home_page_slider_enabled' , array(
			'label'    => __( 'Enable slider', 'spicebox' ),
			'section'  => 'slider_section',
			'type'     => 'radio',
			'choices' => array(
				'on'=>__('ON', 'spicebox'),
				'off'=>__('OFF', 'spicebox')
			)
	));
		
		
	//Slider Image
	$wp_customize->add_setting( 'home_slider_image',array('default' => SPICEB_PLUGIN_URL .'inc/cloudpress/images/slider/slider.jpg',
	'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'home_slider_image',
			array(
				'type'        => 'upload',
				'label' => __('Image','spicebox'),
				'settings' =>'home_slider_image',
				'section' => 'slider_section',
				
			)
		)
	);
		
	// Image overlay
	$wp_customize->add_setting( 'slider_image_overlay', array(
		'default' => true,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control('slider_image_overlay', array(
		'label'    => __('Enable slider image overlay', 'spicebox' ),
		'section'  => 'slider_section',
		'type' => 'checkbox',
	) );
		
		
	//Slider Background Overlay Color
	$wp_customize->add_setting( 'slider_overlay_section_color', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 'rgba(0,0,0,0.6)',
        ) );	
        
        $wp_customize->add_control(new Cloudpress_Customize_Alpha_Color_Control( $wp_customize,'slider_overlay_section_color', array(
           'label'      => __('Slider image overlay color','spicebox' ),
            'palette' => true,
            'section' => 'slider_section')
        ) );
	
		
	// Slider title
	$wp_customize->add_setting( 'home_slider_title',array(
	'default' => __('Nulla nec dolor sit amet lacus molestie', 'spicebox'),
	'capability'     => 'edit_theme_options',
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_slider_title',array(
	'label'   => __('Title','spicebox'),
	'section' => 'slider_section',
	'type' => 'text',
	));	
	
	//Slider discription
	$wp_customize->add_setting( 'home_slider_discription',array(
	'default' => __('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim <br> mei ludus efficiendi ei sea summo mazim ex.', 'spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_slider_discription',array(
	'label'   => __('Description','spicebox'),
	'section' => 'slider_section',
	'type' => 'textarea',
	));
	
	
	// Slider button text
	$wp_customize->add_setting( 'home_slider_btn_txt',array(
	'default' =>  __('Nec Sem', 'spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_slider_btn_txt',array(
	'label'   => __('Button Text','spicebox'),
	'section' => 'slider_section',
	'type' => 'text',
	));

	// Slider button link
	$wp_customize->add_setting( 'home_slider_btn_link',array(
	'default' => __('#','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_slider_btn_link',array(
	'label'   => __('Button Link','spicebox'),
	'section' => 'slider_section',
	'type' => 'text',
	));

	// Slider button target
	$wp_customize->add_setting(
	'home_slider_btn_target', 
		array(
		'default'        => false,
		'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_slider_btn_target', array(
		'label'   => __('Open link in new tab', 'spicebox'),
		'section' => 'slider_section',
		'type' => 'checkbox',
	));
	
	// Slider button2 text
	$wp_customize->add_setting( 'home_slider_btn_txt2',array(
	'default' => __('Cras Vitae', 'spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_slider_btn_txt2',array(
	'label'   => __('Button 2 Text','spicebox'),
	'section' => 'slider_section',
	'type' => 'text',
	));

	// Slider button link
	$wp_customize->add_setting( 'home_slider_btn_link2',array(
	'default' => __('#','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'home_slider_btn_link2',array(
	'label'   => __('Button 2 Link','spicebox'),
	'section' => 'slider_section',
	'type' => 'text',
	));
	
	// Slider button target
	$wp_customize->add_setting(
	'home_slider_btn_target2', 
		array(
		'default'        => false,
		'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
	));
	$wp_customize->add_control('home_slider_btn_target2', array(
		'label'   => __('Open link in new tab', 'spicebox'),
		'section' => 'slider_section',
		'type' => 'checkbox',
	));	
}

add_action( 'customize_register', 'spiceb_cloudpress_slider_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_cloudpress_register_home_slider_section_partials( $wp_customize )
{	
	$wp_customize->selective_refresh->add_partial( 'home_slider_image', array(
		'selector'            => '.slider .item',
		'settings'            => 'home_slider_image',
	
	) );
		
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'home_slider_title', array(
		'selector'            => '.main-slider .caption-content .title',
		'settings'            => 'home_slider_title',
		'render_callback'  => 'spiceb_cloudpress_slider_section_title_render_callback',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_discription', array(
		'selector'            => '.caption-content .description',
		'settings'            => 'home_slider_discription',
		'render_callback'  => 'spiceb_cloudpress_slider_section_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_btn_txt', array(
		'selector'            => '.caption-content .btn-small.btn-default',
		'settings'            => 'home_slider_btn_txt',
		'render_callback'  => 'spiceb_cloudpress_slider_btn_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'home_slider_btn_txt2', array(
		'selector'            => '.caption-content .btn-small.btn-light',
		'settings'            => 'home_slider_btn_txt2',
		'render_callback'  => 'spiceb_cloudpress_slider_btn2_render_callback',
	) );
}

add_action( 'customize_register', 'spiceb_cloudpress_register_home_slider_section_partials' );


function spiceb_cloudpress_slider_section_title_render_callback() {
	return get_theme_mod( 'home_slider_title' );
}

function spiceb_cloudpress_slider_section_discription_render_callback() {
	return get_theme_mod( 'home_slider_discription' );
}

function spiceb_cloudpress_slider_btn_render_callback() {
	return get_theme_mod( 'home_slider_btn_txt' );
}

function spiceb_cloudpress_slider_btn2_render_callback() {
	return get_theme_mod( 'home_slider_btn_txt2' );
}