<?php

defined('ABSPATH') or die('No script kiddies please!!');

if ($this->ajax_nonce_verify()) {
    /**
     * Triggers just before processing the subscription form
     *
     * @since 1.0.0
     */
    do_action('wpsf_before_form_process');
    $form_data = $_POST['form_data'];
    parse_str($form_data, $form_data);
    $form_data = $this->sanitize_array($form_data);
    $form_alias = sanitize_text_field($_POST['form_alias']);
    $form_row = $this->get_form_row_by_alias($form_alias);
    $form_details = maybe_unserialize($form_row->form_details);
    $subscriber_name = (!empty($form_data['wpsf_name'])) ? sanitize_text_field($form_data['wpsf_name']) : '';
    $subscriber_email = sanitize_email($form_data['wpsf_email']);
    if (empty($form_data['wpsf_email']) || (!empty($form_details['form']['terms_agreement']['show']) && empty($form_data['wpsf_terms_agreement'])) || (!empty($form_details['form']['name']['show']) && !empty($form_details['form']['name']['required']) && empty($form_data['wpsf_name']))) {
        $response['status'] = 403;
        $response['message'] = esc_attr($form_details['general']['required_error_message']);
    } else {
        //It is okay to process the form now

        if (!empty($form_details['general']['double_optin'])) {
            setcookie("wpsf_name", $subscriber_name, time() + 24 * 365, '/');
            setcookie("wpsf_email", $subscriber_email, time() + 24 * 365, '/');
            setcookie("wpsf_alias", $form_alias, time() + 24 * 365, '/');


            $from_email = (!empty($form_details['email']['from_email'])) ? $form_details['email']['from_email'] : $this->get_default_from_email();
            $from_name = (!empty($form_details['email']['from_name'])) ? $form_details['email']['from_name'] : esc_html__('No Reply', 'wp-subscription-forms');
            $confirmation_subject = (!empty($form_details['email']['confirmation_subject'])) ? $form_details['email']['confirmation_subject'] : esc_html__('Subscription Confirmation', 'wp-subscription-forms');
            $confirmation_email_message = (!empty($form_details['email']['confirmation_email_message'])) ? $form_details['email']['confirmation_email_message'] : $this->get_default_confirmation_email_message();
            $confirmation_verification_key = md5($subscriber_email);
            $confirmation_verification_link = site_url() . '/?wpsf_subscription_confirmation=true&confirmation_verification_key=' . $confirmation_verification_key;
            $confirmation_email_message = str_replace('#confirmation_link', $confirmation_verification_link, $confirmation_email_message);
            $charset = get_option('blog_charset');
            $headers[] = 'Content-Type: text/html; charset=' . $charset;
            $headers[] = "From: $from_name <$from_email>";
            $email_check = wp_mail($subscriber_email, $confirmation_subject, $confirmation_email_message, $headers);
            if ($email_check) {
                $response['status'] = 200;
                $response['type'] = esc_html__('Subscription Confirmation', 'wp-subscription-forms');
                $response['confirmation_verification_link'] = esc_url($confirmation_verification_link);
                $response['message'] = esc_html($form_details['general']['success_message']);
            } else {
                $response['status'] = 403;
                $response['message'] = esc_html($form_details['general']['error_message']);
            }
        } else {
            include(WPSF_PATH . 'inc/cores/subscribe-action.php');
        }
    }

    die(json_encode($response));
} else {
    $this->permission_denied();
}
