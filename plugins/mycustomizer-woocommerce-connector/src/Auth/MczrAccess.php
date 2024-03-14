<?php

namespace MyCustomizer\WooCommerce\Connector\Auth;

use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

class MczrAccess {

	public static function isAuthorized() {
		if ( ! defined( 'ABSPATH' ) ) {
			exit( 'You cannot access this file directly (ABSPATH is not defined)' );
		}
	}

	public static function isAPIAuthorized() {
		$settings         = new MczrSettings();
		$authorizationKey = $settings->get( 'authorizationKey' );

		if (!function_exists('apache_request_headers')) {
			function apache_request_headers() {
				$arh = array();
				$rx_http = '/\AHTTP_/';
				foreach ($_SERVER as $key => $val) {
					if (preg_match($rx_http, $key)) {
						$arh_key = preg_replace($rx_http, '', $key);
						$rx_matches = array();
						$rx_matches = explode('_', $arh_key);
						if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
							foreach ($rx_matches as $ak_key => $ak_val) {
								$rx_matches[$ak_key] = ucfirst($ak_val);
							}
							$arh_key = implode('-', $rx_matches);
						}
						$arh[$arh_key] = $val;
					}
				}
				return( $arh );
			}
		}
		$headers = apache_request_headers();

		if (function_exists('phpversion')
			&& ( version_compare(phpversion(), '7', '>=') )
			&& !isset($headers['Authorization'])
			&& !isset($headers['authorization'])
			&& isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
			$headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
		}

		$isAuthorized = false;

		foreach ( $headers as $key => $value ) {
			if ( 'Authorization' === $key || 'authorization' === $key ) {
				if ( $authorizationKey === $value ) {
					$isAuthorized = true;
				}
			}
		}

		if ( ! $isAuthorized ) {
			http_response_code( 401 );
			exit;
		}
	}

	public static function can( $do ) {
		if ( ! current_user_can( $do ) ) {
			wp_die( esc_attr__( '[MczrConnectorAjax] You do not have sufficient permissions to access this page.' ) );
		}
	}
}
