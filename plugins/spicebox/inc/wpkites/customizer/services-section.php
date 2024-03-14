<?php
$wp_customize->add_section('services_section', array(
    'title' => esc_html__('Services Settings', 'spicebox'),
    'panel' => 'section_settings',
    'priority' => 2,
));

// Enable service more btn
$wp_customize->add_setting('home_service_section_enabled', array(
    'default' => true,
    'sanitize_callback' => 'spiceb_wpkites_sanitize_checkbox'
    ));

$wp_customize->add_control(new WPKites_Toggle_Control($wp_customize, 'home_service_section_enabled',
                array(
            'label' => esc_html__('Enable/Disable Service Section', 'spicebox'),
            'type' => 'toggle',
            'section' => 'services_section',
                )
));

//Service section title
$wp_customize->add_setting('home_service_section_title', array(
    'capability' => 'edit_theme_options',
    'default' => esc_html__('Quisque Blandit', 'spicebox'),
    'sanitize_callback' => 'spiceb_wpkites_home_page_sanitize_text',
    'transport' => $selective_refresh,
));

$wp_customize->add_control('home_service_section_title', array(
    'label' => esc_html__('Title', 'spicebox'),
    'section' => 'services_section',
    'type' => 'text',
    'active_callback' => 'spiceb_wpkites_service_callback'
));


// Service section description
$wp_customize->add_setting('home_service_section_discription', array(
    'capability' => 'edit_theme_options',
    'default' => esc_html__('Fusce Sed Massa', 'spicebox'),
    'sanitize_callback' => 'spiceb_wpkites_home_page_sanitize_text',
    'transport' => $selective_refresh,
));

$wp_customize->add_control('home_service_section_discription', array(
    'label' => esc_html__('Sub Title', 'spicebox'),
    'section' => 'services_section',
    'type' => 'text',
    'active_callback' => 'spiceb_wpkites_service_callback'
));


if (class_exists('Spicebox_Limit_Repeater')) {
    $wp_customize->add_setting('wpkites_service_content', array());

    $wp_customize->add_control(new Spicebox_Limit_Repeater($wp_customize, 'wpkites_service_content', array(
                'label' => esc_html__('Service content', 'spicebox'),
                'section' => 'services_section',
                'priority' => 10,
                'add_field_label' => esc_html__('Add new Service', 'spicebox'),
                'item_name' => esc_html__('Service', 'spicebox'),
                'customizer_repeater_icon_control' => true,
                'customizer_repeater_title_control' => true,
                'customizer_repeater_text_control' => true,
                'customizer_repeater_link_control' => true,
                'customizer_repeater_checkbox_control' => true,
                'customizer_repeater_image_control' => true,
                'active_callback' => 'spiceb_wpkites_service_callback'
    )));
}

class wpkites_services_section_upgrade extends WP_Customize_Control {
            public function render_content() { ?>
                <h3 class="customizer_wpkites_service_upgrade_section" style="display: none;">
        <?php esc_html_e('To add More Service? Then','spicebox'); ?><a href="<?php echo esc_url( 'https://spicethemes.com/wpkites-plus' ); ?>" target="_blank">
                    <?php esc_html_e('Upgrade to Plus','spicebox'); ?> </a>  
                </h3>
            <?php
            }
        }
        
        $wp_customize->add_setting( 'wpkites_service_upgrade_to_pro', array(
            'capability'            => 'edit_theme_options',
        ));
        $wp_customize->add_control(
            new wpkites_services_section_upgrade(
            $wp_customize,
            'wpkites_service_upgrade_to_pro',
                array(
                    'section'               => 'services_section',
                    'settings'              => 'wpkites_service_upgrade_to_pro',
                    'active_callback' => 'spiceb_wpkites_service_callback'
                )
            )
        );

$wp_customize->selective_refresh->add_partial('home_service_section_title', array(
    'selector' => '.services .section-title, .services2 .section-title, .services3 .section-title, .services4 .section-title',
    'settings' => 'home_service_section_title',
    'render_callback' => 'spiceb_home_service_section_title_render_callback'
));

$wp_customize->selective_refresh->add_partial('home_service_section_discription', array(
    'selector' => '.services .section-subtitle, .services2 .section-subtitle, .services3 .section-subtitle, .services4 .section-subtitle',
    'settings' => 'home_service_section_discription',
    'render_callback' => 'spiceb_home_service_section_discription_render_callback'
));

$wp_customize->selective_refresh->add_partial('service_viewmore_btn_text', array(
    'selector' => '.services .view-more-services',
    'settings' => 'service_viewmore_btn_text',
    'render_callback' => 'spiceb_service_viewmore_btn_text_render_callback'
));

function spiceb_home_service_section_title_render_callback() {
    return get_theme_mod('home_service_section_title');
}

function spiceb_home_service_section_discription_render_callback() {
    return get_theme_mod('home_service_section_discription');
}

function spiceb_service_viewmore_btn_text_render_callback() {
    return get_theme_mod('service_viewmore_btn_text');
}