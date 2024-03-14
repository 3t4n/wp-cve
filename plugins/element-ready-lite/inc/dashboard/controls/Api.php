<?php

$return_arr = [
    
    'facebook_app_id' => [
        'demo_link' => 'https://developers.facebook.com/docs/development/',
        'lavel'     => esc_html__('FB App ID','element-ready-lite'),
        'default'   => 1,
        'type'      => 'text',
        'is_pro'    => 0,
    ],
    
    'facebook_secret_code' => [
        'demo_link' => 'https://developers.facebook.com/docs/facebook-login/access-tokens/',
        'lavel'     => esc_html__('FB Secret Code','element-ready-lite'),
        'default'   => 1,
        'type'      => 'text',
        'is_pro'    => 0,
    ],

    'weather_api_key' => [
        'demo_link' => 'https://openweathermap.org/api',
        'lavel'     => esc_html__('Weather','element-ready-lite'),
        'default'   => '',
        'type'      => 'text',
        'is_pro'    => 0,
    ],

];

$return_arr = apply_filters('element_ready/dashboard/api-data' , $return_arr );