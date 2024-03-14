<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Base files
 *
 */
function wpsbc_include_files_base()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include languages functions
    if (file_exists($dir_path . 'functions-languages.php')) {
        include $dir_path . 'functions-languages.php';
    }

    // Include utils functions
    if (file_exists($dir_path . 'functions-utils.php')) {
        include $dir_path . 'functions-utils.php';
    }

    // Include the shortcodes class
    if (file_exists($dir_path . 'class-shortcodes.php')) {
        include $dir_path . 'class-shortcodes.php';
    }

    // Include the widget class
    if (file_exists($dir_path . 'class-widget-calendar.php')) {
        include $dir_path . 'class-widget-calendar.php';
    }

    // Include the widget class
    if (file_exists($dir_path . 'class-widget-calendar-search.php')) {
        include $dir_path . 'class-widget-calendar-search.php';
    }

}
add_action('wpsbc_include_files', 'wpsbc_include_files_base');

/**
 * Returns an array with the weekdays
 *
 * @return array
 *
 */
function wpsbc_get_weekdays()
{

    $weekdays = array(
        __('Monday', 'wp-simple-booking-calendar'),
        __('Tuesday', 'wp-simple-booking-calendar'),
        __('Wednesday', 'wp-simple-booking-calendar'),
        __('Thursday', 'wp-simple-booking-calendar'),
        __('Friday', 'wp-simple-booking-calendar'),
        __('Saturday', 'wp-simple-booking-calendar'),
        __('Sunday', 'wp-simple-booking-calendar'),
    );

    return $weekdays;

}

/**
 * Returns true if there are any active languages in settings, false if not.
 *
 * @return bool
 *
 */
function wpsbc_translations_active()
{

    $settings = get_option('wpsbc_settings', array());

    if (!isset($settings['active_languages'])) {
        return false;
    }

    if (empty($settings['active_languages'])) {
        return false;
    }

    return true;

}

/**
 * Returns the starting day of the week
 *
 * @return int
 *
 */
function wpsbc_get_start_day()
{

    $settings = get_option('wpsbc_settings', array());

    return (!empty($settings['backend_start_day']) ? (int) $settings['backend_start_day'] : 1);
}
