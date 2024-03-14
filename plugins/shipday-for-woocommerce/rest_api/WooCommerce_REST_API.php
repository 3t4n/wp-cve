<?php

require_once dirname(__DIR__). '/functions/common.php';

class WooCommerce_REST_API {

	public static function init() {
		add_action('shipday_settings_updated', __CLASS__ . '::register_in_server');
	}

	public static function is_consumer_secret_valid($consumer_secret) {
		global $wpdb;
		$rest_api_key = $wpdb->get_row(
			$wpdb->prepare(
				"
					SELECT consumer_key, consumer_secret, permissions
					FROM {$wpdb->prefix}woocommerce_api_keys
					WHERE  consumer_secret = %s
					  and permissions = 'read_write'
				",
				$consumer_secret
			),
			ARRAY_A
		);
		return !is_null($rest_api_key);
	}

    public static function str_ends_with( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }

    public static function is_consumer_keys_valid($consumer_key, $consumer_secret) {
        global $wpdb;
        $rest_api_key = $wpdb->get_row(
            $wpdb->prepare(
                "
					SELECT consumer_key, consumer_secret, truncated_key, permissions
					FROM {$wpdb->prefix}woocommerce_api_keys
					WHERE  consumer_secret = %s
					  and permissions = 'read_write'
				",
                $consumer_secret
            ),
            ARRAY_A
        );
        return !is_null($rest_api_key) && self::str_ends_with($consumer_key, $rest_api_key['truncated_key']);
    }

	public static function register_in_server() {
//		$key_size = 43;
		$consumer_key = trim(get_option('wc_settings_tab_shipday_rest_api_consumer_key'));
		$consumer_secret = trim(get_option('wc_settings_tab_shipday_rest_api_consumer_secret'));
		if (is_null($consumer_key) ||
            is_null($consumer_secret) ||
            !self::is_consumer_keys_valid($consumer_key, $consumer_secret)
        ){
            shipday_logger('info', 'Rest api key: invalid keys');
            delete_option('wc_settings_tab_shipday_registered_uuid');
            return;
        }
		$uuid = self::post_payload($consumer_key, $consumer_secret);
		if (is_null($uuid)) {
            shipday_logger('info', 'Rest api key: null uuid');
            delete_option('wc_settings_tab_shipday_registered_uuid');
            return;
        }
//        delete_option('wc_settings_tab_shipday_rest_api_consumer_key');
//        delete_option('wc_settings_tab_shipday_rest_api_consumer_secret');

        update_option('wc_settings_tab_shipday_registered_uuid', $uuid);
	}

	public static function post_payload($key, $secret) {
		$url              = get_rest_url();

		$curl             = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL            => get_shipday_rest_key_install_url(),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'POST',
				CURLOPT_POSTFIELDS     => '{
  						"url": "' . $url . '",
  						"consumer_key": "' . $key . '",
  						"consumer_secret": "' . $secret . '"
						}',
				CURLOPT_HTTPHEADER     => array(
					'Authorization: Basic '. get_shipday_api_key(),
					'Content-Type: application/json',
				),
			)
		);

		$response        = curl_exec($curl);
		if (is_null($response)) return null;
		$response_decoded = json_decode($response);
		if (!isset($response_decoded->success)) return null;
		$uuid            = $response_decoded->uuid;
		return $uuid;
	}
}