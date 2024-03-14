<?php

use AbsoluteAddons\MailChimp;
use AbsoluteAddons\Settings\Dashboard;

function absp_post_like_action_callback() {
	if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'absp-like' ) ) {
		$post_id         = ! empty( $_REQUEST['post_id'] ) ? absint( sanitize_text_field( $_REQUEST['post_id'] ) ) : 0;
		$current_user_id = get_current_user_id();

		$liked_user_ids = get_post_meta( $post_id, '_absp_like_user_ids', true );
		$liked_user_ids = empty( $liked_user_ids ) ? [] : $liked_user_ids;

		$type = 'like';

		if ( in_array( $current_user_id, $liked_user_ids ) ) {
			$liked_user_ids = array_diff( $liked_user_ids, [ $current_user_id ] );
			$type           = 'dislike';
		} else {
			array_push( $liked_user_ids, $current_user_id );
		}

		$liked_user_ids = array_unique( $liked_user_ids );


		$updated = update_post_meta( $post_id, '_absp_like_user_ids', $liked_user_ids );

		$response_array = [
			'post_id' => $post_id,
			'type'    => $type,
			'updated' => $updated,
		];

		wp_send_json( $response_array );
	}

	die();
}

function absp_handle_mailchimp_subscribe() {
	check_ajax_referer( 'absp-frontend' );

	if ( isset( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
		$email             = sanitize_email( $_POST['email'] );
		$mailchimp_options = Dashboard::get_tab_section_option( 'integrations', 'mailchimp' );
		if ( isset( $mailchimp_options['api_key'] ) && $mailchimp_options['api_key'] ) {
			try {
				$mailchimp_client = new MailChimp( $mailchimp_options['api_key'], true );
				if ( isset( $_POST['list'] ) && ! empty( $_POST['list'] ) ) {
					$list = sanitize_text_field( $_POST['list'] );
				} else {
					$list = $mailchimp_options['audience_list'];
				}
				$mailchimp_client->set_active_list( $list );
				$response = $mailchimp_client->subscribe( $email );
				if ( is_wp_error( $response ) ) {
					wp_send_json_error( [
						'message' => $response->get_error_message(),
						'error'   => $response,
					] );
				} else {
					wp_send_json_success( [ 'message' => esc_html__( 'Thank you for subscribing.', 'absolute-addons' ) ] );
				}
			} catch ( InvalidArgumentException $e ) {
				wp_send_json_success( [ 'message' => $e->getMessage() ] );
			}
		} else {
			if ( current_user_can( 'manage_options' ) ) {
				wp_send_json_success( [ 'message' => esc_html__( 'Mailchimp api is not configured.', 'absolute-addons' ) ] );
			} else {
				wp_send_json_success( [ 'message' => esc_html__( 'Something Went Wrong. Please contact site admin.', 'absolute-addons' ) ] );
			}
		}
		die();
	}

	wp_send_json_error( [ 'message' => esc_html__( 'Email Is Required.', 'absolute-addons' ) ] );
	die();
}

add_action( 'wp_ajax_absp_post_like_action', 'absp_post_like_action_callback' );
add_action( 'wp_ajax_absp-mailchimp-subscribe', 'absp_handle_mailchimp_subscribe' );
add_action( 'wp_ajax_nopriv_absp-mailchimp-subscribe', 'absp_handle_mailchimp_subscribe' );

// End of file ajax-handler.php.
