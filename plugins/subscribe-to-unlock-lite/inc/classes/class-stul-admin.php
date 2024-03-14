<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('STUL_Admin')) {

    class STUL_Admin extends STUL_Library {

        function __construct() {
            add_action('admin_menu', array($this, 'add_stul_menu'));
            /**
             * Subscribers export to csv
             */
            add_action('admin_post_stul_export_csv', array($this, 'export_to_csv'));
        }

        function add_stul_menu() {
            add_menu_page(esc_html__('Subscribe to Unlock Lite', 'subscribe-to-unlock-lite'), esc_html__('Subscribe to &nbsp;Unlock Lite', 'subscribe-to-unlock-lite'), 'manage_options', 'stul-settings', array($this, 'settings_page'), 'dashicons-unlock');
            add_submenu_page('stul-settings', esc_html__('Settings', 'subscribe-to-unlock-lite'), esc_html__('Settings', 'subscribe-to-unlock-lite'), 'manage_options', 'stul-settings', array($this, 'settings_page'));
            add_submenu_page('stul-settings', esc_html__('Subscribers', 'subscribe-to-unlock-lite'), esc_html__('Subscribers', 'subscribe-to-unlock-lite'), 'manage_options', 'stul-subscribers', array($this, 'generate_subscribers_list'));
            add_submenu_page('stul-settings', esc_html__('Help', 'subscribe-to-unlock-lite'), esc_html__('Help', 'subscribe-to-unlock-lite'), 'manage_options', 'stul-help', array($this, 'render_help_page'));
            add_submenu_page('stul-settings', esc_html__('About', 'subscribe-to-unlock-lite'), esc_html__('About', 'subscribe-to-unlock-lite'), 'manage_options', 'stul-about', array($this, 'render_about_page'));
        }

        function generate_subscribers_list() {
            include(STUL_PATH . 'inc/views/backend/subscribers/list-subscribers.php');
        }

        function export_to_csv() {
            if (!empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'stul_export_csv_nonce')) {
                $filename = "subscribers-list.csv";
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $filename);
                $csv_rows = $this->get_subscriber_csv_rows();

                /**
                 * Filters csv rows
                 *
                 * @param array $csv_rows
                 *
                 * @since 1.0.0
                 */
                $csv_rows = apply_filters('stul_csv_rows', $csv_rows);

                $csv_output = fopen('php://output', 'w');
                foreach ($csv_rows as $csv_row) {
                    fputcsv($csv_output, $csv_row);
                }

                exit;
            } else {
                $this->permission_denied();
            }
        }

        function get_subscriber_csv_rows() {
            global $wpdb;
            $subscriber_table = STUL_SUBSCRIBERS_TABLE;
            $form_table = STUL_FORM_TABLE;

            $subscriber_query = "select * from $subscriber_table";

            $subscriber_rows = $wpdb->get_results($subscriber_query);
            $csv_rows = array();
            $csv_rows[] = array(esc_html__('Subscriber Name', 'subscribe-to-unlock-lite'), esc_html__('Subscriber Email', 'subscribe-to-unlock-lite'), esc_html__('Verified', 'subscribe-to-unlock-lite'));
            if (!empty($subscriber_rows)) {
                foreach ($subscriber_rows as $subscriber_row) {
                    $verification_status = (!empty($subscriber_row->subscriber_verification_status)) ? esc_html__('Yes', 'subscribe-to-unlock-lite') : esc_html__('No', 'subscribe-to-unlock-lite');
                    $csv_row = array($subscriber_row->subscriber_name, $subscriber_row->subscriber_email, $verification_status);
                    $csv_rows[] = $csv_row;
                }
            }
            return $csv_rows;
        }

        function settings_page() {
            include(STUL_PATH . 'inc/views/backend/stul-settings.php');
        }

        function render_help_page() {
            include(STUL_PATH . 'inc/views/backend/stul-help.php');
        }

        function render_about_page() {
            include(STUL_PATH . 'inc/views/backend/stul-about.php');
        }

    }

    new STUL_Admin();
}