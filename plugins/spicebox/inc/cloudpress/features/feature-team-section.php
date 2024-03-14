<?php
if ( ! function_exists( 'spiceb_cloudpress_team_customize_register' ) ) :
    function spiceb_cloudpress_team_customize_register($wp_customize) {

        $selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

        $wp_customize->add_section('cloudpress_team_section',array(
        'title'     => esc_html__('Team Settings','spicebox'),
        'panel'     => 'section_settings',
        'priority'  => 6,
        ));

        $wp_customize->add_setting( 'team_section_enable' , array( 'default' => 'on' ) );
        $wp_customize->add_control(	'team_section_enable' , array(
    				'label'     => esc_html__( 'Enable Home Team section', 'spicebox' ),
    				'section'   => 'cloudpress_team_section',
    				'type'      => 'radio',
    				'choices'   => array(
                'on'    =>    esc_html__('ON', 'spicebox'),
                'off'   =>    esc_html__('OFF', 'spicebox')
    				)
    		));

        // Team section title
        $wp_customize->add_setting( 'home_team_section_title',array(
            'capability'        => 'edit_theme_options',
            'default'           => esc_html__('Cras ullamcorper turpis','spicebox'),
            'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
            'transport'         => $selective_refresh,
        ));
        $wp_customize->add_control( 'home_team_section_title',array(
            'label'   => esc_html__('Sub Title','spicebox'),
            'section' => 'cloudpress_team_section',
            'type'    => 'text',
        ));

        //Team section discription
        $wp_customize->add_setting( 'home_team_section_discription',array(
            'capability'        => 'edit_theme_options',
            'default'           => esc_html__('Cras blandit fringilla suscipit','spicebox'),
            'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
            'transport'         => $selective_refresh,
        ));
        $wp_customize->add_control( 'home_team_section_discription',array(
            'label'   => esc_html__('Title','spicebox'),
            'section' => 'cloudpress_team_section',
            'type'    => 'textarea',
        ));

        if ( class_exists( 'Cloudpress_Repeater' ) ) {
            $wp_customize->add_setting(
              'cloudpress_team_content', array(
              )
            );

            $wp_customize->add_control(
              new Cloudpress_Repeater(
                $wp_customize, 'cloudpress_team_content', array(
                  'label'                                 => esc_html__( 'Team content', 'spicebox' ),
                  'section'                               => 'cloudpress_team_section',
                  'priority'                              => 15,
                  'add_field_label'                       => esc_html__( 'Add new Team Member', 'spicebox' ),
                  'item_name'                             => esc_html__( 'Team Member', 'spicebox' ),
                  'customizer_repeater_image_control'     => true,
                  'customizer_repeater_title_control'     => true,
                  'customizer_repeater_subtitle_control'  => true,
                  'customizer_repeater_link_control'      => true,
                  'customizer_repeater_checkbox_control'  => true,
                  'customizer_repeater_repeater_control'  => true,
                )
              )
            );
        }

        // animation speed
        $wp_customize->add_setting( 'team_animation_speed', array( 'default' => 3000) );
        $wp_customize->add_control(	'team_animation_speed', array(
            'label'    => esc_html__( 'Animation speed', 'spicebox' ),
            'section'  => 'cloudpress_team_section',
            'type'     => 'select',
            'priority' => 53,
            'choices'  => array(
                '2000'  =>  '2.0',
                '3000'  =>  '3.0',
                '4000'  =>  '4.0',
                '5000'  =>  '5.0',
                '6000'  =>  '6.0',
            )
        ));

        //Navigation Type
        $wp_customize->add_setting( 'team_nav_style' , array( 'default' => 'bullets') );
        $wp_customize->add_control(	'team_nav_style' , array(
            'label'    => esc_html__( 'Navigation Style', 'spicebox' ),
            'section'  => 'cloudpress_team_section',
            'type'     => 'radio',
            'priority' => 17,
            'choices' => array(
                'bullets'   =>  esc_html__('Bullets', 'spicebox'),
                'navigation'=>  esc_html__('Navigation', 'spicebox'),
                'both'      =>  esc_html__('Both', 'spicebox'),
            )
        ));


        // smooth speed
        $wp_customize->add_setting( 'team_smooth_speed', array( 'default' => 1000) );
        $wp_customize->add_control(	'team_smooth_speed',
        array(
            'label'     => esc_html__( 'Smooth speed', 'spicebox' ),
            'section'   => 'cloudpress_team_section',
            'type'      => 'select',
            'priority'  => 17,
            'choices'   => array(
                '500'   =>'0.5',
                '1000'  =>'1.0',
                '1500'  =>'1.5',
                '2000'  =>'2.0',
                '2500'  =>'2.5',
                '3000'  =>'3.0'
            )
        ));
    }
    add_action( 'customize_register', 'spiceb_cloudpress_team_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_cloudpress_register_home_team_section_partials( $wp_customize ) {

	$wp_customize->selective_refresh->add_partial( 'cloudpress_team_content', array(
		'selector'            => '.section-module.team-members #team',
		'settings'            => 'cloudpress_team_content',
	) );

	$wp_customize->selective_refresh->add_partial( 'home_team_section_title', array(
  		'selector'            => '.section-module.team-members .section-subtitle',
  		'settings'            => 'home_team_section_title',
  		'render_callback'     => 'spiceb_cloudpress_team_section_title_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_team_section_discription', array(
  		'selector'            => '.section-module.team-members .section-title',
  		'settings'            => 'home_team_section_discription',
  		'render_callback'     => 'spiceb_cloudpress_team_section_discription_render_callback',

	) );

}
add_action( 'customize_register', 'spiceb_cloudpress_register_home_team_section_partials' );

function spiceb_cloudpress_team_section_title_render_callback() {
	return get_theme_mod( 'home_team_section_title' );
}

function spiceb_cloudpress_team_section_discription_render_callback() {
	return get_theme_mod( 'home_team_section_discription' );
}
