<?php

/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 *
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;   // Exit if accessed directly.
}

if (!class_exists('WP_Customize_Setting', false)) {
    include_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
}

class Aarambha_DS_Customize_Setting extends WP_Customize_Setting
{

    /**
     * Imports the data.
     * 
     * Calls the WP_Customize_Setting::update method.
     * 
     * @return void.
     */
    public function import($value)
    {
        $this->update($value);
    }
}
