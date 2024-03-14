<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Config\MczrConfig;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

class MczrConnect {

	const TOKEN_FIELD_NAME = 'apiToken';

	public function __construct() {
		$this->settings = new MczrSettings();
	}

	public function getToken() {
		$token = $this->settings->get( self::TOKEN_FIELD_NAME );
		if ( ! $token ) {
			throw new \Exception( 'Could not get token in db nor in class' );
		}
		return $token;
	}

	public function post( $path, $content = array(), $headers = array(), $bearer = true, $retry = true ) {
		if ( $bearer ) {
			$headers['Authorization'] = 'Bearer ' . $this->getToken();
		}
		$headers['Content-Type'] = 'application/json';
		$args                    = array(
			'body'        => json_encode( $content ),
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
			'cookies'     => array(),
		);
		$response                = wp_remote_post( MczrConfig::getInstance()['apiBaseUrl'] . $path, $args );
		if ( is_wp_error( $response ) ) {
			if ( true === $retry ) {
				$this->post( $path, $content, $headers, $bearer, false );
			}
			$error_message = $response->get_error_message();

			try {
				$logger = wc_get_logger();
				$logger->warning("Kickflip Plugin couldn't POST to {$path}. {$error_message}. Please contact us at info@mycustomizer.com", ['source' => 'Kickflip Product Customizer']);
			} catch (Exception $e) {
			}
		} else {
			return $response;
		}
	}

	public function get( $path, $retry = true, $bearer = true ) {
		$headers = array();
		if ( $bearer ) {
			$headers['Authorization'] = 'Bearer ' . $this->getToken();
		}
		$args      = array( 'headers' => $headers );
		$wpget     = wp_remote_get( MczrConfig::getInstance()['apiBaseUrl'] . $path, $args );

		if ( is_wp_error( $wpget ) ) {
			if ( true === $retry ) {
				$this->get( $path, false, $bearer );
			} else {
			  $error_message = $response->get_error_message();
				throw new \Exception( "MyCustomizer Plugin couldn't GET {$path}. {$error_message}. Please contact us at info@mycustomizer.com" );
			}
		} else {
			$body      = wp_remote_retrieve_body( $wpget );
			$http_code = wp_remote_retrieve_response_code( $wpget );
			$json = json_decode( $body );
			return $json;
		}
	}

	public function uninstall( $brand, $onlineStoreId ) {
		try {
			$data = array(
				'status' => 'uninstalled',
			);

			$this->post( "/brands/$brand/onlinestores/$onlineStoreId", $data );

			return true;
		} catch ( \Exception $ex ) {
			error_log( $ex->getMessage() );
			return false;
		}
	}

	public function connect( $brand, $onlineStoreId = null ) {
		try {
			$shopUrl = get_site_url();

			if ( empty( $onlineStoreId ) ) {
				$onlineStores = $this->get( "/brands/$brand/onlinestores" );

				if ( is_array( $onlineStores ) ) {
					foreach ( $onlineStores as $onlineStore ) {
						if ( $onlineStore->url === $shopUrl ) {
							$onlineStoreId = (string) $onlineStore->id;
							break;
						}
					}
				}
			}

			$data = array(
				'name'        => get_bloginfo( 'name' ),
				'currency'    => get_woocommerce_currency(),
				'url'         => get_site_url(),
				'status'      => 'installed',
				'eCommerce'   => 'woocommerce',
				'accessToken' => $this->settings->get( 'authorizationKey' ),
			);

			if ( empty( $onlineStoreId ) ) {
				$response = $this->post( "/brands/$brand/onlinestores", $data );
			} else {
				$response = $this->post( "/brands/$brand/onlinestores/$onlineStoreId", $data );
			}
			$onlineStore = json_decode( $response['body'] );

			$this->settings->updateOne( 'shopId', $onlineStore->id );

			return true;
		} catch ( \Exception $ex ) {
			error_log( $ex->getMessage() );
			return false;
		}
	}
}
