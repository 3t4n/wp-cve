<?php if ( ! function_exists( 'icycp_agencyup_top_header_customize_register' ) ) :
function icycp_agencyup_top_header_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'header_contact' , array(
		'title' => __('Header Top Setting', 'agencyup'),
		'panel' => 'header_options',
		'priority' => 10,
   	) );

    //Enable and disable header contact info
    $wp_customize->add_setting(
    'header_contact_info_enable',
    array(
        'capability'     => 'edit_theme_options',
        'default' => '1',
        'sanitize_callback' => 'agencyup_header_sanitize_checkbox',
    )   
    );
    $wp_customize->add_control(
    'header_contact_info_enable',
    array(
        'label' => __('Hide / Show','agencyup'),
        'section' => 'header_contact',
        'type' => 'checkbox',
    )
    );

    $wp_customize->add_setting(
        'agencyup_head_info_icon_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-envelope',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'agencyup_head_info_icon_one', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'agencyup_head_info_icon_one_text', array(
        'capability' => 'edit_theme_options',
        'default' => 'dummyabc@gmail.com',
		'sanitize_callback' => 'agencyup_sanitize_text_content',
		'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'agencyup_head_info_icon_one_text', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	

    $wp_customize->add_setting(
        'agencyup_head_info_icon_two', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        //'transport'         => $selective_refresh,
        'default' => 'fa-phone',
    ) );
    $wp_customize->add_control( 'agencyup_head_info_icon_two', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'agencyup_head_info_icon_two_text', array(
        'capability' => 'edit_theme_options',
        'default' => '9876543210',
		'sanitize_callback' => 'agencyup_sanitize_text_content',
		'transport'         => $selective_refresh,
    ) );
    $wp_customize->add_control( 'agencyup_head_info_icon_two_text', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_contact',
        'type' => 'text',
    ) );
	
	//Enable and disable social icon
	$wp_customize->add_setting(
	'header_social_icon_enable'
    ,
    array(
		'capability'     => 'edit_theme_options',
        'default' => '1',
		'sanitize_callback' => 'agencyup_header_sanitize_checkbox',
    )	
	);
	$wp_customize->add_control(
    'header_social_icon_enable',
    array(
        'label' => __('Hide / Show Social Icons','agencyup'),
        'section' => 'header_contact',
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
        'section' => 'header_contact',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_header_fb_target',array(
	'sanitize_callback' => 'agencyup_header_sanitize_checkbox',
	'default' => 1,
	));

	$wp_customize->add_control(
    'agencyup_header_fb_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_contact',
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
        'section' => 'header_contact',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_header_twt_target',array(
	'sanitize_callback' => 'agencyup_header_sanitize_checkbox',
	'default' => 1,
	));

	$wp_customize->add_control(
    'agencyup_header_twt_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_contact',
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
        'section' => 'header_contact',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_twitter_lnkd_target',array(
	'default' => 1,
	'sanitize_callback' => 'agencyup_header_sanitize_checkbox',
	));

	$wp_customize->add_control(
    'agencyup_twitter_lnkd_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_contact',
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
        'section' => 'header_contact',
        'type' => 'url',
    )
	);

	$wp_customize->add_setting(
	'agencyup_insta_lnkd_target',array(
	'default' => 1,
	'sanitize_callback' => 'agencyup_header_sanitize_checkbox',
	));

	$wp_customize->add_control(
    'agencyup_insta_lnkd_target',
    array(
        'type' => 'checkbox',
        'label' => __('Open link in a new tab','agencyup'),
        'section' => 'header_contact',
    )
	);
}

add_action( 'customize_register', 'icycp_agencyup_top_header_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_agencyup_register_top_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'agencyup_head_info_icon_one', array(
		'selector'            => '.bs-head-detail .top-one i',
		'settings'            => 'agencyup_head_info_icon_one',
		'render_callback'  => 'icycp_agencyup_head_info_icon_one_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'agencyup_head_info_icon_one_text', array(
		'selector'            => '.bs-head-detail .top-one a',
		'settings'            => 'agencyup_head_info_icon_one_text',
		'render_callback'  => 'icycp_agencyup_head_info_icon_one_text_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'agencyup_head_info_icon_two', array(
		'selector'            => '.bs-head-detail .top-two i',
		'settings'            => 'agencyup_head_info_icon_two',
		'render_callback'  => 'icycp_agencyup_head_info_icon_two_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'agencyup_head_info_icon_two_text', array(
		'selector'            => '.bs-head-detail .top-two a',
		'settings'            => 'agencyup_head_info_icon_two_text',
		'render_callback'  => 'icycp_agencyup_head_info_icon_two_text_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'agencyup_header_fb_link', array(
		'selector'            => '.bs-head-detail .bs-social',
		'settings'            => 'agencyup_header_fb_link',
		'render_callback'  => 'icycp_agencyup_header_fb_link_render_callback',
	
	) );
	
}

add_action( 'customize_register', 'icycp_agencyup_register_top_section_partials' );


function icycp_agencyup_head_info_icon_one_render_callback() {
	return get_theme_mod( 'agencyup_head_info_icon_one' );
}

function icycp_agencyup_head_info_icon_one_text_render_callback() {
	return get_theme_mod( 'agencyup_head_info_icon_one_text' );
}

function icycp_agencyup_head_info_icon_two_render_callback() {
	return get_theme_mod( 'agencyup_head_info_icon_two' );
}

function icycp_agencyup_head_info_icon_two_text_render_callback() {
	return get_theme_mod( 'agencyup_head_info_icon_two_text' );
}

function icycp_agencyup_header_fb_link_render_callback() {
	return get_theme_mod( 'agencyup_header_fb_link' );
}


