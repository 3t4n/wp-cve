<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles the migration of the calendars from the old plugins to the new structure
 *
 */
function wpsbc_action_ajax_migrate_calendars()
{

    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpsbc_upgrader')) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

    $upgrade_from = wpsbc_process_upgrade_from();

    if (false == $upgrade_from) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

    /**
     * Verify for the existance of calendars
     *
     */
    $calendars = wpsbc_get_calendars();

    if (!empty($calendars)) {
        echo json_encode(array('success' => 0));
        wp_die();
    }


    /**
     * Handle upgrade from free version
     *
     */
    if ($upgrade_from == 'free') {

        // Get calendars options
        $calendars_options = get_option('wp-simple-booking-calendar-options');

        // Get the first array key of the calendar
        foreach ($calendars_options['calendars'] as $key => $old_calendar_data) {

            $calendar_id = $key;
            break;

        }

        /**
         * Handle Calendar
         *
         */

        // Set the new calendar data.
        $calendar_data = array(
            'id' => $calendar_id,
            'name' => $calendars_options['calendars'][$calendar_id]['calendarName'],
            'date_created' => date('Y-m-d H:i:s', $calendars_options['calendars'][$calendar_id]['dateCreated']),
            'date_modified' => date('Y-m-d H:i:s', $calendars_options['calendars'][$calendar_id]['dateModified']),
            'status' => 'active',
            'ical_hash' => wpsbc_generate_ical_hash(),
        );

        $calendar_id = wpsbc_insert_calendar($calendar_data);

        /**
         * Handle Legend Items
         *
         */
        $legend_items_data = wpsbc_get_default_legend_items_data();

       

        foreach ($legend_items_data as $legend_item_data) {

            // Set the calendar id for the legend items data
            $legend_item_data['calendar_id'] = $calendar_id;

            // Insert legend item
            wpsbc_insert_legend_item($legend_item_data);

        }

    }

    echo json_encode(array('success' => 1));
    wp_die();

}
add_action('wp_ajax_wpsbc_action_ajax_migrate_calendars', 'wpsbc_action_ajax_migrate_calendars');

/**
 * Handles the migration of the bookings from the old plugins to the new structure
 *
 */
function wpsbc_action_ajax_migrate_bookings()
{

    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpsbc_upgrader')) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

    $upgrade_from = wpsbc_process_upgrade_from();

    if (false == $upgrade_from) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

   

    /**
     * Handle upgrade from free version
     *
     */
    if ($upgrade_from == 'free') {

        /**
         * Get calendar data
         *
         */
        $calendars_options = get_option('wp-simple-booking-calendar-options');

        // Get the first array key of the calendar
        foreach ($calendars_options['calendars'] as $key => $old_calendar_data) {

            $calendar_id = $key;
            break;

        }

        if (!empty($calendars_options['calendars'][$calendar_id]['calendarJson'])) {

            $calendar_bookings = json_decode($calendars_options['calendars'][$calendar_id]['calendarJson'], true);

            /**
             * Clean up bookings array keys and arrange every year and month array
             * by keys, so that it's in chronological order
             *
             */
            foreach ($calendar_bookings as $year => $year_data) {

                unset($calendar_bookings[$year]);

                $year = absint(str_replace('year', '', $year));

                $calendar_bookings[$year] = $year_data;

                foreach ($year_data as $month => $month_data) {

                    unset($calendar_bookings[$year][$month]);

                    $month = absint(str_replace('month', '', $month));

                    $calendar_bookings[$year][$month] = $month_data;

                    ksort($calendar_bookings[$year]);

                    foreach ($month_data as $day => $booking) {

                        unset($calendar_bookings[$year][$month][$day]);

                        $day = absint(str_replace('day', '', $day));

                        $calendar_bookings[$year][$month][$day] = $booking;

                        ksort($calendar_bookings[$year][$month]);

                    }

                }

            }

            /**
             * Add events based on bookings
             *
             */
            foreach ($calendar_bookings as $year => $year_data) {

                foreach ($year_data as $month => $month_data) {

                    foreach ($month_data as $day => $booking) {

                        $event_data = array(
                            'calendar_id' => $calendar_id,
                            'date_year' => $year,
                            'date_month' => $month,
                            'date_day' => $day,
                            'legend_item_id' => ($booking == 'booked' ? 2 : ($booking == 'changeover' ? 3 : 0)),
                        );

                        $event_id = wpsbc_insert_event($event_data);

                    }

                }

            }

        }

    }

    echo json_encode(array('success' => 1));
    wp_die();

}
add_action('wp_ajax_wpsbc_action_ajax_migrate_bookings', 'wpsbc_action_ajax_migrate_bookings');

/**
 * Handles the migration of the general settings from the old plugins to the new structure
 *
 */
function wpsbc_action_ajax_migrate_general_settings()
{

    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpsbc_upgrader')) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

    $upgrade_from = wpsbc_process_upgrade_from();

    if (false == $upgrade_from) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

   
    echo json_encode(array('success' => 1));
    wp_die();

}
add_action('wp_ajax_wpsbc_action_ajax_migrate_general_settings', 'wpsbc_action_ajax_migrate_general_settings');

/**
 * Handles the migration of the general settings from the old plugins to the new structure
 *
 */
function wpsbc_action_ajax_migrate_finishing_up()
{

    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpsbc_upgrader')) {
        echo json_encode(array('success' => 0));
        wp_die();
    }

    // Add the option that the upgrader has migrated the data
    update_option('wpsbc_upgrade_8_0_0', 1);

    echo json_encode(array('success' => 1));
    wp_die();

}
add_action('wp_ajax_wpsbc_action_ajax_migrate_finishing_up', 'wpsbc_action_ajax_migrate_finishing_up');
