<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPSBC_Submenu_Page_Calendars extends WPSBC_Submenu_Page
{

    /**
     * Helper init method that runs on parent __construct
     *
     */
    protected function init()
    {

        add_action('admin_init', array($this, 'register_admin_notices'), 10);

    }

    /**
     * Callback method to register admin notices that are sent via URL parameters
     *
     */
    public function register_admin_notices()
    {

        if (empty($_GET['wpsbc_message'])) {
            return;
        }

        // Calendar insert success
        wpsbc_admin_notices()->register_notice('calendar_insert_success', '<p>' . __('Calendar created successfully.', 'wp-simple-booking-calendar') . '</p>');

        // Calendar updated successfully
        wpsbc_admin_notices()->register_notice('calendar_update_success', '<p>' . __('Calendar updated successfully.', 'wp-simple-booking-calendar') . '</p>');

        // Calendar updated fail
        wpsbc_admin_notices()->register_notice('calendar_update_fail', '<p>' . __('Something went wrong. Could not update the calendar.', 'wp-simple-booking-calendar') . '</p>', 'error');

        // Calendar trash success
        wpsbc_admin_notices()->register_notice('calendar_trash_success', '<p>' . __('Calendar successfully moved to Trash.', 'wp-simple-booking-calendar') . '</p>');

        // Calendar restore success
        wpsbc_admin_notices()->register_notice('calendar_restore_success', '<p>' . __('Calendar has been successfully restored.', 'wp-simple-booking-calendar') . '</p>');

        // Calendar delete success
        wpsbc_admin_notices()->register_notice('calendar_delete_success', '<p>' . __('Calendar has been successfully deleted.', 'wp-simple-booking-calendar') . '</p>');

        // Legend item insert success
        wpsbc_admin_notices()->register_notice('legend_item_insert_success', '<p>' . __('Legend item created successfully.', 'wp-simple-booking-calendar') . '</p>');

        // Legend item update success
        wpsbc_admin_notices()->register_notice('legend_item_update_success', '<p>' . __('Legend item updated successfully.', 'wp-simple-booking-calendar') . '</p>');

        // Legend item delete success
        wpsbc_admin_notices()->register_notice('legend_item_delete_success', '<p>' . __('Legend item deleted successfully.', 'wp-simple-booking-calendar') . '</p>');

        // Legend items sort fail
        wpsbc_admin_notices()->register_notice('sort_legend_items_fail', '<p>' . __('Something went wrong. Could not sort the legend items.', 'wp-simple-booking-calendar') . '</p>', 'error');

        // Legend item make visible/invisible
        if ($_GET['wpsbc_message'] == 'legend_item_make_visible_success' || $_GET['wpsbc_message'] == 'legend_item_make_invisible_success') {

            $legend_item_id = absint($_GET['legend_item_id']);
            $legend_item = wpsbc_get_legend_item($legend_item_id);

            wpsbc_admin_notices()->register_notice('legend_item_make_visible_success', '<p>' . sprintf(__('Legend item %s is now visible.', 'wp-simple-booking-calendar'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');
            wpsbc_admin_notices()->register_notice('legend_item_make_invisible_success', '<p>' . sprintf(__('Legend item %s is now hidden.', 'wp-simple-booking-calendar'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');

        }

        // Legend item make default
        if ($_GET['wpsbc_message'] == 'legend_item_make_default_success') {

            $legend_item_id = absint($_GET['legend_item_id']);
            $legend_item = wpsbc_get_legend_item($legend_item_id);

            wpsbc_admin_notices()->register_notice('legend_item_make_default_success', '<p>' . sprintf(__('%s legend item is now the default one.', 'wp-simple-booking-calendar'), '<strong>' . $legend_item->get('name') . '</strong>') . '</p>');

        }

    }

    /**
     * Callback for the HTML output for the Calendar page
     *
     */
    public function output()
    {

        if (empty($this->current_subpage)) {
            include 'views/view-calendars.php';
        } else {

            if ($this->current_subpage == 'add-calendar') {
                if (count(wpsbc_get_calendars()) > 0) {
                    include WPSBC_PLUGIN_DIR . 'includes/base/admin/upgrade-to-premium.php';
                } else {
                    include 'views/view-add-calendar.php';
                }

            }

            if ($this->current_subpage == 'edit-calendar') {
                include 'views/view-edit-calendar.php';
            }

            if ($this->current_subpage == 'view-legend') {
                include WPSBC_PLUGIN_DIR . 'includes/base/admin/upgrade-to-premium.php';
            }

            if ($this->current_subpage == 'ical-import-export') {
                include WPSBC_PLUGIN_DIR . 'includes/base/admin/upgrade-to-premium.php';
            }

            if ($this->current_subpage == 'upgrade-to-premium') {
                include WPSBC_PLUGIN_DIR . 'includes/base/admin/upgrade-to-premium.php';
            }

            if ($this->current_subpage == 'csv-export') {
                include WPSBC_PLUGIN_DIR . 'includes/base/admin/upgrade-to-premium.php';
            }

        }

    }

}
