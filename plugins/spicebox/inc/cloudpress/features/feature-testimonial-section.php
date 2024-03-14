<?php
if ( ! function_exists( 'spiceb_cloudpress_agency_testimonial_customize_register' ) ) :
function spiceb_cloudpress_agency_testimonial_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	/* Testimonial Section */
	$wp_customize->add_section( 'testimonial_section' , array(
			'title'      => __('Testimonial settings', 'spicebox'),
			'panel'  => 'section_settings',
			'priority'   =>4,
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
	$wp_customize->add_setting( 'testimonial_callout_background', array('default' => SPICEB_PLUGIN_URL .'inc/cloudpress/images/testimonial/bg03.jpg',
	  'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'testimonial_callout_background', array(
	  'label'    => __( 'Background Image', 'cloudpress' ),
	  'section'  => 'testimonial_section',
	  'settings' => 'testimonial_callout_background',
	) ) );

	//Testimonial Background Color
	$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(1, 7, 12, 0.65)',
	    ) );



    $wp_customize->add_control(new Cloudpress_Customize_Alpha_Color_Control( $wp_customize,'testimonial_overlay_section_color', array(
           'label'      => __('Background Overlay Color','spicebox'),
            'palette' => true,
            'section' => 'testimonial_section')
    ) );

	// testimonial section title
	$wp_customize->add_setting( 'home_testimonial_section_title',array(
	'capability'     => 'edit_theme_options',
	'default' => __('Proin Egestas','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
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
	'default'=> __('Nam Viverra Iaculis Finibus','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
	'transport'         => $selective_refresh,

	));
	$wp_customize->add_control( 'home_testimonial_section_discription',array(
	'label'   => __('Description','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'textarea',
	));

	//testimonial one image
	$wp_customize->add_setting( 'home_testimonial_thumb',
    		array(
    			'default' => SPICEB_PLUGIN_URL .'inc/cloudpress/images/testimonial/user-1.jpg',
				'sanitize_callback' => 'esc_url_raw',

			));

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
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
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
	'default' => __('Cras Vitae','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
	

	));
	$wp_customize->add_control( 'home_testimonial_title',array(
	'label'   => __('Title','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'text',
	));

	// testimonial link
	$wp_customize->add_setting( 'home_testimonial_link',array(
	'default' => __('#','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
	
	));
	$wp_customize->add_control( 'home_testimonial_link',array(
	'label'   => __('Link','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'text',
	));

	// testimonial open in new tab
	$wp_customize->add_setting( 'home_testimonial_open_tab',array(
	'default' => false,
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
	
	));
	$wp_customize->add_control( 'home_testimonial_open_tab',array(
	'label'   => __('Open link in new tab','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'checkbox',
	));
	$wp_customize->add_setting( 'home_testimonial_designation',array(
	'capability'     => 'edit_theme_options',
	'default' => __('Eu Suscipit','spicebox'),
	'sanitize_callback' => 'spiceb_cloudpress_agency_home_page_sanitize_text',
	

	));
	$wp_customize->add_control( 'home_testimonial_designation',array(
	'label'   => __('Designation','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'text',
	));
}

add_action( 'customize_register', 'spiceb_cloudpress_agency_testimonial_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_cloudpress_agency_register_home_testimonial_section_partials( $wp_customize ){

	//Testimonial
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_section_title', array(
		'selector'            => '.testimonial-wrapper .section-header .section-title',
		'settings'            => 'home_testimonial_section_title',
		'render_callback'  => 'spiceb_cloudpress_agency_testimonial_section_title_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_section_discription', array(
		'selector'            => '.testimonial-wrapper .section-header .section-subtitle',
		'settings'            => 'home_testimonial_section_discription',
		'render_callback'  => 'spiceb_cloudpress_agency_testimonial_section_discription_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_desc', array(
		'selector'            => '.testimonial .testmonial-block .description p',
		'settings'            => 'home_testimonial_desc',
		'render_callback'  => 'spiceb_cloudpress_agency_testimonial_desc_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_title', array(
		'selector'            => '.testimonial .testmonial-block .name',
		'settings'            => 'home_testimonial_title',
		'render_callback'  => 'spiceb_cloudpress_agency_testimonial_title_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_designation', array(
		'selector'            => '.testimonial .testmonial-block .name .designation',
		'settings'            => 'home_testimonial_designation',
		'render_callback'  => 'spiceb_cloudpress_agency_testimonial_designation_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_thumb', array(
		'selector'            => '.testimonial .testmonial-block .avatar ',
		'settings'            => 'home_testimonial_thumb',

	) );
}

add_action( 'customize_register', 'spiceb_cloudpress_agency_register_home_testimonial_section_partials' );

function spiceb_cloudpress_agency_testimonial_section_title_render_callback() {
	return get_theme_mod( 'home_testimonial_section_title' );
}

function spiceb_cloudpress_agency_testimonial_section_discription_render_callback() {
	return get_theme_mod( 'home_testimonial_section_discription' );
}

function spiceb_cloudpress_agency_testimonial_desc_render_callback() {
	return get_theme_mod( 'home_testimonial_desc' );
}

function spiceb_cloudpress_agency_testimonial_title_render_callback() {
	return get_theme_mod( 'home_testimonial_title' );
}

function spiceb_cloudpress_agency_testimonial_designation_render_callback() {
	return get_theme_mod( 'home_testimonial_designation' );
}

 function spiceb_cloudpress_agency_home_page_sanitize_text( $input ) {
   	return wp_kses_post( force_balance_tags( $input ) );
 }
