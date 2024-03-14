<?php

$subscriber_row = $this->get_subscriber_row_by_email($form_data['wpsf_email']);


if (empty($subscriber_row)) {
    //if subscriber isn't already subscribed

    global $wpdb;
    $wpdb->insert(WPSF_SUBSCRIBERS_TABLE, array('subscriber_name' => $subscriber_name,
        'subscriber_email' => $form_data['wpsf_email'],
        'subscriber_form_alias' => $form_alias), array('%s', '%s', '%s', '%s'));
    $response['status'] = 200;
    $response['message'] = esc_attr($form_details['general']['success_message']);
} else {
    //if subscriber is already subscribed

    $response['status'] = 200;
    $response['type'] = esc_html__('Already subscribed', 'wp-subscription-forms');
    $response['message'] = esc_attr($form_details['general']['success_message']);
}
/**
 * Triggers at the end of processing the subscription form successfully
 *
 * @param array $form_data
 *
 * @since 1.0.0
 */
do_action('wpsf_end_form_process', $form_data, $form_details);
