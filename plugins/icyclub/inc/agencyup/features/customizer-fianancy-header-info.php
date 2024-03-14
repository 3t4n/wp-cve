<?php if ( ! function_exists( 'icycp_agencyup_header_info_customize_register' ) ) :
function icycp_agencyup_header_info_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'header_info_contact' , array(
		'title' => __('Header Info Setting', 'agencyup'),
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
        'section' => 'header_info_contact',
        'type' => 'checkbox',
    )
    );

    $wp_customize->add_setting(
        'financey_head_info_icon_one', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-map-marker',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_one', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'financey_head_info_icon_one_text', array(
        'capability' => 'edit_theme_options',
        'default' => '1240 Park Avenue,',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_one_text', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
        'financey_head_info_icon_one_two_text', array(
        'capability' => 'edit_theme_options',
        'default' => 'NYC, USA 256323',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_one_two_text', array(
        'label' => __('Text Two', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );
	

    //Info TWO

    $wp_customize->add_setting(
        'financey_head_info_icon_two', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-phone-alt',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_two', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'financey_head_info_icon_two_text', array(
        'capability' => 'edit_theme_options',
        'default' => 'Free Consult',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_two_text', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
        'financey_head_info_icon_two_two_text', array(
        'capability' => 'edit_theme_options',
        'default' => '+ (007) 548 58 5400',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_two_two_text', array(
        'label' => __('Text Two', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );
    

    //Info Three

    $wp_customize->add_setting(
        'financey_head_info_icon_three', array(
        'capability' => 'edit_theme_options',
        'default' => 'fa-clock',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_three', array(
        'label' => __('Icon', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );
    
    $wp_customize->add_setting(
        'financey_head_info_icon_three_text', array(
        'capability' => 'edit_theme_options',
        'default' => 'Mon - Sat :',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_three_text', array(
        'label' => __('Text', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );

    $wp_customize->add_setting(
        'financey_head_info_icon_three_two_text', array(
        'capability' => 'edit_theme_options',
        'default' => '10:00AM - 7:00PM',
    ) );
    $wp_customize->add_control( 'financey_head_info_icon_three_two_text', array(
        'label' => __('Text Two', 'agencyup'),
        'section' => 'header_info_contact',
        'type' => 'text',
    ) );

}

add_action( 'customize_register', 'icycp_agencyup_header_info_customize_register' );
endif;
