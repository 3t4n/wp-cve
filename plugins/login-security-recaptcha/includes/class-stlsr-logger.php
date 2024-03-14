<?php
defined( 'ABSPATH' ) || die();

class STLSR_Logger {
	public static function log_error( $message, $form, $captcha, $ip_address ) {
		$error_logs = get_option( 'stlsr_error_logs', array() );
		$total_logs = 20;
		if ( ! is_array( $error_logs ) ) {
			$error_logs = array();
		}

		array_push(
			$error_logs,
			array(
				'msg'     => sanitize_text_field( $message ),
				'form'    => sanitize_text_field( $form ),
				'captcha' => sanitize_text_field( $captcha ),
				'ip'      => sanitize_text_field( $ip_address ),
				'time'    => time(),
			)
		);

		if ( count( $error_logs ) > $total_logs ) {
			$error_logs = array_slice( $error_logs, - ( $total_logs ) );
		}

		update_option( 'stlsr_error_logs', $error_logs, true );
	}

	public static function get_error_logs() {
		$error_logs = get_option( 'stlsr_error_logs', array() );
		if ( ! is_array( $error_logs ) ) {
			$error_logs = array();
		}
		return array_reverse( $error_logs );
	}
}
