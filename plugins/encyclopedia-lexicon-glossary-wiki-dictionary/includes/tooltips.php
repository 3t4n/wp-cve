<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class Tooltips
{
    public static function init(): void
    {
        add_action('init', [static::class, 'registerScripts']);
        add_action('wp_enqueue_scripts', [static::class, 'enqueueScripts']);
    }

    public static function registerScripts(): void
    {
        WP_Register_Script('tooltipster', Core::$base_url . '/assets/js/tooltipster.bundle.min.js', ['jquery'], '4.2.6', true);
        WP_Register_Script('encyclopedia-tooltips', Core::$base_url . '/assets/js/tooltips.js', ['tooltipster'], null, true);

        $js_parameters = [];
        $js_parameters = apply_Filters('encyclopedia_tooltip_js_parameters', $js_parameters);

        WP_Localize_Script('encyclopedia-tooltips', 'Encyclopedia_Tooltips', $js_parameters);
    }

    public static function enqueueScripts(): void
    {
        if (Options::get('activate_tooltips')) {
            WP_Enqueue_Style('encyclopedia-tooltips', Core::$base_url . '/assets/css/tooltips.css');
            WP_Enqueue_Script('encyclopedia-tooltips');
        }
    }
}

Tooltips::init();
