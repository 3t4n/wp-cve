<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Library')) {

    class WPSF_Library {

        /**
         * Exit for unauthorized access
         *
         * @since 1.0.0
         */
        function permission_denied() {
            die('No script kiddies please!!');
        }

        /**
         * Prints array in the pre format
         *
         * @param array $array
         * @since 1.0.0
         */
        function print_array($array) {
            echo "<pre>";
            print_r($array);
            echo "</pre>";
        }

        /**
         * Sanitizes Multi Dimensional Array
         * @param array $array
         * @param array $sanitize_rule
         * @return array
         *
         * @since 1.0.0
         */
        function sanitize_array($array = array(), $sanitize_rule = array()) {
            if (!is_array($array) || count($array) == 0) {
                return array();
            }

            foreach ($array as $k => $v) {
                if (!is_array($v)) {

                    $default_sanitize_rule = (is_numeric($k)) ? 'html' : 'text';
                    $sanitize_type = isset($sanitize_rule[$k]) ? $sanitize_rule[$k] : $default_sanitize_rule;
                    $array[$k] = $this->sanitize_value($v, $sanitize_type);
                }
                if (is_array($v)) {
                    $array[$k] = $this->sanitize_array($v, $sanitize_rule);
                }
            }

            return $array;
        }

        /**
         * Sanitizes Value
         *
         * @param type $value
         * @param type $sanitize_type
         * @return string
         *
         * @since 1.0.0
         */
        function sanitize_value($value = '', $sanitize_type = 'text') {
            switch ($sanitize_type) {
                case 'html':
                    $allowed_html = wp_kses_allowed_html('post');
                    return $this->sanitize_html($value);
                    break;
                case 'to_br':
                    return $this->sanitize_escaping_linebreaks($value);
                    break;
                default:
                    return sanitize_text_field($value);
                    break;
            }
        }

        /**
         * Check if alias has already been used or not
         *
         * @param string $form_alias
         * @param int $form_id
         *
         * @since 1.0.0
         */
        function is_alias_available($form_alias, $form_id = 0) {
            $form_table = WPSF_FORM_TABLE;
            global $wpdb;
            if (empty($form_id)) {
                $alias_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $form_table WHERE form_alias like %s", $form_alias));
            } else {
                $alias_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $form_table WHERE form_alias like %s  AND form_id !=%d", $form_alias, $form_id));
            }
            if ($alias_count == 0) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Ajax nonce verification for ajax in admin
         *
         * @return bolean
         * @since 1.0.0
         */
        function admin_ajax_nonce_verify() {
            if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'wpsf_ajax_nonce')) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Ajax nonce verification for ajax in frontend
         *
         * @return bolean
         * @since 1.0.0
         */
        function ajax_nonce_verify() {
            if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'wpsf_frontend_ajax_nonce')) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Fetches the form row from form table
         *
         * @global object $wpdb
         * @param int $form_id
         * @return object
         */
        public static function get_form_row_by_id($form_id) {
            global $wpdb;
            $table = WPSF_FORM_TABLE;
            $form_row = $wpdb->get_row($wpdb->prepare("select * from $table where form_id = %d", $form_id));
            return $form_row;
        }

        /**
         * Returns the shortcode from alias
         *
         * @param string $alias
         * @return string
         *
         * @since 1.0.0
         */
        function generate_shortcode($alias) {
            $shortcode = "[wp_subscription_forms alias='$alias' ]";
            return $shortcode;
        }

        /**
         * Returns the default email message
         *
         * @return string
         *
         * @since 1.0.0
         */
        function get_default_email_message() {
            $default_email_message = esc_html__(sprintf('Hello There,

Thank you for subscribing in our %s website. Please download the file from below link:

#download_link

Thank you', esc_attr(get_bloginfo('name'))), 'wp-subscription-forms');
            return $default_email_message;
        }

        /**
         * Returns the default confirmation email message
         *
         * @return string
         *
         * @since 1.0.0
         */
        function get_default_confirmation_email_message() {
            $default_email_message = esc_html__(sprintf('Hello There,

Thank you for subscribing in our %s website. Please confirm your subscription from below below link:

#confirmation_link

You will be subscribed to our mailing list after you will click in the above confirmation link.

Thank you', esc_attr(get_bloginfo('name'))), 'wp-subscription-forms');
            return $default_email_message;
        }

        /**
         * Sanitizes the content by bypassing allowed html
         *
         * @param string $text
         * @return string
         *
         * @since 1.0.0
         */
        function sanitize_html($text, $br_omit = false) {
            $allowed_html = wp_kses_allowed_html('post');
            if ($br_omit) {
                unset($allowed_html['br']);
            }
            return wp_kses($text, $allowed_html);
        }

        /**
         * Sanitizes field by converting line breaks to <br /> tags
         *
         * @since 1.0.0
         *
         * @return string $text
         */
        function sanitize_escaping_linebreaks($text) {
            $text = $this->sanitize_html($text, true);
            $text = implode("<br \>", explode("\n", $text));
            return $text;
        }

        /**
         * Outputs by converting <Br/> tags into line breaks
         *
         * @since 1.0.0
         *
         * @return string $text
         */
        function output_converting_br($text) {

            $text = implode("\n", explode("<br \>", $text));
            $text = $this->sanitize_html($text, true);
            return $text;
        }

        /**
         * Gets the form row as per the alias
         *
         * @param string $alias
         *
         * @return object
         *
         * @since 1.0.0
         */
        function get_form_row_by_alias($alias) {
            global $wpdb;
            $form_table = WPSF_FORM_TABLE;
            $form_row = $wpdb->get_row("select * from $form_table where form_alias = '$alias'");
            return $form_row;
        }

        /**
         * Gets the subscriber row from email
         *
         * @param string $email
         *
         * @return object/boolean
         *
         * @since 1.0.0
         */
        function get_subscriber_row_by_email($email) {
            global $wpdb;
            $subscriber_table = WPSF_SUBSCRIBERS_TABLE;
            $subscriber_row = $wpdb->get_row($wpdb->prepare("select * from $subscriber_table where subscriber_email = %s", $email));
            return $subscriber_row;
        }

        /**
         * Returns the default From Email
         *
         * @return string
         *
         * @since 1.0.0
         */
        function get_default_from_email() {
            $site_url = site_url();
            $find_h = '#^http(s)?://#';
            $find_w = '/^www\./';
            $replace = '';
            $output = preg_replace($find_h, $replace, $site_url);
            $output = preg_replace($find_w, $replace, $output);
            return 'noreply@' . $output;
        }

        /**
         * Generates a unique encryption key
         *
         * @return string
         *
         * @since 1.0.0
         */
        function generate_encryption_key() {
            $current_date_time = date('Y-m-d H:i:s');
            $encryption_key = md5($current_date_time);
            return $encryption_key;
        }

        /**
         * Returns download Path
         *
         * @global object $wpdb
         * @param string $encryption_key
         * @return string/boolean
         *
         * @since 1.0.0
         */
        function get_download_path($encryption_key, $form_alias = '') {
            $subscriber_table = WPSF_SUBSCRIBERS_TABLE;
            global $wpdb;
            if (empty($form_alias)) {
                $subscriber_row = $wpdb->get_row($wpdb->prepare("select * from $subscriber_table where subscriber_encryption_key = %s", $encryption_key));
                if (empty($subscriber_row)) {
                    // if encryption key is inavalid
                    return false;
                }
                $form_alias = $subscriber_row->subscriber_form_alias;
            }
            $form_row = $this->get_form_row_by_alias($form_alias);
            if (empty($form_row)) {
                //if form has been deleted or not available in db
                return false;
            }
            $form_details = maybe_unserialize($form_row->form_details);
            if (empty($form_details['general']['download_file_id'])) {
                // If download file is not available in the settings
                return false;
            }
            $attachment_path = get_attached_file(intval($form_details['general']['download_file_id']));
            return $attachment_path;
        }

        /**
         * Returns the file name from download URL
         *
         * @param string $url
         * @return string
         *
         * @since 1.0.0
         */
        function get_file_name_from_url($url) {
            $url = untrailingslashit($url);
            $url_array = explode('/', $url);
            $file_name = end($url_array);
            return $file_name;
        }

        /**
         * Copy form
         *
         * @param int $form_id
         * @return boolean
         *
         * @since 1.0.0
         */
        function copy_form($form_id) {
            global $wpdb;
            $form_row = $this->get_form_row_by_id($form_id);
            $form_alias = $form_row->form_alias . '_' . rand(1111, 9999);
            $copy_check = $wpdb->insert(
                    WPSF_FORM_TABLE, array(
                'form_title' => $form_row->form_title . ' - Copy',
                'form_alias' => $form_alias,
                'form_details' => $form_row->form_details,
                'form_status' => $form_row->form_status
                    ), array('%s', '%s', '%s', '%d'));
            return $copy_check;
        }

        function check_if_already_subscribed($email_address) {
            global $wpdb;
            $subscriber_table = WPSF_SUBSCRIBERS_TABLE;
            $subscriber_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $subscriber_table WHERE subscriber_email like '%s'", $email_address));
            if ($subscriber_count == 0) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * Prints Display None
         *
         * @param string $parameter1
         * @param string $parameter2
         *
         * @since 1.0.0
         */
        function display_none($parameter1, $parameter2) {
            if ($parameter1 != $parameter2) {
                echo 'style="display:none"';
            }
        }

        /**
         * Returns the default opt-in confirmation message
         *
         * @return string
         *
         * @since 1.0.5
         */
        function get_default_optin_confirmation_message() {
            $default_optin_confirmation_message = esc_html__('Congratulations!!

Your email has been verified.

Thank you', 'wp-subscription-forms');
            return $default_optin_confirmation_message;
        }

    }

    $GLOBALS['wpsf_library'] = new WPSF_Library();
}
