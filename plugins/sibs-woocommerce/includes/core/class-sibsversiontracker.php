<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class SibsVersionTracker {
	
	private static function sibs_get_version_tracker_url() {
		$_version_tracker_url = 'http://api.dbserver.payreto.eu/v1/tracker';
		return $_version_tracker_url;
	}


	private static function sibs_get_response_data( $data, $url ) {
		$response = wp_remote_post(
			$url, array(
				'body'      => $data,
				'sslverify' => false,
			)
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $response );
		return json_decode( $response, true );
	}


	private static function sibs_get_version_tracker_parameter( $version_data ) {
		$data = array(
			'transaction_mode' => $version_data['transaction_mode'],
			'ip_address'       => $version_data['ip_address'],
			'shop_version'     => $version_data['shop_version'],
			'plugin_version'   => $version_data['plugin_version'],
			'client'           => $version_data['client'],
			'hash'             => md5( $version_data['shop_version'] . $version_data['plugin_version'] . $version_data['client'] ),
		);

		if ( $version_data['shop_system'] ) {
			$data['shop_system'] = $version_data['shop_system'];
		}
		if ( $version_data['email'] ) {
			$data['email'] = $version_data['email'];
		}
		if ( $version_data['merchant_id'] ) {
			$data['merchant_id'] = $version_data['merchant_id'];
		}
		if ( $version_data['shop_url'] ) {
			$data['shop_url'] = $version_data['shop_url'];
		}

		return $data;
	}


	public static function sibs_send_version_tracker( $version_data ) {
		$post_data = self::sibs_get_version_tracker_parameter( $version_data );
		$url       = self::sibs_get_version_tracker_url();
		return self::sibs_get_response_data( $post_data, $url );
	}
}
