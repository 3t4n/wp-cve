<?php if ( ! function_exists( 'icycp_industryup_top_header_customize_register' ) ) :
function icycp_industryup_top_header_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Header Section */

$wp_customize->add_section( 'header_contact' , array(
        'title' => __('Contact Setting', 'industryup'),
        'panel' => 'header_options',
        'priority' => 20,
    ) );
    
    //Enable and disable header contact info
    $wp_customize->add_setting(
    'header_contact_info_enable',
    array(
        'capability'     => 'edit_theme_options',
        'default' => '1',
        'sanitize_callback' => 'icycp_industryup_switch_sanitization',
    )   
    );
    $wp_customize->add_control(
    'header_contact_info_enable',
    array(
        'label' => __('Hide / Show Contact Info','industryup'),
        'section' => 'header_contact',
        'type' => 'checkbox',
    )
    );

    $wp_customize->add_setting(
        'industryup_head_info_icon_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-phone',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'industryup_head_info_icon_one', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'industryup_head_info_text_one', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );

    $wp_customize->add_control( 'industryup_head_info_text_one', array(
        'label' => __('Text', 'industryup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
        'industryup_head_info_text_two', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );


    $wp_customize->add_control( 'industryup_head_info_text_two', array(
        'label' => __('Text', 'icyclub'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
    

    $wp_customize->add_setting(
        'industryup_head_info_icon_two', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
        'default' => 'fa-clock',
    ) );
    $wp_customize->add_control( 'industryup_head_info_icon_two', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'industryup_head_info_text_three', array(
        'capability' => 'edit_theme_options',
        'default' => '7:30 AM - 7:30 PM',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'industryup_head_info_text_three', array(
        'label' => __('Text', 'industryup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
        'industryup_head_info_text_four', array(
        'capability' => 'edit_theme_options',
        'default' => 'Monday to Saturday',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'industryup_head_info_text_four', array(
        'label' => __('Text', 'industryup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
    //Social Icon

	 $wp_customize->add_section('header_social_icon', array(
        'title' => __('Social Icon','industryup'),
        'priority' => 50,
        'panel' => 'header_options',
    ) );


    //Enable and disable social icon
	$wp_customize->add_setting(
	'header_social_icon_enable'
    ,
    array(
		'capability'     => 'edit_theme_options',
        'default' => '1',
		'sanitize_callback' => 'icycp_industryup_switch_sanitization',
    )	
	);
	$wp_customize->add_control(
    'header_social_icon_enable',
    array(
        'label' => __('Hide / Show Social Icons','industryup'),
        'section' => 'header_social_icon',
        'type' => 'checkbox',
    )
	);


	// Soical facebook link
	$wp_customize->add_setting(
    'industryup_header_fb_link',
    array(
		'sanitize_callback' => 'esc_url_raw',
		'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'industryup_header_fb_link',
    array(
        'label' => __('Facebook URL','industryup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'industryup_header_fb_target',array(
	'sanitize_callback' => 'icycp_industryup_switch_sanitization',
	'default' => 1,
	));

	$wp_customize->add_control(
    'industryup_header_fb_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','industryup'),
        'section' => 'header_social_icon',
    )
	);
	
	
	//Social Twitter link
	$wp_customize->add_setting(
    'industryup_header_twt_link',
    array(
		'sanitize_callback' => 'esc_url_raw',
		'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'industryup_header_twt_link',
    array(
        'label' => __('Twitter URL','industryup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'industryup_header_twt_target',array(
	'sanitize_callback' => 'icycp_industryup_switch_sanitization',
	'default' => 1,
	));

	$wp_customize->add_control(
    'industryup_header_twt_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','industryup'),
        'section' => 'header_social_icon',
    )
	);
	
	//Soical Linkedin link
	$wp_customize->add_setting(
    'industryup_header_lnkd_link',
    array(
		'sanitize_callback' => 'esc_url_raw',
		'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'industryup_header_lnkd_link',
    array(
        'label' => __('Linkedin URL','industryup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'industryup_twitter_lnkd_target',array(
	'default' => 1,
	'sanitize_callback' => 'icycp_industryup_switch_sanitization',
	));

	$wp_customize->add_control(
    'industryup_twitter_lnkd_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','industryup'),
        'section' => 'header_social_icon',
    )
	);
	
	
	//Soical Instagram link
	$wp_customize->add_setting(
    'industryup_header_insta_link',
    array(
        'sanitize_callback' => 'esc_url_raw',
        'default' => '#',
    )
	
	);
	$wp_customize->add_control(
    'industryup_header_insta_link',
    array(
        'label' => __('Instagram URL','industryup'),
        'section' => 'header_social_icon',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'industryup_insta_lnkd_target',array(
	'default' => 1,
	'sanitize_callback' => 'icycp_industryup_switch_sanitization',
	));

	$wp_customize->add_control(
    'industryup_insta_lnkd_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','industryup'),
        'section' => 'header_social_icon',
    )
	);

    $wp_customize->add_setting(
        'header_img_bg_color', array( 'sanitize_callback' => 'sanitize_text_field',
        'default' =>'#ffffffb3',
    ) );
    
    $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,
        'header_img_bg_color', array(
        'label'      => __('Overlay Color', 'industryup' ),
        'palette' => true,
        'section' => 'header_image')
    ) );

	
}

add_action( 'customize_register', 'icycp_industryup_top_header_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_industryup_register_top_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'industryup_head_text', array(
		'selector'            => '.header_widgets .top-text',
		'settings'            => 'industryup_head_text',
		'render_callback'  => 'icycp_industryup_head_text_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'industryup_head_info_icon_one', array(
		'selector'            => '.top-one .mr-3 i',
		'settings'            => 'industryup_head_info_icon_one',
		'render_callback'  => 'icycp_industryup_head_info_icon_one_render_callback',
	
	) );


	
	$wp_customize->selective_refresh->add_partial( 'industryup_header_fb_link', array(
		'selector'            => '.header_widgets .bs-social',
		'settings'            => 'industryup_header_fb_link',
		'render_callback'  => 'icycp_industryup_header_fb_link_render_callback',
	
	) );
	
}

add_action( 'customize_register', 'icycp_industryup_register_top_section_partials' );


function icycp_industryup_head_info_icon_one_render_callback() {
	return get_theme_mod( 'industryup_head_info_icon_one' );
}


function icycp_industryup_header_fb_link_render_callback() {
	return get_theme_mod( 'industryup_header_fb_link' );
}


function icycp_industryup_head_text_render_callback() {
	return get_theme_mod( 'industryup_head_text' );
}

function industryup_sanitize_text_content( $input, $setting ) {

        return ( stripslashes( wp_filter_post_kses( addslashes( $input ) ) ) );

    }