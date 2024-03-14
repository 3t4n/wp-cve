<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


function job_bm_payment_methods(){

    $methods = array();

    $methods['paypal'] = 'PayPal';
    $methods['2checkout'] = '2Checkout';
    $methods['stripe'] = 'Stripe';

    return apply_filters('job_bm_payment_methods', $methods);

}








function job_bm_ajax_payments(){

    $paymentData = isset($_POST['paymentData']) ? job_bm_recursive_sanitize_arr($_POST['paymentData']) : array();
    //$paymentData = json_decode($paymentData);

    $transaction_id = isset($paymentData['transaction_id']) ? $paymentData['transaction_id'] : '';
    $payment_type = isset($paymentData['payment_type']) ? $paymentData['payment_type'] : '';
    $job_id = isset($paymentData['job_id']) ? $paymentData['job_id'] : '';
    $payment_method = isset($paymentData['payment_method']) ? $paymentData['payment_method'] : '';




    $user_id = get_current_user_id();

    $payment_id = wp_insert_post(
        array(
            'post_title'    => 'Payments',
            'post_type'   	=> 'payment',
            'post_author'   => $user_id,
        )
    );

    update_post_meta($payment_id, 'transaction_id', $transaction_id);
    update_post_meta($payment_id, 'job_id', $job_id);

    do_action('job_bm_payment_submitted', $payment_id, $paymentData);


    die();
}
add_action('wp_ajax_job_bm_ajax_payments', 'job_bm_ajax_payments');
add_action('wp_ajax_nopriv_job_bm_ajax_payments', 'job_bm_ajax_payments');

