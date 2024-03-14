<?php
function bea_get_option($option_name, $default = null) {

    $settings = get_option('bea_settings');

    if (!empty($settings) && isset($settings[$option_name]))
        $option_value = $settings[$option_name];
    else
        $option_value = $default;

    return apply_filters('bea_get_option', $option_value, $option_name, $default);
}

function bea_update_option($option_name, $option_value) {

    $settings = get_option('bea_settings');

    if (empty($settings))
        $settings = array();

    $settings[$option_name] = $option_value;

    update_option('bea_settings', $settings);
}


/**
 * Update multiple options in one go
 * @param array $setting_data An collection of settings key value pairs;
 */
function bea_update_options($setting_data) {

    $settings = get_option('bea_settings');

    if (empty($settings))
        $settings = array();

    foreach ($setting_data as $setting => $value) {
        // because of get_magic_quotes_gpc()
        $value = stripslashes($value);
        $settings[$setting] = $value;
    }

    update_option('bea_settings', $settings);
}