<?php

defined('ABSPATH') or die('No script kiddies please!!');

if ($this->ajax_nonce_verify()) {
    /**
     * Triggers just before processing the subscription form
     *
     * @since 1.0.0
     */
    do_action('stul_before_form_process');
    $form_data = $_POST['form_data'];
    parse_str($form_data, $form_data);
    $form_data = $this->sanitize_array($form_data);
    $form_alias = sanitize_text_field($_POST['form_alias']);
    $form_details = get_option('stul_settings');
    $subscriber_name = (!empty($form_data['stul_name'])) ? $form_data['stul_name'] : '';
    if (empty($form_data['stul_email']) || (!empty($form_details['form']['terms_agreement']['show']) && empty($form_data['stul_terms_agreement'])) || (!empty($form_details['form']['name']['show']) && !empty($form_details['form']['name']['required']) && empty($form_data['stul_name']))) {
        $response['status'] = 403;
        $response['message'] = esc_attr($form_details['general']['required_error_message']);
    } else {
        //It is okay to process the form now
        $subscriber_row = $this->get_subscriber_row_by_email($form_data['stul_email']);
        $from_email = (!empty($form_details['email']['from_email'])) ? $form_details['email']['from_email'] : $this->get_default_from_email();
        $from_name = (!empty($form_details['email']['from_name'])) ? $form_details['email']['from_name'] : esc_html__('No Reply', 'subscribe-to-unlock-lite');
        $subject = (!empty($form_details['email']['subject'])) ? $form_details['email']['subject'] : esc_html__('Subscription Successful', 'subscribe-to-unlock-lite');
        $email_message = (!empty($form_details['email']['email_message'])) ? $form_details['email']['email_message'] : $this->get_default_email_message();

        $charset = get_option('blog_charset');
        $headers[] = 'Content-Type: text/html; charset=' . $charset;
        $headers[] = "From: $from_name <$from_email>";

        if (empty($subscriber_row)) {
            //if subscriber isn't already subscribed
            $unlock_key = $this->generate_unlock_key();
            global $wpdb;
            $wpdb->insert(STUL_SUBSCRIBERS_TABLE, array('subscriber_name' => $subscriber_name,
                'subscriber_email' => $form_data['stul_email'],
                'subscriber_form_alias' => $form_alias,
                'subscriber_unlock_key' => $unlock_key), array('%s', '%s', '%s', '%s'));
        } else {
            //if subscriber is already subscribed
            $unlock_key = $subscriber_row->subscriber_unlock_key;
        }
        if (!empty($form_details['general']['verification'])) {
            switch ($form_details['general']['verification_type']) {
                case 'link':
                    $unlock_link = site_url() . '?stul_unlock_key=' . $unlock_key;
                    $email_message = str_replace('#unlock_link', '<a href="' . $unlock_link . '">' . $unlock_link . '</a>', $email_message);
                    break;
                case 'unlock_code':
                    $email_message = str_replace('#unlock_code', $unlock_key, $email_message);
                    break;
            }
            $email_check = wp_mail($form_data['stul_email'], $subject, $email_message, $headers);
            if ($email_check) {
                $unlock_code = $unlock_key;
                setcookie("stul_unlock_key", $unlock_key, time() + 3600 * 24 * 365, '/');
                $response['status'] = 200;
                $response['message'] = esc_attr($form_details['general']['success_message']);
                $response['verification_type'] = $form_details['general']['verification_type'];
                $response['unlock_key'] = $unlock_code;
            } else {
                $response['status'] = 403;
                $response['message'] = esc_attr($form_details['general']['error_message']);
            }
        } else {
            setcookie("stul_unlock_key", $unlock_key, time() + 3600 * 24 * 365, '/');
            setcookie('stul_unlock_check', 'yes', time() + (86400 * 30 * 365), "/");
            $response['status'] = 200;
            $response['message'] = esc_attr($form_details['general']['success_message']);
            $response['verification_type'] = 'none';
            $response['unlock_key'] = $unlock_key;
        }
        /**
         * Triggers at the end of processing the subscription form successfully
         *
         * @param array $form_data
         *
         * @since 1.0.0
         */
        do_action('stul_end_form_process', $form_data, $form_details);
    }

    die(json_encode($response));
} else {
    $this->permission_denied();
}
