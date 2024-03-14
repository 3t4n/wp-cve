<?php

defined( 'ABSPATH' ) or die();

class SubscribeFormAjax
{
    public static function subscribe_form_action() {
        check_ajax_referer( 'subscribe_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['email'] ) ) {
            $email  = sanitize_email( $_POST['email'] );

            if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                wp_send_json( array( 'status' => 'error', 'message' => __( 'Please enter valide email.!', WL_COMPANION_DOMAIN ) ) );
            } else {
                $admin_email = get_option( 'admin_email' );
                $headers     = array( 'Content-Type: text/html; charset=UTF-8' );
                $subject     = __( 'You have new subscriber', WL_COMPANION_DOMAIN );

                $message = 'New subscriber\'s email:- '.$email;

                $enquerysend = wp_mail( $admin_email, $subject, $message, $headers );
                if ( $enquerysend ) {
                    wp_send_json( array( 'status' => 'success', 'message' => __( 'Mail sent successfully.!', WL_COMPANION_DOMAIN ) ) );
                } else {
                    wp_send_json( array( 'status' => 'error', 'message' => __( 'Mail not sent successfully.!', WL_COMPANION_DOMAIN ) ) );
                }
            }

		} else {
            if ( empty ( $_POST['email'] ) ) {
                wp_send_json( array( 'status' => 'error', 'message' => __( 'Please enter email.!', WL_COMPANION_DOMAIN ) ) );
            } else {
                wp_send_json( array( 'status' => 'error', 'message' => __( 'Something went wrong.!', WL_COMPANION_DOMAIN ) ) );
            }

		}
		wp_die();
    }
}