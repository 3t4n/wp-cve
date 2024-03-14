<?php
/**
 * Widget Class.
 *
 * @package    Rock_Convert\Inc\Admin\Widget
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin\Widget;

use Rock_Convert\Inc\Admin\Subscriber;
/**
 * Subscribe form class
 *
 * @package Rock_Content
 * @since 1.0.0
 */
class Subscribe_Form {

	/**
	 * Callback from subscribe form;
	 *
	 * Here if the email and post_id are valid it will:
	 *  * Store email in database (via popup either)
	 *  * Send to RD_Station if is integrated
	 *  * Send to Hubspot if is integrated
	 *  * Redirect to the page back
	 *
	 * @since 2.1.0
	 */
	public function subscribe_form_callback() {
		if ( ! isset( $_POST['rock_convert_subscribe_nonce'] )
				&& wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_POST['rock_convert_subscribe_nonce'] ) ),
					'rock_convert_subscribe_nonce'
				)
					) {
			return;
		}

		$url = isset( $_POST['rock_convert_subscribe_page'] ) ?
			esc_url_raw(
				wp_unslash( $_POST['rock_convert_subscribe_page'] )
			) : null;

		if ( ( get_option( '_rock_convert_g_site_key' ) && get_option( '_rock_convert_g_secret_key' ) ) &&
				isset( $_POST['g-recaptcha-response'] )
		) {
			$g_response = $this->recaptcha_response();

			if ( $g_response->success ) {
				$this->save_email( $url );
				$status = $this->save_email_status( 'recaptcha' );
			}
		} else {
			$this->save_email( $url );
			$status = $this->save_email_status( 'success' );
		}

		$redirect_id = isset( $_POST['rock_convert_subscribe_redirect_page'] ) ?
		sanitize_text_field( wp_unslash( $_POST['rock_convert_subscribe_redirect_page'] ) ) : 0;

		if ( is_int( $redirect_id ) ) {
			$redirect_url = get_permalink( get_post( $redirect_id ) );
			$this->redirect( $redirect_url );
			exit;
		}

		if ( $status ) {
			$this->redirect( add_query_arg( $status, $url ) );
		}

		$this->redirect( home_url() );
		exit;
	}

	/**
	 * Recaptcha response
	 *
	 * @return mixed
	 */
	public function recaptcha_response() {
		if ( ! isset( $_POST['rock_convert_subscribe_nonce'] )
				&& wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_POST['rock_convert_subscribe_nonce'] ) ),
					'rock_convert_subscribe_nonce'
				)
					) {
			return;
		}

		$remote_addr = isset( $_SERVER['REMOTE_ADDR'] ) ?
						sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : null;
		$response    = isset( $_POST['g-recaptcha-response'] ) ?
						sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : null;
		$secret      = get_option( '_rock_convert_g_secret_key' );
		$remoteip    = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ?
						sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) : $remote_addr;

		$response = wp_remote_get(
			add_query_arg(
				array(
					'secret'   => $secret,
					'response' => $response,
					'remoteip' => $remoteip,
				),
				'https://www.google.com/recaptcha/api/siteverify'
			)
		);

		$json = json_decode( $response['body'] );
		if ( is_wp_error( $response ) || empty( $response['body'] ) || ! ( $json ) || ! $json->success ) {
			return new \WP_Error(
				'validation-error',
				__( 'A verificação "Você é humano?" falhou. Por favor, tente novamente.', 'rock-convert' )
			);
		}

		return $json;
	}

	/**
	 * Save e-mail
	 *
	 * @param string $url URL.
	 * @return void
	 */
	public function save_email( $url ) {
		/**
		 * Nonce was checked in the previous function.
		 */
		$email        = isset( $_POST['rock_convert_subscribe_email'] ) ? sanitize_email( wp_unslash( $_POST['rock_convert_subscribe_email'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification
		$post_id      = isset( $_POST['rock_get_current_post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['rock_get_current_post_id'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification
		$name_field   = isset( $_POST['rock_convert_subscribe_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rock_convert_subscribe_name'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification
		$custom_field = isset( $_POST['rock_convert_subscribe_custom_field'] ) ? sanitize_text_field( wp_unslash( $_POST['rock_convert_subscribe_custom_field'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification
		$subscriber   = new Subscriber( $email, $post_id, $url, $name_field, $custom_field );

		if ( ! $subscriber->subscribe( 'rock-convert-' . get_bloginfo( 'name' ) ) ) {
			$this->save_email_status( 'error' );
		}
	}

	/**
	 * Status E-mail.
	 *
	 * @param string $status Define a satus to delivered e-mail.
	 * @return void|array
	 */
	public function save_email_status( $status ) {
		if ( ! isset( $_POST['rock_convert_subscribe_nonce'] )
				&& wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_POST['rock_convert_subscribe_nonce'] ) ),
					'rock_convert_subscribe_nonce'
				)
					) {
			return;
		}

		$popup_send = isset( $_POST['popup_send'] ) ? sanitize_text_field( wp_unslash( $_POST['popup_send'] ) ) : null;

		if ( ! $popup_send ) {
			if ( 'error' === $status ) {
				$status = array( 'error' => 'rc-subscribe-email-invalid#rock-convert-alert-box' );
			} elseif ( 'success' === $status ) {
				$status = array( 'success' => 'rc-subscribed#rock-convert-alert-box' );
			} elseif ( 'recaptcha' === $status ) {
				$status = array( 'recaptcha' => 'rc-recaptcha-invalid#rock-convert-alert-box' );
			}
		} else {
			$status = null;
		}

		return $status;
	}

	/**
	 * Redirect
	 *
	 * @param string $path URL to be redirected.
	 * @since    2.0.0
	 */
	public function redirect( $path ) {
		wp_safe_redirect( esc_url_raw( $path ) );
	}
}
