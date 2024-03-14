<?php
/**
 *  Themes functionality.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Hide Themes from the theme page.
 *
 * @return void
 */
function white_label_hide_themes($prepared_themes)
{
    // Exit If it's WL Admin.
    if (white_label_is_wl_admin()) {
        return $prepared_themes;
    }

    $hidden_themes = white_label_get_option('hidden_themes', 'white_label_themes', false);

    // Exit if no settings.
    if (empty($hidden_themes)) {
        return $prepared_themes;
    }

    // Check each theme name.
    foreach ($hidden_themes as $theme) {
        if (isset($prepared_themes[$theme])) {
            unset($prepared_themes[$theme]);
        }
    }

    return $prepared_themes;
}

add_filter('wp_prepare_themes_for_js', 'white_label_hide_themes', 999, 1);


/**
 * Hide theme updates from the transient & updates page.
 *
 * @param array $value transient updates array.
 *
 * @return array $value update information.
 */
function white_label_hide_theme_updates($value)
{
    // Exit if it's WL Admin.
    if (white_label_is_wl_admin()) {
        return $value;
    }


    $hidden_themes = white_label_get_option('hidden_themes', 'white_label_themes', false);

    if (!empty($hidden_themes)) {
        // Hide each theme update.
        foreach ($hidden_themes as $theme) {
            if (isset($value->response[$theme])) {
                unset($value->response[$theme]);
            }
        }
    }

    return $value;
}

add_filter('site_transient_update_themes', 'white_label_hide_theme_updates');