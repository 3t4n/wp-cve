<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Validates and handles the adding of the new calendar in the database
 *
 */
function wpsbc_action_add_calendar()
{

    // Verify for nonce
    if (empty($_POST['wpsbc_token']) || !wp_verify_nonce($_POST['wpsbc_token'], 'wpsbc_add_calendar')) {
        return;
    }

    // Verify for calendar name
    if (empty($_POST['calendar_name'])) {

        wpsbc_admin_notices()->register_notice('calendar_name_missing', '<p>' . __('Please add a name for your new calendar.', 'wp-simple-booking-calendar') . '</p>', 'error');
        wpsbc_admin_notices()->display_notice('calendar_name_missing');

        return;

    }

    // Prepare calendar data to be inserted
    $calendar_data = array(
        'name' => sanitize_text_field($_POST['calendar_name']),
        'date_created' => current_time('Y-m-d H:i:s'),
        'date_modified' => current_time('Y-m-d H:i:s'),
        'status' => 'active',
        'ical_hash' => wpsbc_generate_ical_hash(),
    );

    // Insert calendar into the database
    $calendar_id = wpsbc_insert_calendar($calendar_data);

    // If the calendar could not be inserted show a message to the user
    if (!$calendar_id) {

        wpsbc_admin_notices()->register_notice('calendar_insert_false', '<p>' . __('Something went wrong. Could not create the calendar. Please try again.', 'wp-simple-booking-calendar') . '</p>', 'error');
        wpsbc_admin_notices()->display_notice('calendar_insert_false');

        return;

    }

    /**
     * Add default legend items
     *
     */

    $legend_items_data = wpsbc_get_default_legend_items_data();

    foreach ($legend_items_data as $legend_item_data) {

        // Set the calendar id for the legend items data
        $legend_item_data['calendar_id'] = $calendar_id;

        // Insert legend item
        $legend_id = wpsbc_insert_legend_item($legend_item_data);

        // Add default translations
        if(isset($legend_item_data['translations'])) {
            foreach ($legend_item_data['translations'] as $language_code => $legend_translation) {
                wpsbc_add_legend_item_meta($legend_id, 'translation_' . $language_code, $legend_translation);
            }
        }

    }

    /**
     * Add legend items from another calendar
     *
     */
    if (!empty($_POST['calendar_legend'])) {

        $copy_calendar_id = absint($_POST['calendar_legend']);
        $copy_calendar_legend_items = wpsbc_get_legend_items(array('calendar_id' => $copy_calendar_id));

        if (!empty($copy_calendar_legend_items)) {

            foreach ($copy_calendar_legend_items as $legend_item) {

                // Prepare data
                $copy_legend_item_data = $legend_item->to_array();
                $copy_legend_item_data['calendar_id'] = $calendar_id;

                // Unset the legend item id from the array
                unset($copy_legend_item_data['id']);

                $copy_legend_item_id = $legend_item->get('id');

                // Insert the new legend item
                $legend_item_id = wpsbc_insert_legend_item($copy_legend_item_data);

                if (!$legend_item_id) {
                    continue;
                }

                // Get all meta from the copy calendar legend items
                $copy_legend_item_meta = wpsbc_get_legend_item_meta($copy_legend_item_id);

                if (empty($copy_legend_item_meta)) {
                    continue;
                }

                foreach ($copy_legend_item_meta as $meta_key => $meta_values) {

                    foreach ($meta_values as $meta_value) {
                        wpsbc_add_legend_item_meta($legend_item_id, $meta_key, $meta_value);
                    }

                }

            }

        }

    }

    // Redirect to the edit page of the calendar with a success message
    wp_redirect(add_query_arg(array('page' => 'wpsbc-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'wpsbc_message' => 'calendar_insert_success'), admin_url('admin.php')));
    exit;

}
add_action('wpsbc_action_add_calendar', 'wpsbc_action_add_calendar', 50);

/**
 * Handles the trash calendar action, which changes the status of the calendar from active to trash
 *
 */
function wpsbc_action_trash_calendar()
{

    // Verify for nonce
    if (empty($_GET['wpsbc_token']) || !wp_verify_nonce($_GET['wpsbc_token'], 'wpsbc_trash_calendar')) {
        return;
    }

    if (empty($_GET['calendar_id'])) {
        return;
    }

    $calendar_id = absint($_GET['calendar_id']);

    $calendar_data = array(
        'status' => 'trash',
    );

    $updated = wpsbc_update_calendar($calendar_id, $calendar_data);

    if (!$updated) {
        return;
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpsbc-calendars', 'calendar_status' => 'active', 'wpsbc_message' => 'calendar_trash_success'), admin_url('admin.php')));
    exit;

}
add_action('wpsbc_action_trash_calendar', 'wpsbc_action_trash_calendar', 50);

/**
 * Handles the restore calendar action, which changes the status of the calendar from trash to active
 *
 */
function wpsbc_action_restore_calendar()
{

    // Verify for nonce
    if (empty($_GET['wpsbc_token']) || !wp_verify_nonce($_GET['wpsbc_token'], 'wpsbc_restore_calendar')) {
        return;
    }

    if (empty($_GET['calendar_id'])) {
        return;
    }

    $calendar_id = absint($_GET['calendar_id']);

    $calendar_data = array(
        'status' => 'active',
    );

    $updated = wpsbc_update_calendar($calendar_id, $calendar_data);

    if (!$updated) {
        return;
    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpsbc-calendars', 'calendar_status' => 'trash', 'wpsbc_message' => 'calendar_restore_success'), admin_url('admin.php')));
    exit;

}
add_action('wpsbc_action_restore_calendar', 'wpsbc_action_restore_calendar', 50);

/**
 * Handles the delete calendar action, which removes all calendar data, legend items and events data
 * associated with the calendar
 *
 */
function wpsbc_action_delete_calendar()
{

    // Verify for nonce
    if (empty($_GET['wpsbc_token']) || !wp_verify_nonce($_GET['wpsbc_token'], 'wpsbc_delete_calendar')) {
        return;
    }

    if (empty($_GET['calendar_id'])) {
        return;
    }

    $calendar_id = absint($_GET['calendar_id']);

    /**
     * Delete the calendar
     *
     */
    $deleted = wpsbc_delete_calendar($calendar_id);

    if (!$deleted) {
        return;
    }

    /**
     * Delete legend items
     *
     */
    $legend_items = wpsbc_get_legend_items(array('calendar_id' => $calendar_id));

    foreach ($legend_items as $legend_item) {

        wpsbc_delete_legend_item($legend_item->get('id'));

    }

    /**
     * Delete legend items meta
     *
     */
    foreach ($legend_items as $legend_item) {

        $legend_item_meta = wpsbc_get_legend_item_meta($legend_item->get('id'));

        if (!empty($legend_item_meta)) {

            foreach ($legend_item_meta as $key => $value) {

                wpsbc_delete_legend_item_meta($legend_item->get('id'), $key);

            }

        }

    }

    /**
     * Delete events
     *
     */
    $events = wpsbc_get_events(array('calendar_id' => $calendar_id));

    foreach ($events as $event) {

        wpsbc_delete_event($event->get('id'));

    }

    /**
     * Delete events meta
     *
     */
    foreach ($events as $event) {

        $event_meta = wpsbc_get_legend_item_meta($event->get('id'));

        if (!empty($event_meta)) {

            foreach ($event_meta as $key => $value) {

                wpsbc_delete_event_meta($event->get('id'), $key);

            }

        }

    }

    // Redirect to the current page
    wp_redirect(add_query_arg(array('page' => 'wpsbc-calendars', 'calendar_status' => 'trash', 'wpsbc_message' => 'calendar_delete_success'), admin_url('admin.php')));
    exit;

}
add_action('wpsbc_action_delete_calendar', 'wpsbc_action_delete_calendar', 50);
