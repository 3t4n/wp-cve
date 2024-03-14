<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Admin')) {

    class WPSF_Admin extends WPSF_Library {

        function __construct() {
            add_action('admin_menu', array($this, 'add_wpsf_menu'));
            /**
             * Subscribers export to csv
             */
            add_action('admin_post_wpsf_export_csv', array($this, 'export_to_csv'));
        }

        function add_wpsf_menu() {
            add_menu_page(esc_html__('WP Subscription Forms', 'wp-subscription-forms'), esc_html__('WP Subscription Forms', 'wp-subscription-forms'), 'manage_options', 'wp-subscription-forms', array($this, 'generate_main_page'), 'dashicons-email-alt');
            add_submenu_page('wp-subscription-forms', esc_html__('Subscription Forms', 'wp-subscription-forms'), esc_html__('Subscription Forms', 'wp-subscription-forms'), 'manage_options', 'wp-subscription-forms', array($this, 'generate_main_page'));
            add_submenu_page('wp-subscription-forms', esc_html__('Add Subscription Form', 'wp-subscription-forms'), esc_html__('Add Subscription Form', 'wp-subscription-forms'), 'manage_options', 'add-subscription-form', array($this, 'add_subscription_form'));
            add_submenu_page('wp-subscription-forms', esc_html__('Subscribers', 'wp-subscription-forms'), esc_html__('Subscribers', 'wp-subscription-forms'), 'manage_options', 'wpsf-subscribers', array($this, 'generate_subscribers_list'));
            add_submenu_page('wp-subscription-forms', esc_html__('Help', 'wp-subscription-forms'), esc_html__('Help', 'wp-subscription-forms'), 'manage_options', 'wpsf-help', array($this, 'render_help_page'));
            add_submenu_page('wp-subscription-forms', esc_html__('About', 'wp-subscription-forms'), esc_html__('About', 'wp-subscription-forms'), 'manage_options', 'wpsf-about', array($this, 'render_about_page'));
        }

        function generate_main_page() {
            if (isset($_GET['action'], $_GET['form_id']) && $_GET['action'] == 'edit_form') {
                if (!empty($_GET['form_id'])) {
                    $form_id = intval($_GET['form_id']);
                    $form_row = $this->get_form_row_by_id($form_id);

                    if (empty($form_row)) {
                        echo sprintf(esc_html__("No form found with ID %d", 'wp-subscription-forms'), $form_id);
                    } else {
                        $form_details = maybe_unserialize($form_row->form_details);
                    }
                }
                include(WPSF_PATH . 'inc/views/backend/forms/subscription-form-edit.php');
            } else {
                include(WPSF_PATH . 'inc/views/backend/forms/subscription-forms-list.php');
            }
        }

        function add_subscription_form() {
            include(WPSF_PATH . 'inc/views/backend/forms/subscription-form-add.php');
        }

        function generate_subscribers_list() {
            include(WPSF_PATH . 'inc/views/backend/subscribers/list-subscribers.php');
        }

        function export_to_csv() {
            if (!empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'wpsf_export_csv_nonce')) {
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
                $csv_rows = apply_filters('wpsf_csv_rows', $csv_rows);

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
            $subscriber_table = WPSF_SUBSCRIBERS_TABLE;
            $form_table = WPSF_FORM_TABLE;
            if (!empty($_GET['form_alias'])) {

                $form_alias = sanitize_text_field($_GET['form_alias']);
                $subscriber_query = $wpdb->prepare("select * from $subscriber_table inner join $form_table on $subscriber_table.subscriber_form_alias = $form_table.form_alias where subscriber_form_alias = %s", $form_alias);
            } else {
                $subscriber_query = "select * from $subscriber_table inner join $form_table on $subscriber_table.subscriber_form_alias = $form_table.form_alias";
            }
            $subscriber_rows = $wpdb->get_results($subscriber_query);
            $csv_rows = array();
            $csv_rows[] = array('Subscriber Name', 'Subscriber Email', 'Subscription Form');
            if (!empty($subscriber_rows)) {
                foreach ($subscriber_rows as $subscriber_row) {
                    $download_status = (!empty($subscriber_row->subscriber_download_status)) ? esc_html__('Yes', 'wp-subscription-forms') : esc_html__('No', 'wp-subscription-forms');
                    $csv_row = array($subscriber_row->subscriber_name, $subscriber_row->subscriber_email, $subscriber_row->form_title);
                    $csv_rows[] = $csv_row;
                }
            }
            return $csv_rows;
        }

        function render_help_page() {
            include(WPSF_PATH . 'inc/views/backend/wpsf-help.php');
        }

        function render_about_page() {
            include(WPSF_PATH . 'inc/views/backend/wpsf-about.php');
        }

    }

    new WPSF_Admin();
}