<?php
class MegaMenu_WP_Settings {

    function __construct() {
        //add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'customize_register', array( $this, 'customize_register' ), 93 );
    }

    function customize_register( $wp_customize ){
        $support = get_theme_support('megamenu-wp');
        $wp_customize->add_section( 'mega_menu' , array(
            'title'      => esc_html__( 'Mega Menu Settings', 'megamenu-wp' ),
            'priority'   => 3,
            'panel' => 'nav_menus'
        ) );

        $wp_customize->add_setting( 'mega_disable_css', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'mega_disable_css', array(
            'label'       => esc_html__( 'Disable auto css', 'megamenu-wp' ),
            'section'     => 'mega_menu',
            'type'        => 'checkbox',
            'description' => esc_html__( 'Disable auto css and use your custom css.', 'megamenu-wp' ),
        ) );

        if ( false === MegaMenu_WP::get_theme_support( 'parent_level' ) ) {
            $wp_customize->add_setting('mega_parent_level', array(
                'default' => 0,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $parent_levels = array();
            $parent_levels[0] = esc_html__('Auto', 'megamenu-wp');
            for ($i = 1; $i <= 20; $i++) {
                $parent_levels[$i] = sprintf(_n('%s Level', '%s Levels', $i, 'megamenu-wp'), $i);
            }

            $wp_customize->add_control('mega_parent_level', array(
                'label' => esc_html__('Mega Wrapper', 'megamenu-wp'),
                'section' => 'mega_menu',
                'description' => esc_html__('The width of mega content may get incorrect so you can switch to each parent level until it get correct.', 'megamenu-wp'),
                'type' => 'select',
                'choices' => $parent_levels
            ));
        }

        if ( false === MegaMenu_WP::get_theme_support( 'mobile_mod' ) ) {
            $wp_customize->add_setting('mega_mobile_break_points', array(
                'default' => 720,
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('mega_mobile_break_points', array(
                'label' => esc_html__('Mobile break points', 'megamenu-wp'),
                'section' => 'mega_menu',
                'description' => esc_html__('Screen pixels to enter mobile mod.', 'megamenu-wp'),
            ));
        }

        if ( false === MegaMenu_WP::get_theme_support( 'margin_top' ) ) {
            $wp_customize->add_setting('mega_content_margin_top', array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('mega_content_margin_top', array(
                'label' => esc_html__('Mega content margin top (px)', 'megamenu-wp'),
                'description' => esc_html__('', 'megamenu-wp'),
                'section' => 'mega_menu',
            ));
        }

    }

    function admin_menu() {
        add_options_page(
            esc_html__( 'Mega Menu', 'megamenu-wp' ),
            esc_html__( 'Mega Menu', 'megamenu-wp' ),
            'manage_options',
            'options_page_slug',
            array(
                $this,
                'settings_page'
            )
        );
    }

    function  settings_page() {
        ob_start();
        $url = admin_url( 'customize.php?autofocus[panel]=nav_menus&autofocus[section]=mega_menu' );
        wp_redirect( $url );
        die();
    }
}

new MegaMenu_WP_Settings;
