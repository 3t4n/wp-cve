<?php if ( ! function_exists( 'icycp_cargoup_top_header_customize_register' ) ) :
function icycp_cargoup_top_header_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Header Section */

$wp_customize->add_section( 'cargo_top_header_contact' , array(
        'title' => __('Top Info Setting', 'cargoup'),
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
        'label' => __('Hide / Show Top Contact Info','industryup'),
        'section' => 'cargo_top_header_contact',
        'type' => 'checkbox',
    )
    );

    $wp_customize->add_setting(
        'cargoup_head_info_icon_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-clock',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'cargoup_head_info_icon_one', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'cargo_top_header_contact',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'cargoup_head_info_text_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'Open-Hours:10 am to 7pm',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_head_info_text_one', array(
        'label' => __('Text', 'icyclub'),
        'section' => 'cargo_top_header_contact',
        'type' => 'text',
    ) );
    

    $wp_customize->add_setting(
        'cargoup_head_info_icon_two', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
        'default' => 'fa-envelope-open',
    ) );
    $wp_customize->add_control( 'cargoup_head_info_icon_two', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'cargo_top_header_contact',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'cargoup_head_info_text_three', array(
        'capability' => 'edit_theme_options',
        'default' => 'info@yoursite.com',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'cargoup_head_info_text_three', array(
        'label' => __('Text', 'industryup'),
        'section' => 'cargo_top_header_contact',
        'type' => 'text',
    ) );
	
    //Social Icon

	 $wp_customize->add_section('top_header_social_icon', array(
        'title' => __('Social Icon','industryup'),
        'priority' => 20,
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
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
        'section' => 'top_header_social_icon',
    )
	);

    //Contact Info settings
    $wp_customize->add_section( 'cargo_header_contact_info' , array(
        'title' => __('Contact Info Setting', 'cargoup'),
        'panel' => 'header_options',
        'priority' => 25,
    ) );
    
    //Enable and disable header contact info
    $wp_customize->add_setting(
    'header_right_contact_info_enable',
    array(
        'capability'     => 'edit_theme_options',
        'default' => '1',
        'sanitize_callback' => 'icycp_industryup_switch_sanitization',
    )   
    );
    $wp_customize->add_control(
    'header_right_contact_info_enable',
    array(
        'label' => __('Hide / Show Contact Info','industryup'),
        'section' => 'cargo_header_contact_info',
        'type' => 'checkbox',
    )
    );

    //Info One
    $wp_customize->add_setting(
        'cargoup_contact_icon_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-phone',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'cargoup_contact_icon_one', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'cargoup_contact_text_two', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_contact_text_two', array(
        'label' => __('Text one', 'icyclub'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );


    $wp_customize->add_setting(
        'cargoup_contact_text_three', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_contact_text_three', array(
        'label' => __('Text two', 'icyclub'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );
    

    //Info Two
    $wp_customize->add_setting(
        'cargoup_contact_icon_four', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-phone',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'cargoup_contact_icon_four', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'cargoup_contact_text_five', array(
        'capability' => 'edit_theme_options',
        'default' => '7:30 AM - 7:30 PM',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_contact_text_five', array(
        'label' => __('Text one', 'icyclub'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );


    $wp_customize->add_setting(
        'cargoup_contact_text_six', array(
        'capability' => 'edit_theme_options',
        'default' => 'Monday to Saturday',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_contact_text_six', array(
        'label' => __('Text two', 'icyclub'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );

    //Info One
    $wp_customize->add_setting(
        'cargoup_contact_icon_seven', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-phone',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'cargoup_contact_icon_seven', array(
        'label' => __('Icon', 'industryup'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'cargoup_contact_text_eight', array(
        'capability' => 'edit_theme_options',
        'default' => '7:30 AM - 7:30 PM',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_contact_text_eight', array(
        'label' => __('Text one', 'icyclub'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );


    $wp_customize->add_setting(
        'cargoup_contact_text_nine', array(
        'capability' => 'edit_theme_options',
        'default' => 'Monday to Saturday',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );



    $wp_customize->add_control( 'cargoup_contact_text_nine', array(
        'label' => __('Text two', 'icyclub'),
        'section' => 'cargo_header_contact_info',
        'type' => 'text',
    ) );
	
}

add_action( 'customize_register', 'icycp_cargoup_top_header_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_cargoup_register_top_section_partials( $wp_customize ){

	
	
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

add_action( 'customize_register', 'icycp_cargoup_register_top_section_partials' );


function icycp_cargoup_head_info_icon_one_render_callback() {
	return get_theme_mod( 'industryup_head_info_icon_one' );
}


function icycp_cargoup_header_fb_link_render_callback() {
	return get_theme_mod( 'industryup_header_fb_link' );
}


function icycp_cargoup_head_text_render_callback() {
	return get_theme_mod( 'industryup_head_text' );
}