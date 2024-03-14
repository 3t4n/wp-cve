<?php
/**
 * Google Analytics notice.
 *
 * @package GoogleAnalytics
 */

/**
 * Notice class.
 */
class Ga_Notice {

	/**
	 * Get translated error message.
	 *
	 * @param string $error Error string.
	 *
	 * @return string Translated error string.
	 */
	public static function get_message( $error ) {
		$message = '';

		if ( Ga_Helper::GA_DEBUG_MODE ) {
			$message = Ga_Helper::ga_wp_notice(
				( ! empty( $error['class'] ) ? esc_html( '[' . $error['class'] . ']' ) : '' ) . ' ' . $error['message'],
				'error'
			);
		} elseif ( 'Ga_Lib_Google_Api_Client_AuthCode_Exception' === $error['class'] ) {
			$message = Ga_Helper::ga_wp_notice( $error['message'], 'error' );
		} elseif ( 'Ga_Lib_Sharethis_Api_Client_InvalidDomain_Exception' === $error['class'] ) {
			$message = Ga_Helper::ga_wp_notice( $error['message'], 'error' );
		} elseif ( 'Ga_Lib_Sharethis_Api_Client_Invite_Exception' === $error['class'] ) {
			$message = Ga_Helper::ga_wp_notice( $error['message'], 'error' );
		} elseif (
			in_array(
				$error['class'],
				array( 'Ga_Lib_Sharethis_Api_Client_Verify_Exception', 'Ga_Lib_Sharethis_Api_Client_Alerts_Exception' ),
				true
			)
		) {
			$message = Ga_Helper::ga_wp_notice( $error['message'], 'error' );
		} elseif ( 'Ga_Data_Outdated_Exception' === $error['class'] ) {
			$message = Ga_Helper::ga_wp_notice( $error['message'], 'warning' );
		} else {
			$message = Ga_Helper::ga_wp_notice(
				__( 'There are temporary connection issues, please try again later or go to Google Analytics website to see the dashboards' ),
				'error'
			);
		}

		return $message;
	}

}
