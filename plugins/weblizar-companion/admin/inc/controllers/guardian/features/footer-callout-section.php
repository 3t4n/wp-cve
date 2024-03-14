<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_callout_customizer {
	
	public static function wl_guardian_footer_callout_customizer( $wp_customize ) {
		    /* Footer callout */
		    $wp_customize->add_section( 'callout_section', array(
			    'title'      => __( "Footer Call-Out Options", WL_COMPANION_DOMAIN ),
			    'panel'      => 'guardian_theme_option',
			    'capability' => 'edit_theme_options',
			    'priority'   => 49
		    ) );

		    $wp_customize->add_setting(
			    'fc_home',
			    array(
				    'default'           => 1,
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'guardian_sanitize_checkbox',
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
				    'sanitize_callback' => 'guardian_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_title', array(
			    'label'    => __( 'Footer callout Title', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_title'
		    ) );

		    $wp_customize->selective_refresh->add_partial( 'fc_title', array(
			    'selector' => '.guardian_callout_area p',
		    ));

		    $wp_customize->add_setting(
			    'fc_btn_txt',
			    array(
				    'default'           => __( 'More Features', WL_COMPANION_DOMAIN ),
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'guardian_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_btn_txt', array(
			    'label'    => __( 'Footer callout Button Text', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_btn_txt'
		    ) );

		    $wp_customize->selective_refresh->add_partial( 'fc_btn_txt', array(
			    'selector' => '.guardian_callout_area a',
		    ));

		    $wp_customize->add_setting(
			    'fc_btn_link',
			    array(
				    'default'           => '#',
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'guardian_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_btn_link', array(
			    'label'    => __( 'Footer callout Button Link', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_btn_link'
		    ) );
			
			
			//link setting
			$wp_customize->add_setting(
				'guardian_link_setting',
				array(
					'type'              => 'theme_mod',
					'default'           => 0,
					'sanitize_callback' => 'guardian_sanitize_checkbox',
					'capability'        => 'edit_theme_options',
				)
			);
			$wp_customize->add_control( 'guardian_link_setting', array(
				'label'    => __( 'Open Footer Callout link in New Tab',  WL_COMPANION_DOMAIN ),
				'type'     => 'checkbox',
				'section'  => 'callout_section',
				'settings' => 'guardian_link_setting',
			) );
			
		    $wp_customize->add_setting(
			    'fc_icon',
			    array(
				    'default'           => 'fa fa-thumbs-up',
				    'type'              => 'theme_mod',
				    'capability'        => 'edit_theme_options',
				    'sanitize_callback' => 'guardian_sanitize_text',
			    )
		    );
		    $wp_customize->add_control( 'fc_icon', array(
			    'label'    => __( 'Footer callout Icon', WL_COMPANION_DOMAIN ),
			    'type'     => 'text',
			    'section'  => 'callout_section',
			    'settings' => 'fc_icon'
		    ) );
		
	}
}
?>