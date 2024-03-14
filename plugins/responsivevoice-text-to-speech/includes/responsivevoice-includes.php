<?php

function RV_load_scripts()
{
    $options = get_option('RV_settings', '');
    $apiKey  = !empty($options) && array_key_exists('RV_text_api_key', $options) ? $options['RV_text_api_key'] : '';

    $appendScriptTag = [
        'source=wp-plugin'
    ];

    if(!empty($apiKey)){
        $appendScriptTag[] = "key=$apiKey";
    }

    wp_enqueue_style('rv-style', plugin_dir_url(__FILE__) . 'css/responsivevoice.css');
    wp_enqueue_script('responsive-voice', 'https://code.responsivevoice.org/1.8.3/responsivevoice.js?' . implode('&', $appendScriptTag));
}

add_action('wp_enqueue_scripts', 'RV_load_scripts');