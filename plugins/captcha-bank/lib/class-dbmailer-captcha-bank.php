<?php
/**
 * This file is used for Sending mails.
 *
 * @author  Tech Banker
 * @package captcha-bank3./lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! class_exists( 'Dbmailer_Captcha_Bank' ) ) {
	/**
	 * Class Name: Dbmailer_Captcha_Bank
	 * Parameters: No
	 * Description: This Class is used for mailing operations.
	 * Created On: 31-08-2016 15:39
	 * Created By: Tech Banker Team
	 */
	class Dbmailer_Captcha_Bank {
		/**
		 * This function is used to send mail for IP address.
		 *
		 * @param string $meta_data_array .
		 * @param string $controls_data_array .
		 */
		public function ip_address_mail_command_captcha_bank( $meta_data_array, $controls_data_array ) {
			if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations-frontend.php' ) ) {
				include CAPTCHA_BANK_DIR_PATH . 'includes/translations-frontend.php';
			}
			$ip = $controls_data_array['ip_address'];

			$datetime = date_i18n( 'd M Y h:i A' );

			$to                 = $meta_data_array['send_to'];
			$subject            = $meta_data_array['email_subject'];
			$message            = '<div style="font-family: Calibri;">';
			$message           .= $meta_data_array['email_message'];
			$message           .= '</div>';
			$replace_url        = str_replace( '[site_url]', site_url(), $message );
			$replace_date_time  = str_replace( '[date_time]', $datetime, $replace_url );
			$replace_ip_address = str_replace( '[ip_address]', long2ip_captcha_bank( $ip ), $replace_date_time );
			$replace_resource   = str_replace( '[resource]', isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', $replace_ip_address );// WPCS: Input var ok, sanitization ok.
			switch ( $controls_data_array['blocked_for'] ) {
				case '1Hour':
					$blocked_for = $cpb_for . $cpb_one_hour;
					break;

				case '12Hour':
					$blocked_for = $cpb_for . $cpb_twelve_hours;
					break;

				case '24hours':
					$blocked_for = $cpb_for . $cpb_twenty_four_hours;
					break;

				case '48hours':
					$blocked_for = $cpb_for . $cpb_forty_eight_hours;
					break;

				case 'week':
					$blocked_for = $cpb_for . $cpb_one_week;
					break;

				case 'month':
					$blocked_for = $cpb_for . $cpb_one_month;
					break;

				case 'permanently':
					$blocked_for = $cpb_permanently;
					break;
			}
			$replace_blocked_for = str_replace( '[blocked_for]', $blocked_for, $replace_resource );
			$email_cc            = $meta_data_array['email_cc'];
			$email_bcc           = $meta_data_array['email_bcc'];

			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $email_cc ) {
				$headers .= 'Cc: ' . $email_cc . "\r\n";
			}
			if ( '' !== $email_bcc ) {
				$headers .= 'Bcc: ' . $email_bcc . "\r\n";
			}
			wp_mail( $to, $subject, $replace_blocked_for, $headers );
		}
		/**
		 * This function is used to send mail for IP range.
		 *
		 * @param string $meta_data_array passes parameter as meta data array.
		 * @param string $controls_data_array passes parameter as controls data array.
		 */
		public function ip_range_mail_command_captcha_bank( $meta_data_array, $controls_data_array ) {
			if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations-frontend.php' ) ) {
				include CAPTCHA_BANK_DIR_PATH . 'includes/translations-frontend.php';
			}
			$ip_range = explode( ',', $controls_data_array['ip_range'] );

			$datetime = date_i18n( 'd M Y h:i A' );

			$to                     = $meta_data_array['send_to'];
			$subject                = $meta_data_array['email_subject'];
			$message                = '<div style="font-family: Calibri;">';
			$message               .= $meta_data_array['email_message'];
			$message               .= '</div>';
			$replace_url            = str_replace( '[site_url]', site_url(), $message );
			$replace_start_ip_range = str_replace( '[start_ip_range]', long2ip_captcha_bank( $ip_range[0] ), $replace_url );
			$replace_end_ip_range   = str_replace( '[end_ip_range]', long2ip_captcha_bank( $ip_range[1] ), $replace_start_ip_range );
			$replace_date_time      = str_replace( '[date_time]', $datetime, $replace_end_ip_range );
			$replace_ip_range       = str_replace( '[ip_range]', long2ip_captcha_bank( $ip_range[0] ) . '-' . long2ip_captcha_bank( $ip_range[1] ), $replace_date_time );
			switch ( $controls_data_array['blocked_for'] ) {
				case '1Hour':
					$blocked_for = $cpb_for . $cpb_one_hour;
					break;

				case '12Hour':
					$blocked_for = $cpb_for . $cpb_twelve_hours;
					break;

				case '24hours':
					$blocked_for = $cpb_for . $cpb_twenty_four_hours;
					break;

				case '48hours':
					$blocked_for = $cpb_for . $cpb_forty_eight_hours;
					break;

				case 'week':
					$blocked_for = $cpb_for . $cpb_one_week;
					break;

				case 'month':
					$blocked_for = $cpb_for . $cpb_one_month;
					break;

				case 'permanently':
					$blocked_for = $cpb_permanently;
					break;
			}
			$replace_blocked_for = str_replace( '[blocked_for]', $blocked_for, $replace_ip_range );
			$send_message        = str_replace( '[resource]', isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', $replace_blocked_for );// WPCS: Input var ok, sanitization ok.
			$email_cc            = $meta_data_array['email_cc'];
			$email_bcc           = $meta_data_array['email_bcc'];

			$headers  = '';
			$headers .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $email_cc ) {
				$headers .= 'Cc: ' . $email_cc . "\r\n";
			}
			if ( '' !== $email_bcc ) {
				$headers .= 'Bcc: ' . $email_bcc . "\r\n";
			}
			wp_mail( $to, $subject, $send_message, $headers );
		}
		/**
		 * This function is used to send mail for login success and failure.
		 *
		 * @param string $template_success_data passes parameter as template success.
		 * @param string $username passes parameter as username.
		 */
		public function login_mail_command_captcha_bank( $template_success_data, $username ) {
			$datetime = date_i18n( 'd M Y h:i A' );

			$userdata   = get_user_by( 'login', $username );
			$ip         = get_ip_address_for_captcha_bank();
			$ip_address = '::1' === $ip ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $ip ) );

			$to                 = $template_success_data['send_to'];
			$subject            = $template_success_data['email_subject'];
			$message            = '<div style="font-family: Calibri;">';
			$message           .= $template_success_data['email_message'];
			$message           .= '</div>';
			$replace_url        = str_replace( '[site_url]', site_url(), $message );
			$replace_username   = str_replace( '[username]', $username, $replace_url );
			$replace_date_time  = str_replace( '[date_time]', $datetime, $replace_username );
			$replace_ip_address = str_replace( '[ip_address]', long2ip_captcha_bank( $ip_address ), $replace_date_time );
			$replace_resource   = str_replace( '[resource]', isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', $replace_ip_address );// WPCS: Input var ok, sanitization ok.
			$email_cc           = $template_success_data['email_cc'];
			$email_bcc          = $template_success_data['email_bcc'];
			$headers            = '';
			$headers           .= 'Content-Type: text/html; charset= utf-8' . "\r\n";
			if ( '' !== $email_cc ) {
				$headers .= 'Cc: ' . $email_cc . "\r\n";
			}
			if ( '' !== $email_bcc ) {
				$headers .= 'Bcc: ' . $email_bcc . "\r\n";
			}
			wp_mail( $to, $subject, $replace_resource, $headers );
		}
	}
}
