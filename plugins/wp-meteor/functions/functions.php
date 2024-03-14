<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 1.0.0
 * @return array
 */
function wpmeteor_get_settings()
{
    return apply_filters(WPMETEOR_TEXTDOMAIN . '-load-settings', get_option(WPMETEOR_TEXTDOMAIN . '-settings', []));
}

function wpmeteor_set_settings($settings)
{
    return update_option(WPMETEOR_TEXTDOMAIN . '-settings', $settings);
}

function wpmeteor_sanitive_recursively($value)
{
    $sanitized = [];
    if (is_array($value) || is_object($value)) {
        foreach ($value as $k => $v) {
            $sanitized[$k] = wpmeteor_sanitive_recursively($v);
        }
        return $sanitized;
    } else {
        return sanitize_textarea_field(wp_unslash($value));
    }
}