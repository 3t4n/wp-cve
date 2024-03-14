<?php if ( ! function_exists( 'icycp_agencyup_top_header_customize_register' ) ) :
function icycp_agencyup_top_header_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'header_info_text' , array(
        'title' => __('Header Info Text Setting', 'consultco'),
        'panel' => 'header_options',
        'priority' => 9,
    ) );

   	//Enable and disable header text
    $wp_customize->add_setting(
    'consultco_head_text_enable',
    array(
        'capability'     => 'edit_theme_options',
        'default' => '1',
        'sanitize_callback' => 'icycp_switch_sanitization',
    )   
    );
    $wp_customize->add_control(
    'consultco_head_text_enable',
    array(
        'label' => __('Hide / Show Top Text','agencyup'),
        'section' => 'header_info_text',
        'type' => 'checkbox',
    )
    );

   	$wp_customize->add_setting(
		'consultco_head_text', array(
        'capability' => 'edit_theme_options',
        'default' => 'Welcome to our consulting company ConsultCorp',
		'sanitize_callback' => 'consultco_sanitize_text_content',
		//'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'consultco_head_text', array(
        'label' => __('Top Text', 'agencyup'),
        'section' => 'header_info_text',
        'type' => 'text',
    ) );


    //Social Icon

	 $wp_customize->add_section('header_social_icon', array(
        'title' => __('Social Icon','consultco'),
        'priority' => 10,
        'panel' => 'header_options',
    ) );


    //Enable and disable social icon
	$wp_customize->add_setting(
	'header_social_icon_enable'
    ,
    array(
		'capability'     => 'edit_theme_options',
        'default' => '1',
		'sanitize_callback' => 'consultco_header_sanitize_checkbox',
    )	
	);
	$wp_customize->add_control(
    'header_social_icon_enable',
    array(
        'label' => __('Hide / Show Social Icons','agencyup'),
        'section' => 'header_social_icon',
        'type' => 'checkbox',
    )
	);


	// Soical facebook link
	$wp_customize->add_setting(
    'agencyup_header_fb_link',
    array(
		'sanitize_callback' => 'esc_url_raw',
		'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'agencyup_header_fb_link',
    array(
        'label' => __('Facebook URL','agencyup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_header_fb_target',array(
	'sanitize_callback' => 'consultco_header_sanitize_checkbox',
	'default' => 1,
	));

	$wp_customize->add_control(
    'agencyup_header_fb_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_social_icon',
    )
	);
	
	
	//Social Twitter link
	$wp_customize->add_setting(
    'agencyup_header_twt_link',
    array(
		'sanitize_callback' => 'esc_url_raw',
		'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'agencyup_header_twt_link',
    array(
        'label' => __('Twitter URL','agencyup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_header_twt_target',array(
	'sanitize_callback' => 'consultco_header_sanitize_checkbox',
	'default' => 1,
	));

	$wp_customize->add_control(
    'agencyup_header_twt_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_social_icon',
    )
	);
	
	//Soical Linkedin link
	$wp_customize->add_setting(
    'agencyup_header_lnkd_link',
    array(
		'sanitize_callback' => 'esc_url_raw',
		'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'agencyup_header_lnkd_link',
    array(
        'label' => __('Linkedin URL','agencyup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_twitter_lnkd_target',array(
	'default' => 1,
	'sanitize_callback' => 'consultco_header_sanitize_checkbox',
	));

	$wp_customize->add_control(
    'agencyup_twitter_lnkd_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_social_icon',
    )
	);
	
	
	//Soical Instagram link
	$wp_customize->add_setting(
    'agencyup_header_insta_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
        'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'agencyup_header_insta_link',
    array(
        'label' => __('Instagram URL','agencyup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_insta_lnkd_target',array(
	'default' => 1,
	'sanitize_callback' => 'consultco_header_sanitize_checkbox',
	));

	$wp_customize->add_control(
    'agencyup_insta_lnkd_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_social_icon',
    )
	);
	

	$wp_customize->add_section( 'header_contact' , array(
        'title' => __('Contact Setting', 'consultco'),
        'panel' => 'header_options',
        'priority' => 20,
    ) );
    
    //Enable and disable header contact info
    $wp_customize->add_setting(
    'header_contact_info_enable',
    array(
        'capability'     => 'edit_theme_options',
        'default' => '1',
        'sanitize_callback' => 'consultco_header_sanitize_checkbox',
    )   
    );
    $wp_customize->add_control(
    'header_contact_info_enable',
    array(
        'label' => __('Show / Hide Contact Info','agencyup'),
        'section' => 'header_contact',
        'type' => 'checkbox',
    )
    );

    $wp_customize->add_setting(
        'agencyup_head_info_icon_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-phone',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'agencyup_head_info_icon_one', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'agencyup_head_info_text_one', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
		'sanitize_callback' => 'consultco_sanitize_text_content',
    ) );

    $wp_customize->add_control( 'agencyup_head_info_text_one', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
		'agencyup_head_info_text_two', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
		'sanitize_callback' => 'consultco_sanitize_text_content',
		//'transport'         => $selective_refresh,
    ) );


    $wp_customize->add_control( 'agencyup_head_info_text_two', array(
        'label' => __('Text', 'icyclub'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	

    $wp_customize->add_setting(
        'agencyup_head_info_icon_two', array(
        'sanitize_callback' => 'sanitize_text_field',
       // 'transport'         => $selective_refresh,
        'default' => 'fa-clock',
    ) );
    $wp_customize->add_control( 'agencyup_head_info_icon_two', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'agencyup_head_info_text_three', array(
        'capability' => 'edit_theme_options',
        'default' => '7:30 AM - 7:30 PM',
		'sanitize_callback' => 'consultco_sanitize_text_content',
		//'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'agencyup_head_info_text_three', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
		'agencyup_head_info_text_four', array(
        'capability' => 'edit_theme_options',
        'default' => 'Monday to Saturday',
		'sanitize_callback' => 'consultco_sanitize_text_content',
		//'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'agencyup_head_info_text_four', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
	

	
}

add_action( 'customize_register', 'icycp_agencyup_top_header_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_agencyup_register_top_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'consultco_head_text', array(
		'selector'            => '.header_widgets .top-text',
		'settings'            => 'consultco_head_text',
		'render_callback'  => 'icycp_consultco_head_text_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'agencyup_head_info_icon_one', array(
		'selector'            => '.top-one .mr-3 i',
		'settings'            => 'agencyup_head_info_icon_one',
		'render_callback'  => 'icycp_agencyup_head_info_icon_one_render_callback',
	
	) );


	
	$wp_customize->selective_refresh->add_partial( 'agencyup_header_fb_link', array(
		'selector'            => '.header_widgets .bs-social',
		'settings'            => 'agencyup_header_fb_link',
		'render_callback'  => 'icycp_agencyup_header_fb_link_render_callback',
	
	) );

    $wp_customize->add_setting(
        'header_img_bg_color', array( 'sanitize_callback' => 'sanitize_text_field',
        'default' =>' ',
    ) );

    $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,
        'header_img_bg_color', array(
        'label'      => __('Overlay Color', 'consultco' ),
        'palette' => true,
        'section' => 'header_image')
    ) );

	
}

add_action( 'customize_register', 'icycp_agencyup_register_top_section_partials' );


function icycp_agencyup_head_info_icon_one_render_callback() {
	return get_theme_mod( 'agencyup_head_info_icon_one' );
}


function icycp_agencyup_header_fb_link_render_callback() {
	return get_theme_mod( 'agencyup_header_fb_link' );
}


function icycp_consultco_head_text_render_callback() {
	return get_theme_mod( 'consultco_head_text' );
}

