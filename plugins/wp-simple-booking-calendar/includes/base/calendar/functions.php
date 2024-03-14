<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Calendars
 *
 */
function wpsbc_include_files_calendar()
{

    // Get calendar dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include other functions files
    if (file_exists($dir_path . 'functions-ajax.php')) {
        include $dir_path . 'functions-ajax.php';
    }

    // Include main Calendar class
    if (file_exists($dir_path . 'class-calendar.php')) {
        include $dir_path . 'class-calendar.php';
    }

    // Include the db layer classes
    if (file_exists($dir_path . 'class-object-db-calendars.php')) {
        include $dir_path . 'class-object-db-calendars.php';
    }

    if (file_exists($dir_path . 'class-object-meta-db-calendars.php')) {
        include $dir_path . 'class-object-meta-db-calendars.php';
    }

    // Include calendar outputters
    if (file_exists($dir_path . 'class-calendar-outputter.php')) {
        include $dir_path . 'class-calendar-outputter.php';
    }


}
add_action('wpsbc_include_files', 'wpsbc_include_files_calendar');

/**
 * Register the class that handles database queries for the Calendars
 *
 * @param array $classes
 *
 * @return array
 *
 */
function wpsbc_register_database_classes_calendars($classes)
{

    $classes['calendars'] = 'WPSBC_Object_DB_Calendars';
    $classes['calendarmeta'] = 'WPSBC_Object_Meta_DB_Calendars';

    return $classes;

}
add_filter('wpsbc_register_database_classes', 'wpsbc_register_database_classes_calendars');

/**
 * Returns an array with WPSBC_Calendar objects from the database
 *
 * @param array $args
 * @param bool  $count
 *
 * @return array
 *
 */
function wpsbc_get_calendars($args = array(), $count = false)
{

    $calendars = wp_simple_booking_calendar()->db['calendars']->get_calendars($args, $count);

    /**
     * Add a filter hook just before returning
     *
     * @param array $calendars
     * @param array $args
     * @param bool  $count
     *
     */
    return apply_filters('wpsbc_get_calendars', $calendars, $args, $count);

}

/**
 * Gets a calendar from the database
 *
 * @param mixed int|object      - calendar id or object representing the calendar
 *
 * @return WPSBC_Calendar|false
 *
 */
function wpsbc_get_calendar($calendar)
{

    return wp_simple_booking_calendar()->db['calendars']->get_object($calendar);

}

/**
 * Inserts a new calendar into the database
 *
 * @param array $data
 *
 * @return mixed int|false
 *
 */
function wpsbc_insert_calendar($data)
{

    return wp_simple_booking_calendar()->db['calendars']->insert($data);

}

/**
 * Updates a calendar from the database
 *
 * @param int     $calendar_id
 * @param array $data
 *
 * @return bool
 *
 */
function wpsbc_update_calendar($calendar_id, $data)
{

    return wp_simple_booking_calendar()->db['calendars']->update($calendar_id, $data);

}

/**
 * Deletes a calendar from the database
 *
 * @param int $calendar_id
 *
 * @return bool
 *
 */
function wpsbc_delete_calendar($calendar_id)
{

    return wp_simple_booking_calendar()->db['calendars']->delete($calendar_id);

}

/**
 * Inserts a new meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $unique
 *
 * @return mixed int|false
 *
 */
function wpsbc_add_calendar_meta($calendar_id, $meta_key, $meta_value, $unique = false)
{

    return wp_simple_booking_calendar()->db['calendarmeta']->add($calendar_id, $meta_key, $meta_value, $unique);

}

/**
 * Updates a meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $prev_value
 *
 * @return bool
 *
 */
function wpsbc_update_calendar_meta($calendar_id, $meta_key, $meta_value, $prev_value = '')
{

    return wp_simple_booking_calendar()->db['calendarmeta']->update($calendar_id, $meta_key, $meta_value, $prev_value);

}

/**
 * Returns a meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param bool   $single
 *
 * @return mixed
 *
 */
function wpsbc_get_calendar_meta($calendar_id, $meta_key = '', $single = false)
{

    return wp_simple_booking_calendar()->db['calendarmeta']->get($calendar_id, $meta_key, $single);

}

/**
 * Removes a meta entry for the calendar
 *
 * @param int    $calendar_id
 * @param string $meta_key
 * @param string $meta_value
 * @param bool   $delete_all
 *
 * @return bool
 *
 */
function wpsbc_delete_calendar_meta($calendar_id, $meta_key, $meta_value = '', $delete_all = '')
{

    return wp_simple_booking_calendar()->db['calendarmeta']->delete($calendar_id, $meta_key, $meta_value, $delete_all);

}

/**
 * Returns the default arguments for the calendar outputter
 *
 * @return array
 *
 */
function wpsbc_get_calendar_output_default_args()
{

    $args = array(
        'show_title' => 1,
        'show_legend' => 1,
        'legend_position' => 'side',
        'show_button_navigation' => 1,
        'current_year' => current_time('Y'),
        'current_month' => current_time('n'),
        'language' => wpsbc_get_locale(),
        'min_width' => '200',
        'max_width' => '380',
    );

    /**
     * Filter the args before returning
     *
     * @param array $args
     *
     */
    $args = apply_filters('wpsbc_get_calendar_output_default_args', $args);

    return $args;

}

/**
 * Generates and returns a random 32 character long string
 *
 * @return string
 *
 */
function wpsbc_generate_ical_hash()
{

    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_length = strlen($chars);
    $ical_hash = '';

    for ($i = 0; $i < 19; $i++) {

        $ical_hash .= $chars[rand(0, $chars_length - 1)];

    }

    return $ical_hash . uniqid();

}

/**
 * Remove Timezone from Date strings.
 * 
 * @param string $date
 * 
 * @return string
 * 
 */
function wpsbc_remove_timezone_from_date_string($date)
{
    return explode('T', $date)[0];
}
