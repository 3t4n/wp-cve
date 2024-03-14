<?php

namespace HQRentalsPlugin\HQRentalsThemes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;

class HQRentalsThemeCustomizer
{
    public function __construct()
    {
        add_action('customize_register', array($this,'addCustomizationToTheme'));
    }
    public function addCustomizationToTheme($wp_customize)
    {
        $wp_customize->add_panel('hq_rental_theme_menu', array(
            'title' => __('HQ Rental Software'),
            'description' => 'Theme Customization Options', // Include html tags such as <p>.
            'priority' => 300, // Mixed with top-level-section hierarchy.
            ));
        /*Images Section*/
        $wp_customize->add_section('images_section', array(
            'title' => 'Images',
            'panel' => 'hq_rental_theme_menu',
        ));
        $wp_customize->add_setting('hq_tenant_logo', array(
            'default' => '',
        ));
        $wp_customize->add_setting('hq_map_pin_image', array(
            'default' => '',
        ));

        $wp_customize->add_control(
            new \WP_Customize_Media_Control(
                $wp_customize, // WP_Customize_Manager
                'hq_tenant_logo', // Setting id
                array( // Args, including any custom ones.
                    'label' => __('Tenant Logo'),
                    'section' => 'images_section',
                )
            )
        );
        $wp_customize->add_control(
            new \WP_Customize_Media_Control(
                $wp_customize, // WP_Customize_Manager
                'hq_map_pin_image', // Setting id
                array( // Args, including any custom ones.
                    'label' => __('Google Map Pin Image'),
                    'section' => 'images_section',
                )
            )
        );
        /*primary color*/
        $wp_customize->add_section('theme_color_section', array(
            'title' => 'Theme',
            'panel' => 'hq_rental_theme_menu',
        ));
        $wp_customize->add_setting('hq_theme_color', array(
            'default' => '',
        ));

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize, // WP_Customize_Manager
                'hq_theme_color', // Setting id
                array( // Args, including any custom ones.
                    'label' => __('Theme Color'),
                    'section' => 'theme_color_section',
                )
            )
        );
    }

    public static function getThemeTenantLogo(): string
    {
        return wp_get_attachment_url(get_theme_mod('hq_tenant_logo'));
    }
    public static function getThemeColor(): string
    {
        return get_theme_mod('hq_theme_color') ?? '#FFF';
    }
    public static function getTenantLogoURL(): string
    {
        return wp_get_attachment_url(get_theme_mod('hq_tenant_logo')) ?? '';
    }
    public static function getMapPinImage(): string
    {
        return empty(wp_get_attachment_url(get_theme_mod('hq_map_pin_image'))) ?
            HQRentalsAssetsHandler::getDefaultMapMarkerImage() : wp_get_attachment_url(get_theme_mod('hq_map_pin_image'));
    }
}
