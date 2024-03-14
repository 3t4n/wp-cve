<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_callout_customizer {
	
	public static function wl_creative_footer_callout_customizer( $wp_customize ) {
		    /* Footer callout */
		    $wp_customize->add_section( 'callout_section', array(
			    'title'      => __( "Footer Call-Out Options", WL_COMPANION_DOMAIN ),
			    'panel'      => 'creative_theme_option',
			    'capability' => 'edit_theme_options',
			    'priority'   => 49
		    ) );

		    $wp_customize->add_setting(
			    'fc_home',
			    array(
				    'default'           => 1,
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'creative_sanitize_checkbox',
			    )
		    );
		    $wp_customize->add_control( 'fc_home', array(
			    'label'    => __( 'Enable Footer callout on Home', WL_COMPANION_DOMAIN ),
			    'type'     => 'checkbox',
			    'section'  => 'callout_section',
			    'settings' => 'fc_home'
		    ) );
		 
		    $wp_customize->add_setting(
			    'fc_title',
			    array(
				    'default'           => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. ', WL_COMPANION_DOMAIN),
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'creative_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_title', array(
			    'label'    => __( 'Footer callout Title', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_title'
		    ) );

		    $wp_customize->selective_refresh->add_partial( 'fc_title', array(
			    'selector' => '.creative_footer_call_title',
		    ));

		    $wp_customize->add_setting(
			    'fc_btn_txt',
			    array(
				    'default'           => __( 'Features', WL_COMPANION_DOMAIN ),
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'creative_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_btn_txt', array(
			    'label'    => __( 'Footer callout Button Text', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_btn_txt'
		    ) );

		    $wp_customize->selective_refresh->add_partial( 'fc_btn_txt', array(
			    'selector' => '.creative_footer_call_text',
		    ));

		    $wp_customize->add_setting(
			    'fc_btn_link',
			    array(
				    'default'           => '#',
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'creative_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_btn_link', array(
			    'label'    => __( 'Footer callout Button Link', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_btn_link'
		    ) );
	}
}
?>