<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

return [
    "name" => RSGD_NAME,
    "base" => RSGD_PFX,
    'category' => esc_html__( 'Content', 'js_composer' ),
    'description' => esc_html__( 'Add RAYS Grid Shortcode', RSGD_SLUG ),
    'icon' => RSGD_URI.'/assets/admin/images/short-logo.png',
    "show_settings_on_create" => true,
    "params" => [
        [
            "type" => "dropdown",
            "heading" => esc_html__("Choose Grid", RSGD_SLUG ),
            "param_name" => "alias",
            "value" => it_dropdown_grids(),
        ]
    ]
];
    
