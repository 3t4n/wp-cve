<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_woo_image_seo_send_feedback', function() {
    check_ajax_referer( 'woo_image_seo_send_feedback' );

    if (
        empty( $_POST['message'] )
        ||
        empty( $_POST['email'] )
    ) {
        wp_send_json_error( 'missing required fields' );
    }

    // this data is sent only after the user's agreement :)
    $message = 'Email: ' . esc_html( $_POST['email'] );
    $message .= '<br>Message: ' . esc_html( $_POST['message'] );
    $message .= '<br>Website: ' . home_url();
    $message .= '<br>Woo Image SEO version: ' . WOO_IMAGE_SEO['version'];
    $message .= '<br>WooCommerce version: ' . WC()->version;

    wp_send_json(
        wp_mail(
            'emandiev@gmail.com',
            'Woo Image SEO Plugin Feedback',
            $message,
            [
                'From: Woo Image SEO <emandiev@gmail.com>',
                'Content-Type: text/html; charset=UTF-8',
            ]
        )
    );
} );
