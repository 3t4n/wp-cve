<?php

//Team Section
$wp_customize->add_section('spiko_team_section', array(
    'title' => esc_html__('Team Settings', 'spicebox'),
    'panel' => 'section_settings',
    'priority' => 4,
));

$wp_customize->add_setting('team_section_enable', array(
    'default' => true,
    'sanitize_callback' => 'spiceb_spiko_sanitize_checkbox'
));

$wp_customize->add_control(new Spiko_Toggle_Control($wp_customize, 'team_section_enable',
                array(
            'label' => esc_html__('Enable/Disable Team Section', 'spicebox'),
            'type' => 'toggle',
            'section' => 'spiko_team_section',
                )
));

//Team section discription
$wp_customize->add_setting('home_team_section_discription', array(
    'default' => esc_html__('Ullamco Laboris Nisi', 'spicebox'),
    'sanitize_callback' => 'spiceb_spiko_home_page_sanitize_text',

));
$wp_customize->add_control('home_team_section_discription', array(
    'label' => esc_html__('Sub Title', 'spicebox'),
    'section' => 'spiko_team_section',
    'type' => 'text',
    'active_callback' => 'spiceb_spiko_team_callback'
));

// Team section title
$wp_customize->add_setting('home_team_section_title', array(
    'default' => esc_html__('Magna Aliqua', 'spicebox'),
    'sanitize_callback' => 'spiceb_spiko_home_page_sanitize_text',
));
$wp_customize->add_control('home_team_section_title', array(
    'label' => esc_html__('Title', 'spicebox'),
    'section' => 'spiko_team_section',
    'type' => 'text',
    'active_callback' => 'spiceb_spiko_team_callback'
));



if (class_exists('Spicebox_Repeater')) {
    $wp_customize->add_setting(
            'spiko_team_content', array(
            )
    );

    $wp_customize->add_control(
            new Spicebox_Repeater(
                    $wp_customize, 'spiko_team_content', array(
                'label' => esc_html__('Team content', 'spicebox'),
                'section' => 'spiko_team_section',
                'priority' => 15,
                'add_field_label' => esc_html__('Add new Team Member', 'spicebox'),
                'item_name' => esc_html__('Team Member', 'spicebox'),
                'customizer_repeater_member_name_control' => true,
                //'customizer_repeater_text_control' => true,
                'customizer_repeater_designation_control' => true,
                'customizer_repeater_image_control' => true,
                //'customizer_repeater_image_control2' => true,
                // 'customizer_repeater_checkbox_control' => true,
                'customizer_repeater_repeater_control' => true,
                'active_callback' => 'spiceb_spiko_team_callback'
                    )
            )
    );
}

// animation speed
$wp_customize->add_setting('team_animation_speed', array('default' => 3000));
$wp_customize->add_control('team_animation_speed',
        array(
            'label' => esc_html__('Animation speed', 'spicebox'),
            'section' => 'spiko_team_section',
            'type' => 'select',
            'priority' => 53,
            'choices' => array(
                '2000' => '2.0',
                '3000' => '3.0',
                '4000' => '4.0',
                '5000' => '5.0',
                '6000' => '6.0',
            ),
            'active_callback' => 'spiceb_spiko_team_callback'
));

//Navigation Type
$wp_customize->add_setting('team_nav_style', array('default' => 'bullets'));
$wp_customize->add_control('team_nav_style', array(
    'label' => esc_html__('Navigation Style', 'spicebox'),
    'section' => 'spiko_team_section',
    'type' => 'radio',
    'priority' => 17,
    'choices' => array(
        'bullets' => esc_html__('Bullets', 'spicebox'),
        'navigation' => esc_html__('Navigation', 'spicebox'),
        'both' => esc_html__('Both', 'spicebox'),
    ),
    'active_callback' => 'spiceb_spiko_team_callback'
));

// smooth speed
$wp_customize->add_setting('team_smooth_speed', array('default' => 1000));
$wp_customize->add_control('team_smooth_speed',
        array(
            'label' => esc_html__('Smooth speed', 'spicebox'),
            'section' => 'spiko_team_section',
            'type' => 'select',
            'priority' => 17,
            'active_callback' => 'spiceb_spiko_team_callback',
            'choices' => array('500' => '0.5',
                '1000' => '1.0',
                '1500' => '1.5',
                '2000' => '2.0',
                '2500' => '2.5',
                '3000' => '3.0')
));

/**
 * Add selective refresh for Front page team section controls.
 */
$wp_customize->selective_refresh->add_partial('home_team_section_title', array(
    'selector' => '.team h2, .team2 .section-title, .team3 .section-title, .team4 .section-title',
    'settings' => 'home_team_section_title',
    'render_callback' => 'spiceb_home_team_section_title_render_callback',
));

$wp_customize->selective_refresh->add_partial('home_team_section_discription', array(
    'selector' => '.team h5, .team2 .section-subtitle, .team3 .section-subtitle, .team4 .section-subtitle',
    'settings' => 'home_team_section_discription',
    'render_callback' => 'spiceb_home_team_section_discription_render_callback',
));

function spiceb_home_team_section_title_render_callback() {
    return get_theme_mod('home_team_section_title');
}

function spiceb_home_team_section_discription_render_callback() {
    return get_theme_mod('home_team_section_discription');
}

?>