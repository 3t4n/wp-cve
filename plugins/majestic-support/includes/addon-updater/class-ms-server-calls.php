<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MJTC_SupportTicketServerCalls extends MJTC_SUPPORTTICKETUpdater{

	private static $server_url = 'https://majesticsupport.com/setup/index.php';

	public static function MJTC_PluginUpdateCheck($token_arrray_json) {
		$args = array(
			'request' => 'pluginupdatecheck',
			'token' => $token_arrray_json,
			'domain' => site_url()
		);

		$url = self::$server_url . '?' . http_build_query( $args, '', '&' );
		$request = wp_remote_get($url);

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'pluginupdatecheck case returned error';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}

		$response = wp_remote_retrieve_body( $request );
		$response = json_decode($response);

		if ( is_object( $response ) ) {
			return $response;
		} else {
			$error_message = 'pluginupdatecheck case returned data which was not correct';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}

	public static function MJTC_PluginUpdateCheckFromCDN() {

		$url = "http://d2k6fm08zy0hmd.cloudfront.net/addonslatestversions.txt";
		$request = wp_remote_get($url);

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'pluginupdatecheck cdn case returned error';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}

		$response = wp_remote_retrieve_body( $request );
		$response = json_decode($response);

		if ( is_object( $response ) ) {
			return $response;
		} else {
			$error_message = 'pluginupdatecheck cdn case returned data which was not correct';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}

	public static function MJTC_GenerateToken($transaction_key,$addon_name) {
			$args = array(
				'request' => 'generatetoken',
				'transactionkey' => $transaction_key,
				'productcode' => $addon_name,
				'domain' => site_url()
			);

			$url = self::$server_url . '?' . http_build_query( $args, '', '&' );
			$request = wp_remote_get($url);
			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				$error_message = 'generatetoken case returned error';
				MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
				return array('error'=>$error_message);
			}

			$response = wp_remote_retrieve_body( $request );
			$response = json_decode($response,true);

			if ( is_array( $response ) ) {
				return $response;
			} else {
				$error_message = 'generatetoken case returned data which was not correct';
				MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
				return array('error'=>$error_message);
			}
			return false;
		}


	public static function MJTC_GetLatestVersions() {
		$args = array(
				'request' => 'getlatestversions'
			);
		$request = wp_remote_get( 'https://majesticsupport.com/appsys/addoninfo/index.php' . '?' . http_build_query( $args, '', '&' ) );

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'getlatestversions case returned error';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}

		$response = wp_remote_retrieve_body( $request );
		$response = json_decode($response,true);
		if ( is_array( $response ) ) {
			return $response;
		} else {
			$error_message = 'getlatestversions case returned data which was not correct';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}

	public static function MJTC_PluginInformation( $args ) {
		$defaults = array(
			'request'        => 'plugininformation',
			'plugin_slug'    => '',
			'version'        => '',
			'token'    => '',
			'domain'          => site_url()
		);

		$args    = wp_parse_args( $args, $defaults );
		$request = wp_remote_get( 'https://majesticsupport.com/appsys/addoninfo/index.php' . '?' . http_build_query( $args, '', '&' ) );

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'plugininformation case returned data error';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}
		$response = wp_remote_retrieve_body( $request );

		$response = json_decode($response);

		if ( is_object( $response ) ) {
			return $response;
		} else {
			$error_message = 'plugininformation case returned data which is not correct';
			MJTC_includer::MJTC_getModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}
}
