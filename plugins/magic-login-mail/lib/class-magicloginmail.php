<?php
/**
 * Magic Login Mail
 *
 * @package    MagicLoginMail
 * @subpackage MagicLoginMail Main
/*  Copyright (c) 2021- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$magicloginmail = new MagicLoginMail();

/** ==================================================
 * MagicLoginMail Library
 */
class MagicLoginMail {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'autologin_via_url' ) );
		add_action( 'magic_email_send', array( $this, 'send_link' ), 10, 2 );
		add_shortcode( 'magic_login', array( $this, 'front_end_login' ) );
	}

	/** ==================================================
	 * Shortcode for the passwordless login form
	 *
	 * @return html
	 * @since 1.00
	 */
	public function front_end_login() {

		$html = null;

		$account_email = null;
		if ( isset( $_POST['magic-submit'] ) ) {
			if ( ! empty( $_POST['nonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
				if ( wp_verify_nonce( $nonce, 'magic_login_request' ) ) {
					if ( ! empty( $_POST['magic_user_email'] ) ) {
						$account_email = sanitize_text_field( wp_unslash( $_POST['magic_user_email'] ) );
						$email_arr[0] = $account_email;
						$this->send_link( $email_arr, false );
					}
				}
			}
		}

		$error_token = false;
		if ( isset( $_GET['magic_login_mail_error_token'] ) ) {
			$error_token = sanitize_key( $_GET['magic_login_mail_error_token'] );
		}

		if ( ! is_null( $account_email ) &&
			! in_array( $account_email, get_option( 'magic_login_mail_valid_errors', array() ) ) &&
			! in_array( $account_email, get_option( 'magic_login_mail_email_errors', array() ) ) &&
			! isset( $_GET['magic_login_mail_error_token'] ) ) {
			$magic_login_mail_success_link_msg = apply_filters( 'magic_login_mail_success_link_msg', __( 'Please check your email. You will soon receive an email with a login link.', 'magic-login-mail' ) );
			$magic_login_mail_success_link_msg_back_color = apply_filters( 'magic_login_mail_success_link_msg_back_color', '#e7f7d3' );
			$html .= '<p style="background-color: ' . esc_attr( $magic_login_mail_success_link_msg_back_color ) . ';">' . esc_html( $magic_login_mail_success_link_msg ) . '</p>';
		} elseif ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$magic_login_mail_success_login_msg_back_color = apply_filters( 'magic_login_mail_success_login_msg_back_color', '#e7f7d3' );
			$magic_login_mail_user_redirect = apply_filters( 'magic_login_mail_user_redirect', admin_url(), $current_user->ID );
			/* translators: %1$s current_page or redirect page link %2$s logout link  */
			$magic_login_mail_success_login_msg = apply_filters( 'magic_login_mail_success_login_msg', sprintf( __( 'You are currently logged in as %1$s. %2$s', 'magic-login-mail' ), '<a href="' . esc_url( $magic_login_mail_user_redirect ) . '" title="' . $current_user->display_name . '">' . $current_user->display_name . '</a>', '<a href="' . wp_logout_url( $this->curpageurl() ) . '" title="' . __( 'Log out of this account', 'magic-login-mail' ) . '">' . __( 'Log out', 'magic-login-mail' ) . ' &raquo;</a>' ) );
			$html .= '<p style="background-color: ' . esc_attr( $magic_login_mail_success_login_msg_back_color ) . ';">' . wp_kses_post( $magic_login_mail_success_login_msg ) . '</p><!-- .alert-->';
		} else {
			if ( in_array( $account_email, get_option( 'magic_login_mail_valid_errors', array() ) ) ) {
				$magic_login_mail_valid_errors_back_color = apply_filters( 'magic_login_mail_valid_errors_back_color', '#ffebe8' );
				$magic_login_mail_valid_errors = apply_filters( 'magic_login_mail_valid_errors', __( 'The email you provided do not exist. Please try again.', 'magic-login-mail' ) );
				$html .= '<p style="background-color: ' . esc_attr( $magic_login_mail_valid_errors_back_color ) . ';">' . esc_html( $magic_login_mail_valid_errors ) . '</p>';
			}
			if ( in_array( $account_email, get_option( 'magic_login_mail_email_errors', array() ) ) ) {
				$magic_login_mail_email_errors_back_color = apply_filters( 'magic_login_mail_email_errors_back_color', '#ffebe8' );
				$magic_login_mail_email_errors = apply_filters( 'magic_login_mail_email_errors', __( 'Failed to send email.', 'magic-login-mail' ) );
				$html .= '<p style="background-color: ' . esc_attr( $magic_login_mail_email_errors_back_color ) . ';">' . esc_html( $magic_login_mail_email_errors ) . '</p>';
			}
			if ( $error_token ) {
				$magic_login_mail_invalid_token_error_back_color = apply_filters( 'magic_login_mail_invalid_token_error_back_color', '#ffebe8' );
				$magic_login_mail_invalid_token_error = apply_filters( 'magic_login_mail_invalid_token_error', __( 'Your token has probably expired. Please try again.', 'magic-login-mail' ) );
				$html .= '<p style="background-color: ' . esc_attr( $magic_login_mail_invalid_token_error_back_color ) . ';">' . esc_html( $magic_login_mail_invalid_token_error ) . '</p>';
			}

			$form_class_name = apply_filters( 'magic_login_mail_form_class_name', null );
			$label = apply_filters( 'magic_login_mail_form_label', __( 'Login with email', 'magic-login-mail' ) );
			$label_class_name = apply_filters( 'magic_login_mail_label_class_name', null );
			$input_class_name = apply_filters( 'magic_login_mail_input_class_name', null );
			$input_size = apply_filters( 'magic_login_mail_input_size', 17 );
			$submit_class_name = apply_filters( 'magic_login_mail_submit_class_name', null );
			$html .= '<form action="' . get_the_permalink() . '" method="post"  class="' . esc_attr( $form_class_name ) . '">';
			$html .= '<p>';
			$html .= '<label for="magic_user_email" class="' . esc_attr( $label_class_name ) . '">' . esc_html( $label ) . '</label>';
			$html .= '<input type="text" inputmode="url" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" name="magic_user_email" id="magic_user_email" class="' . esc_attr( $input_class_name ) . '" value="' . esc_attr( $account_email ) . '" size="' . esc_attr( $input_size ) . '" placeholder="' . esc_attr__( 'Email' ) . '" required="required" />';
			$html .= '<input type="submit" name="magic-submit" id="magic-submit" class="' . esc_attr( $submit_class_name ) . '" value="' . esc_attr__( 'Log In', 'magic-login-mail' ) . '" />';
			$html .= '</p>';
			$html .= wp_nonce_field( 'magic_login_request', 'nonce' );
			$html .= '</form>';

		}

		return $html;
	}

	/** ==================================================
	 * Magic link login
	 *
	 * @since 1.00
	 */
	public function autologin_via_url() {

		if ( isset( $_GET['token'] ) && isset( $_GET['uid'] ) ) {

			$uid = intval( sanitize_key( $_GET['uid'] ) );
			$token = sanitize_key( $_GET['token'] );

			$hash_meta = get_user_meta( $uid, 'magic_login_mail_' . $uid, true );
			$hash_meta_expiration = get_user_meta( $uid, 'magic_login_mail_' . $uid . '_expiration', true );
			$arr_params = array( 'uid', 'token' );
			$current_page_url = remove_query_arg( $arr_params, $this->curpageurl() );

			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
			$time = time();

			if ( ! $wp_hasher->CheckPassword( $token . $hash_meta_expiration, $hash_meta ) || $hash_meta_expiration < $time ) {
				$url = add_query_arg( 'magic_login_mail_error_token', 'true', $current_page_url );
				wp_redirect( $url );
				exit;
			} else {
				wp_set_auth_cookie( $uid );
				delete_user_meta( $uid, 'magic_login_mail_' . $uid );
				delete_user_meta( $uid, 'magic_login_mail_' . $uid . '_expiration' );
				wp_redirect( apply_filters( 'magic_login_mail_after_login_redirect', $current_page_url, $uid ) );
				exit;
			}
		}
	}

	/** ==================================================
	 * Returns the current page URL
	 *
	 * @return string
	 * @since 1.00
	 */
	private function curpageurl() {

		$current_url = get_the_permalink();

		if ( ! $current_url ) {
			if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) ) {
				$current_url = 'https://';
			} else {
				$current_url = 'http://';
			}
			if ( isset( $_SERVER['HTTP_HOST'] ) && ! empty( $_SERVER['HTTP_HOST'] ) ) {
				$current_url .= sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
				if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
					$current_url .= esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
				}
			}
		}

		return $current_url;
	}

	/** ==================================================
	 * Sends an email with the unique login link.
	 *
	 * @param array $email_arr  email's.
	 * @param bool  $admin_batch  Management screen batch sent.
	 * @since 1.00
	 */
	public function send_link( $email_arr, $admin_batch ) {

		delete_option( 'magic_login_mail_valid_errors' );
		delete_option( 'magic_login_mail_email_errors' );
		delete_option( 'magic_login_mail_email_success' );

		$valid_error = array();
		$success_mail = array();
		$error_mail = array();

		foreach ( $email_arr as $email ) {

			$valid_email = $this->valid_account( $email );

			if ( ! $valid_email ) {
				$valid_error[] = $email;
			} else {
				$blog_name = get_bloginfo( 'name' );

				/* Filters to change the content type of the email */
				add_filter(
					'wp_mail_content_type',
					function () {
						return 'text/html';
					}
				);

				$unique_url = $this->generate_url( $valid_email );
				/* translators: %s blogname  */
				$subject = apply_filters( 'magic_login_mail_subject', sprintf( __( 'Login at %s', 'magic-login-mail' ), $blog_name ) );
				/* translators: %1$s blogname %2$s loginurl %3$s loginurl  */
				$message = apply_filters( 'magic_login_mail_message', sprintf( __( 'Hello ! <br><br>Login at %1$s by visiting this url: <a href="%2$s">%3$s</a>', 'magic-login-mail' ), $blog_name, esc_url( $unique_url ), esc_url( $unique_url ) ) );
				$sent_mail = wp_mail( $valid_email, $subject, $message );

				if ( ! $sent_mail ) {
					$error_mail[] = $valid_email;
				} else {
					$success_mail[] = $valid_email;
				}
			}
		}

		update_option( 'magic_login_mail_valid_errors', $valid_error );
		update_option( 'magic_login_mail_email_errors', $error_mail );
		update_option( 'magic_login_mail_email_success', $success_mail );

		if ( is_admin() && $admin_batch ) {
			$state_html = null;
			if ( ! empty( $valid_error ) ) {
				/* translators: %1$s emails */
				$state_html .= '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( sprintf( __( 'The email[%1$s] you provided do not exist. Please try again.', 'magic-login-mail' ), implode( ',', $valid_error ) ) ) . '</li></ul></div>';
			}
			if ( ! empty( $success_mail ) ) {
				/* translators: %1$s emails */
				$state_html .= '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Sent email[%1$s].', 'magic-login-mail' ), implode( ',', $success_mail ) ) ) . '</li></ul></div>';
			}
			if ( ! empty( $error_mail ) ) {
				/* translators: %1$s emails */
				$state_html .= '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Failed to send email[%1$s].', 'magic-login-mail' ), implode( ',', $error_mail ) ) ) . '</li></ul></div>';
			}
			$allowed_sent_mail_html = array(
				'div'   => array(
					'class' => array(),
				),
				'ul' => array(),
				'li' => array(),
			);
			echo wp_kses( $state_html, $allowed_sent_mail_html );
		}
	}

	/** ==================================================
	 * Check if the account is valid from the email address.
	 *
	 * @param string $email  email.
	 * @return string / bool
	 * @since 1.00
	 */
	private function valid_account( $email ) {

		$valid_email = sanitize_email( $email );
		if ( is_email( $valid_email ) && email_exists( $valid_email ) ) {
			return $valid_email;
		}

		return false;
	}

	/** ==================================================
	 * Generates unique URL based on UID and token
	 *
	 * @param string $email  email.
	 * @since 1.00
	 */
	private function generate_url( $email ) {

		/* get user id */
		$user = get_user_by( 'email', $email );
		$token = $this->create_onetime_token( 'magic_login_mail_' . $user->ID, $user->ID );

		$arr_params = array( 'magic_login_mail_error_token', 'uid', 'token' );
		$url = apply_filters( 'magic_login_mail_url', remove_query_arg( $arr_params, $this->curpageurl() ) );

		$url_params = array(
			'uid' => $user->ID,
			'token' => $token,
		);
		$url = add_query_arg( $url_params, $url );

		return $url;
	}

	/** ==================================================
	 * Create a nonce like token that you only use once based on transients
	 *
	 * @param string $action  action.
	 * @param int    $user_id  user_id.
	 * @return string
	 * @since 1.00
	 */
	private function create_onetime_token( $action, $user_id ) {

		$time = time();

		/* random salt */
		$key = wp_generate_password( 20, false );

		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
		$string = $key . $action . $time;

		/* we're sending this to the user */
		$token  = wp_hash( $string );
		$expiration = $time + 60 * apply_filters( 'magic_login_mail_expiration', 10 );
		$expiration_action = $action . '_expiration';

		/* we're storing a combination of token and expiration */
		$stored_hash = $wp_hasher->HashPassword( $token . $expiration );

		update_user_meta( $user_id, $action, $stored_hash );
		/* adjust the lifetime of the token. Currently 10 min. */
		update_user_meta( $user_id, $expiration_action, $expiration );

		return $token;
	}
}
