<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * OTP class to manege otp request
 *
 * @since      1.1.0
 * @package    Payamito
 * @subpackage Payamito/includes
 * @author     payamito <payamito@gmail.com>
 */
if ( ! class_exists( 'Payamito_OTP' ) ) {
	class Payamito_OTP
	{
		/**
		 * generate otp code
		 *
		 * @since    1.1.0
		 */
		public static function payamito_generate_otp( int $count_otp )
		{
			if ( $count_otp > 10 || $count_otp < 4 ) {
				$count_otp = $count_otp ? $count_otp < 10 : 4;
			}
			$count_otp = intval( $count_otp ) - 1;
			$OTP       = null;
			$min       = "1";
			$max       = "9";
			while ( $count_otp ) {
				$min = $min . '1';
				$max = $max . '9';
				-- $count_otp;
			}

			$OTP = rand( $min, $max );

			return $OTP;
		}

		/**
		 * set session  otp code
		 *
		 * @since    1.1.0
		 */
		public static function payamito_set_session( string $mobile, string $OTP,$prefix = 'payamito')
		{
			set_transient($prefix.'_'.$mobile,['time'=>time(),'otp'=>$OTP],300);
		}
		public static function payamito_get_session( string $mobile,$prefix = 'payamito' )
		{
			return get_transient($prefix.'_'.$mobile);
		}

		public static function payamito_delete_session( string $mobile,$prefix = 'payamito' )
		{
			delete_transient($prefix.'_'.$mobile);
		}

		/**
		 * validation session otp code
		 *
		 * @since    1.1.0
		 */
		public static function payamito_validation_session( string $mobile, string $OTP ,$prefix='payamito')
		{
			$session=self::payamito_get_session($mobile,$prefix);	
			if ( empty($session)) {
				return false;
			}
			if ( $session['otp'] == $OTP ) {
				return true;
			}

			return false;
		}

		/**
		 *check resend otp request
		 *
		 * @since    1.1.0
		 */
		public static function payamito_resent_time_check( $mobile, $time = 0 )
		{
			if ( current_user_can( "manage_options" ) ) {
				return;
			}
			$session=self::payamito_get_session($mobile);
			if ( ! empty($session)) {
				return;
			}
			$period_send = (int) $time;
			$time_send   = (int) $session['time'];
			$R           = time() - $time_send;
			if ( $R < $period_send ) {
				die;
			}
		}

	}
}
