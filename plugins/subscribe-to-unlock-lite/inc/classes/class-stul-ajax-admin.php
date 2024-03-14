<?php

defined('ABSPATH') or die('No script kiddies please!!');



if (!class_exists('STUL_Ajax_Admin')) {

    class STUL_Ajax_Admin extends STUL_Library {

        function __construct() {

            /**
             * Settings save ajax
             */
            add_action('wp_ajax_stul_settings_save_action', array($this, 'settings_save_action'));
            add_action('wp_ajax_nopriv_stul_settings_save_action', array($this, 'permission_denied'));

            /**
             * Subscriber delete ajax
             *
             */
            add_action('wp_ajax_stul_subscriber_delete_action', array($this, 'subscriber_delete_action'));
            add_action('wp_ajax_nopriv_stul_subscriber_delete_action', array($this, 'permission_denied'));
        }

        function settings_save_action() {
            if ($this->admin_ajax_nonce_verify()) {
                $_POST = stripslashes_deep($_POST);
                $form_data = $_POST['form_data'];

                parse_str($form_data, $form_data);
                $sanitize_rule = array('agreement_text' => 'html', 'text' => 'html', 'email_message' => 'to_br', 'footer_text' => 'html', 'lock_content' => 'html', 'unlock_link_message' => 'to_br');
                $form_data = $this->sanitize_array($form_data, $sanitize_rule);
                $stul_settings = $form_data['form_details'];
                update_option('stul_settings', $stul_settings);
                $response['status'] = 200;
                $response['message'] = esc_html__('Settings saved successfully.', 'subscribe-to-download-lite');
                echo json_encode($response);
                die();
            } else {
                $this->permission_denied();
            }
        }

        function subscriber_delete_action() {
            if ($this->admin_ajax_nonce_verify()) {
                $subscriber_id = intval($_POST['subscriber_id']);
                global $wpdb;
                $delete_check = $wpdb->delete(STUL_SUBSCRIBERS_TABLE, array('subscriber_id' => $subscriber_id), array('%d'));
                if ($delete_check) {
                    $response['status'] = 200;
                    $response['message'] = esc_html__('Subscriber deleted successfully', 'subscribe-to-unlock');
                } else {
                    $response['status'] = 403;
                    $response['message'] = esc_html__('There occurred some error. Please try again later.', 'subscribe-to-unlock');
                }
                echo json_encode($response);
                die();
            } else {
                $this->permission_denied();
            }
        }

    }

    new STUL_Ajax_Admin();
}
