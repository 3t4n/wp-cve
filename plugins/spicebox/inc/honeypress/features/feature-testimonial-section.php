<?php
if ( ! function_exists( 'spiceb_honeypress_testimonial_customize_register' ) ) :
function spiceb_honeypress_testimonial_customize_register($wp_customize){
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

	//Testimonial Background Color
	$theme = wp_get_theme();
	if( $theme->name=='HoneyPress'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0, 76, 236, 0.9)',
	    ) );
    }
    elseif( $theme->name=='Radix Multipurpose'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(130, 180, 64, 0.9)',
	    ) );
    }
    elseif( $theme->name=='Bizhunt'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(105,116,234,0.9)',
	    ) );
    }
    elseif( $theme->name=='Tromas'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(206, 27, 40,0.9)',
	    ) );
    }
    elseif( $theme->name=='HoneyWaves'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0, 0, 0, 0.9)',
	    ) );
    }
    elseif( $theme->name=='HoneyBee'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(248, 145, 8, 0.9)',
	    ) );
    }
		elseif( $theme->name=='Honeypress Dark'){
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(249, 0, 77, 0.9)',
	    ) );
    }
    else{
		$wp_customize->add_setting( 'testimonial_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0, 76, 236, 0.9)',
	    ) );
    }

    $wp_customize->add_control(new honeypress_Customize_Alpha_Color_Control( $wp_customize,'testimonial_overlay_section_color', array(
           'label'      => __('Background Color','spicebox'),
            'palette' => true,
            'section' => 'testimonial_section')
    ) );

	// testimonial section title
	$wp_customize->add_setting( 'home_testimonial_section_title',array(
	'capability'     => 'edit_theme_options',
	'default' => __('Proin Egestas','spicebox'),
	'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
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
	'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));
	$wp_customize->add_control( 'home_testimonial_section_discription',array(
	'label'   => __('Description','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'textarea',
	));

	//testimonial one image
	$theme = wp_get_theme();
    if ($theme->name == 'HoneyBee') {
        $wp_customize->add_setting( 'home_testimonial_thumb',
    		array(
    			'default' => SPICEB_PLUGIN_URL .'inc/honeypress/images/testimonial/user1.jpg',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => $selective_refresh,
			));
    } else{
    	$wp_customize->add_setting( 'home_testimonial_thumb',
    		array(
    			'default' => SPICEB_PLUGIN_URL .'inc/honeypress/images/testimonial/testi1.jpg',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => $selective_refresh,
			));
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
	'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
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
	'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));
	$wp_customize->add_control( 'home_testimonial_title',array(
	'label'   => __('Title','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'text',
	));

	$wp_customize->add_setting( 'home_testimonial_designation',array(
	'capability'     => 'edit_theme_options',
	'default' => __('Eu Suscipit','spicebox'),
	'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
	'transport'         => $selective_refresh,
	));
	$wp_customize->add_control( 'home_testimonial_designation',array(
	'label'   => __('Designation','spicebox'),
	'section' => 'testimonial_section',
	'type' => 'text',
	));
}

add_action( 'customize_register', 'spiceb_honeypress_testimonial_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_honeypress_register_home_testimonial_section_partials( $wp_customize ){

	//Testimonial
	$wp_customize->selective_refresh->add_partial( 'home_testimonial_section_title', array(
		'selector'            => '.section-module.testimonial .section-subtitle',
		'settings'            => 'home_testimonial_section_title',
		'render_callback'  => 'spiceb_honeypress_testimonial_section_title_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_section_discription', array(
		'selector'            => '.section-module.testimonial .section-title',
		'settings'            => 'home_testimonial_section_discription',
		'render_callback'  => 'spiceb_honeypress_testimonial_section_discription_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_desc', array(
		'selector'            => '.testmonial-block.text-center .text-white',
		'settings'            => 'home_testimonial_desc',
		'render_callback'  => 'spiceb_honeypress_testimonial_desc_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_title', array(
		'selector'            => '.section-module.testimonial .name',
		'settings'            => 'home_testimonial_title',
		'render_callback'  => 'spiceb_honeypress_testimonial_title_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_designation', array(
		'selector'            => '.section-module.testimonial .designation',
		'settings'            => 'home_testimonial_designation',
		'render_callback'  => 'spiceb_honeypress_testimonial_designation_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_testimonial_thumb', array(
		'selector'            => '.section.testimonial-section .avatar ',
		'settings'            => 'home_testimonial_thumb',

	) );
}

add_action( 'customize_register', 'spiceb_honeypress_register_home_testimonial_section_partials' );

function spiceb_honeypress_testimonial_section_title_render_callback() {
	return get_theme_mod( 'home_testimonial_section_title' );
}

function spiceb_honeypress_testimonial_section_discription_render_callback() {
	return get_theme_mod( 'home_testimonial_section_discription' );
}

function spiceb_honeypress_testimonial_desc_render_callback() {
	return get_theme_mod( 'home_testimonial_desc' );
}

function spiceb_honeypress_testimonial_title_render_callback() {
	return get_theme_mod( 'home_testimonial_title' );
}

function spiceb_honeypress_testimonial_designation_render_callback() {
	return get_theme_mod( 'home_testimonial_designation' );
}
