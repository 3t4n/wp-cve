<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Styles
{
    public static function init(): void
    {
        add_Action('wp_enqueue_scripts', [static::class, 'enqueueThemeSupport']);
        add_Action('wp_enqueue_scripts', [static::class, 'enqueueStyles']);
        add_Action('admin_enqueue_scripts', [static::class, 'registerDashboardStyles']);
    }

    public static function enqueueThemeSupport(): void
    {
        $arr_themes = [
            (object) ['name' => 'twentyten', 'check_function' => 'twentyten_setup'],
            (object) ['name' => 'twentyeleven', 'check_function' => 'twentyeleven_setup'],
            (object) ['name' => 'twentytwelve', 'check_function' => 'twentytwelve_setup'],
            (object) ['name' => 'twentythirteen', 'check_function' => 'twentythirteen_setup'],
            (object) ['name' => 'twentyfourteen', 'check_function' => 'twentyfourteen_setup'],
            #(object) [ 'name' => 'twentyfifteen', 'check_function' => 'twentyfifteen_setup' ], # Nothing to customize here yet
            (object) ['name' => 'twentysixteen', 'check_function' => 'twentysixteen_setup'],
            #(object) [ 'name' => 'twentyseventeen', 'check_function' => 'twentyseventeen_setup' ], # Nothing to customize here yet
            #(object) [ 'name' => 'twentynineteen', 'check_function' => 'twentynineteen_setup' ], # Nothing to customize here yet
            #(object) [ 'name' => 'twentytwenty', 'check_function' => 'twentytwenty_theme_support' ] # Nothing to customize here yet
        ];

        foreach ($arr_themes as $theme) {
            if (function_exists($theme->check_function)) {
                WP_Enqueue_Style("gallery-manager-{$theme->name}", Core::$base_url . "/assets/css/themes/{$theme->name}.css");
                break;
            }
        }
    }

    public static function enqueueStyles()
    {
        WP_Enqueue_Style('gallery-manager', Core::$base_url . '/assets/css/gallery-manager.css');
    }

    public static function registerDashboardStyles()
    {
        #WP_Enqueue_Style('gallery-manager-dashboard', Core::$base_url . '/assets/css/dashboard.css');
    }
}

Styles::init();
