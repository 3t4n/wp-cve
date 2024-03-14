<?php
$theme = wp_get_theme();
    if( $theme->name=='BusiCare Dark')
    {
    $priority = 3;  
    }
    else
    {
    $priority = 5;  
    }
//Team Section
$wp_customize->add_section('busicare_team_section', array(
    'title' => esc_html__('Team Settings', 'spicebox'),
    'panel' => 'section_settings',
    'priority' => $priority,
));

$wp_customize->add_setting('team_section_enable', array(
    'default' => true,
    'sanitize_callback' => 'spiceb_busicare_sanitize_checkbox'
));

$wp_customize->add_control(new busicare_Toggle_Control($wp_customize, 'team_section_enable',
                array(
            'label' => esc_html__('Enable/Disable Team Section', 'spicebox'),
            'type' => 'toggle',
            'section' => 'busicare_team_section',
                )
));

// Team section title
$wp_customize->add_setting('home_team_section_title', array(
    'default' => esc_html__('Lorem ipsum', 'spicebox'),
    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
    'transport' => $selective_refresh,
));
$wp_customize->add_control('home_team_section_title', array(
    'label' => esc_html__('Title', 'spicebox'),
    'section' => 'busicare_team_section',
    'type' => 'text',
    'active_callback' => 'spiceb_busicare_team_callback'
));

//Team section discription
$wp_customize->add_setting('home_team_section_discription', array(
    'default' => esc_html__('Lorem ipsum dolor sit ame', 'spicebox'),
    'sanitize_callback' => 'spiceb_busicare_home_page_sanitize_text',
    'transport' => $selective_refresh,
));
$wp_customize->add_control('home_team_section_discription', array(
    'label' => esc_html__('Sub Title', 'spicebox'),
    'section' => 'busicare_team_section',
    'type' => 'text',
    'active_callback' => 'spiceb_busicare_team_callback'
));

if (class_exists('Spicebox_Repeater')) {
    $wp_customize->add_setting(
            'busicare_team_content', array(
            )
    );

    $wp_customize->add_control(
            new Spicebox_Repeater(
                    $wp_customize, 'busicare_team_content', array(
                'label' => esc_html__('Team content', 'spicebox'),
                'section' => 'busicare_team_section',
                'priority' => 15,
                'add_field_label' => esc_html__('Add New Team Member', 'spicebox'),
                'item_name' => esc_html__('Team Member', 'spicebox'),
                 'customizer_repeater_member_name_control' => true,
                'customizer_repeater_designation_control' => true,
                'customizer_repeater_image_control' => true,
                'customizer_repeater_link_control' => true,
                'customizer_repeater_checkbox_control' => true,
                'customizer_repeater_repeater_control' => true,
                'active_callback' => 'spiceb_busicare_team_callback'
                    )
            )
    );
}

//Navigation Type
$wp_customize->add_setting('team_nav_style', array('default' => 'bullets'));
$wp_customize->add_control('team_nav_style', array(
    'label' => esc_html__('Navigation Style', 'spicebox'),
    'section' => 'busicare_team_section',
    'type' => 'radio',
    'priority' => 17,
    'choices' => array(
        'bullets' => esc_html__('Bullets', 'spicebox'),
        'navigation' => esc_html__('Navigation', 'spicebox'),
        'both' => esc_html__('Both', 'spicebox'),
    ),
    'active_callback' => 'spiceb_busicare_team_callback'
));


/**
 * Add selective refresh for Front page team section controls.
 */
$wp_customize->selective_refresh->add_partial('home_team_section_title', array(
    'selector' => '.team-group h2, .team2 .section-title, .team3 .section-title, .team4 .section-title',
    'settings' => 'home_team_section_title',
    'render_callback' => 'spiceb_home_team_section_title_render_callback',
));

$wp_customize->selective_refresh->add_partial('home_team_section_discription', array(
    'selector' => '.team-group h5, .team2 .section-subtitle, .team3 .section-subtitle, .team4 .section-subtitle',
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