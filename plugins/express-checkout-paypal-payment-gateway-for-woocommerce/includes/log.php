<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Eh_PayPal_Log {

	public static function init_log() {
		$content = '<------------------- WebToffee PayPal Express Payment Log File ( ' . EH_PAYPAL_VERSION . " ) ------------------->\n";
		return $content;
	}

	public static function remove_data( $incoming_data ) {
		$data = (array) $incoming_data;

		if ( apply_filters( 'wt_paypal_show_sensitive_data', false ) === true ) {
			return $data;
		} else {
			unset( $data['USER'] );
			unset( $data['PWD'] );
			unset( $data['SIGNATURE'] );
			unset( $data['VERSION'] );
			unset( $data['client_id'] );
			unset( $data['client_secret'] );
			unset( $data['access_token'] );
			return $data;
		}
	}

	public static function log_update( $mg, $title, $type = null ) {
		$check = get_option( 'woocommerce_eh_paypal_express_settings' );
		if ( 'json' == $type ) {
			$resp = self::remove_data( json_decode( $mg, true ) );
			$msg  = wp_json_encode( $resp, JSON_PRETTY_PRINT );
		} else {
			$msg = self::remove_data( $mg );
		}
		if ( 'yes' === $check['paypal_logging'] ) {
			if ( WC()->version >= '2.7.0' ) {
				$log  = wc_get_logger();
				$head = '<------------------- WebToffee PayPal Express Payment ( ' . $title . " ) ------------------->\n";
				if ( 'json' == $type ) {
					$log_text = $head . print_r( $msg, true );
				} else {
					 $log_text = $head . print_r( (object) $msg, true );
				}
				$context = array( 'source' => 'eh_paypal_express_log' );
				$log->log( 'debug', $log_text, $context );
			} else {
				$log  = new WC_Logger();
				$head = '<------------------- WebToffee PayPal Express Payment ( ' . $title . " ) ------------------->\n";
				if ( 'json' == $type ) {
					$log_text = $head . print_r( $msg, true );
				} else {
					$log_text = $head . print_r( (object) $msg, true );
				}                $log->add( 'eh_paypal_express_log', $log_text );
			}
		}
	}

}
