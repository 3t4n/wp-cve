<?php

namespace WPPayForm\Database\Migrations;

class Pages
{
    public static function create()
    {
        $options = get_option('wppayform_confirmation_pages');
        if (false === $options || ! array_key_exists('confirmation', $options)) {
            $charge_confirmation = wp_insert_post(array(
                'post_title'     => __('Payment Confirmation', 'wp-payment-form'),
                'post_content'   => '[wppayform_reciept]',
                'post_status'    => 'publish',
                'post_author'    => 1,
                'post_type'      => 'page',
                'comment_status' => 'closed',
            ));
            $options['confirmation'] = $charge_confirmation;
        }
        if (false === $options || ! array_key_exists('failed', $options)) {
            $charge_failed = wp_insert_post(array(
                'post_title'     => __('Payment Failed', 'wp-payment-form'),
                /* translators: %s: The [simpay_errors] shortcode */
                'post_content'   => __("We're sorry, but your transaction failed to process. Please try again or contact site support.", 'wp-payment-form'),
                'post_status'    => 'publish',
                'post_author'    => 1,
                'post_type'      => 'page',
                'comment_status' => 'closed',
            ));
            $options['failed'] = $charge_failed;
        }
        update_option('wppayform_confirmation_pages', $options);
    }
}
