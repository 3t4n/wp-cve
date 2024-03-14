<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_social_customizer {
	
	public static function wl_guardian_social_customizer( $wp_customize ) {
		       /* Social options */
    $wp_customize->add_section( 'social_section', array(
	    'title'      => __( "Social Options", WL_COMPANION_DOMAIN ),
	    'panel'      => 'guardian_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 48
    ) );

    $wp_customize->add_setting(
	    'header_social_media_in_enabled',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'guardian_sanitize_checkbox',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'header_social_media_in_enabled', array(
	    'label'    => __( 'Enable Social Media Icons in Header', WL_COMPANION_DOMAIN),
	    'type'     => 'checkbox',
	    'section'  => 'social_section',
	    'settings' => 'header_social_media_in_enabled'
    ) );

    $wp_customize->add_setting(
	    'footer_section_social_media_enbled',
	    array(
		    'default'           => 1,
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'guardian_sanitize_checkbox',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'footer_section_social_media_enbled', array(
	    'label'    => __( 'Enable Social Media Icons in Footer', WL_COMPANION_DOMAIN ),
	    'type'     => 'checkbox',
	    'section'  => 'social_section',
	    'settings' => 'footer_section_social_media_enbled'
    ) );

    $wp_customize->selective_refresh->add_partial( 'footer_section_social_media_enbled', array(
	    'selector' => '.footer_social_links',
    ));

    $wp_customize->add_setting(
	    'email_id',
	    array(
		    'default'           => '',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'sanitize_email',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'email_id', array(
	    'label'    => __( 'Email ID', WL_COMPANION_DOMAIN ),
	    'type'     => 'email',
	    'section'  => 'social_section',
	    'settings' => 'email_id'
    ) );

    $wp_customize->add_setting(
	    'phone_no',
	    array(
		    'default'           => '',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'guardian_sanitize_text',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'phone_no', array(
	    'label'    => __( 'Phone Number', WL_COMPANION_DOMAIN ),
	    'type'     => 'text',
	    'section'  => 'social_section',
	    'settings' => 'phone_no'
    ) );

    $wp_customize->add_setting(
	    'twitter_link',
	    array(
		    'default'           => '#',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'twitter_link', array(
	    'label'    => __( 'Twitter', WL_COMPANION_DOMAIN ),
	    'type'     => 'url',
	    'section'  => 'social_section',
	    'settings' => 'twitter_link'
    ) );

    $wp_customize->add_setting(
	    'fb_link',
	    array(
		    'default'           => '#',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'fb_link', array(
	    'label'    => __( 'Facebook', WL_COMPANION_DOMAIN ),
	    'type'     => 'url',
	    'section'  => 'social_section',
	    'settings' => 'fb_link'
    ) );

    $wp_customize->add_setting(
	    'linkedin_link',
	    array(
		    'default'           => '#',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw',
		    'capability'        => 'edit_theme_options'
	    )
    );
    $wp_customize->add_control( 'linkedin_link', array(
	    'label'    => __( 'LinkedIn', WL_COMPANION_DOMAIN ),
	    'type'     => 'url',
	    'section'  => 'social_section',
	    'settings' => 'linkedin_link'
    ) );

    $wp_customize->add_setting(
	    'youtube_link',
	    array(
		    'default'           => '#',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw',
		    'capability'        => 'edit_theme_options'
	    )
    );
    $wp_customize->add_control( 'youtube_link', array(
	    'label'    => __( 'Youtube', WL_COMPANION_DOMAIN ),
	    'type'     => 'url',
	    'section'  => 'social_section',
	    'settings' => 'youtube_link'
    ) );
    $wp_customize->add_setting(
	    'instagram',
	    array(
		    'default'           => '#',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw',
		    'capability'        => 'edit_theme_options'
	    )
    );
    $wp_customize->add_control( 'instagram', array(
	    'label'    => __( 'Instagram', WL_COMPANION_DOMAIN ),
	    'type'     => 'url',
	    'section'  => 'social_section',
	    'settings' => 'instagram'
    ) );
    /*extra icons added 2.7.1*/
    $wp_customize->add_setting(
	    'vk_link',
	    array(
		    'default'           => '#',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'esc_url_raw',
		    'capability'        => 'edit_theme_options'
	    )
    );
    $wp_customize->add_control( 'vk_link', array(
	    'label'    => __( 'RSS', WL_COMPANION_DOMAIN ),
	    'type'     => 'url',
	    'section'  => 'social_section',
	    'settings' => 'vk_link'
    ) );
    
	}
}

?>