<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class Styles
{
    public static function init(): void
    {
        add_action('wp_enqueue_scripts', [static::class, 'enqueueThemeSupport']);
        add_action('wp_enqueue_scripts', [static::class, 'enqueueDefaultStyle']);
        #add_action('admin_enqueue_scripts', [static::class, 'registerDashboardStyles']);
    }

    public static function enqueueThemeSupport(): void
    {
        $active_theme = wp_get_theme();
        $theme_folder = $active_theme->get_stylesheet();
        $parent_theme_folder = $active_theme->get_template();

        $arr_themes = [
            (object) ['name' => 'twentyten', 'check_function' => 'twentyten_setup'],
            (object) ['name' => 'twentyeleven', 'check_function' => 'twentyeleven_setup'],
            (object) ['name' => 'twentytwelve', 'check_function' => 'twentytwelve_setup'],
            (object) ['name' => 'twentythirteen', 'check_function' => 'twentythirteen_setup'],
            (object) ['name' => 'twentyfourteen', 'check_function' => 'twentyfourteen_setup'],
            (object) ['name' => 'twentyfifteen', 'check_function' => 'twentyfifteen_setup'],
            (object) ['name' => 'twentysixteen', 'check_function' => 'twentysixteen_setup'],
            (object) ['name' => 'twentyseventeen', 'check_function' => 'twentyseventeen_setup'],
            (object) ['name' => 'twentynineteen', 'check_function' => 'twentynineteen_setup'],
            (object) ['name' => 'twentytwenty', 'check_function' => 'twentytwenty_theme_support'],
            (object) ['name' => 'twentytwentythree', 'check_name' => 'twentytwentythree']
        ];

        foreach ($arr_themes as $theme) {
            if (
                (isset($theme->check_function) && function_exists($theme->check_function)) ||
                (isset($theme->check_name) && in_array($theme->check_name, [$theme_folder, $parent_theme_folder]))
            ) {
                WP_Enqueue_Style("encyclopedia-{$theme->name}", Core::$base_url . "/assets/css/themes/{$theme->name}.css");
                break;
            }
        }
    }

    public static function enqueueDefaultStyle(): void
    {
        if (Options::get('embed_default_style'))
            WP_Enqueue_Style('encyclopedia', Core::$base_url . '/assets/css/encyclopedia.css');
    }

    public static function registerDashboardStyles()
    {
        #WP_Enqueue_Style('encyclopedia-dashboard-extension', Core::$base_url . '/assets/css/dashboard.css');
    }
}

Styles::init();
