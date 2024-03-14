<?php
$settings = array(
    'default_category' => array(
        'type'          => 'text',
        'default_value' => ''
    ),
    'external_color_style' => array(
        'type'          => 'checkbox',
        'default_value' => 'N'
    ),
    'credit' => array(
        'type'          => 'checkbox',
        'default_value' => 'N'
    ),
    'css_location_type' => array(
        'type'          => 'text',
        'default_value' => 'site'
    ),
    'css_location_posts' => array(
        'type'          => 'multiple',
        'default_value' => array()
    ),
);

return $settings;