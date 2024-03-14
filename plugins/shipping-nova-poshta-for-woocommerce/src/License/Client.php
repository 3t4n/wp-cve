<?php
/**
 * Client for License.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\License;

use NovaPoshta\Main;

/**
 * Class Client
 *
 * @package NovaPoshta\License
 */
class Client {

	/**
	 * Endpoint.
	 */
	const API_ENDPOINT = 'https://wp-unit.com/?wc-api=wc-am-api';

	/**
	 * Product ID.
	 */
	const PRODUCT_ID = 24;

	/**
	 * Check license status.
	 *
	 * @param string $api_key Api key.
	 *
	 * @return array
	 */
	public function check( string $api_key ): array {

		return $this->request(
			[
				'wc_am_action' => 'status',
				'api_key'      => $api_key,
			]
		);
	}

	/**
	 * Activate license.
	 *
	 * @param string $api_key Api key.
	 *
	 * @return bool
	 */
	public function activate( string $api_key ): bool {

		$response = $this->request(
			[
				'wc_am_action' => 'activate',
				'api_key'      => $api_key,
			]
		);

		return ! empty( $response['success'] ) || ( ! empty( $response['error'] ) && 'Cannot activate API Key. The API Key has already been activated with the same unique instance ID sent with this request.' === $response['error'] );
	}

	/**
	 * Deactivate license.
	 *
	 * @param string $api_key Api key.
	 *
	 * @return bool
	 */
	public function deactivate( string $api_key ): bool {

		$response = $this->request(
			[
				'wc_am_action' => 'deactivate',
				'api_key'      => $api_key,
			]
		);

		return ! empty( $response['success'] );
	}

	/**
	 * Request
	 *
	 * @param array $args Arguments.
	 *
	 * @return array
	 */
	private function request( array $args ): array {

		$args['instance']   = $this->get_instance();
		$args['product_id'] = self::PRODUCT_ID;

		$url = add_query_arg( $args, self::API_ENDPOINT );

		$request = wp_remote_get(
			$url,
			[
				'timeout'   => 10,
				'sslverify' => false,
			]
		);
		$body    = wp_remote_retrieve_body( $request );

		if ( is_wp_error( $body ) || empty( $body ) ) {
			return [];
		}

		$response = json_decode( $body, true );

		return is_array( $response ) ? $response : [];
	}

	/**
	 * Check updates.
	 *
	 * @param string $api_key Api key.
	 *
	 * @return array
	 */
	public function update( string $api_key ): array {

		$response = $this->request(
			[
				'wc_am_action' => 'update',
				'api_key'      => $api_key,
				'plugin_name'  => sprintf( '%1$s/%1$s.php', Main::PLUGIN_SLUG . '-pro' ),
				'version'      => Main::VERSION,
			]
		);

		return ! empty( $response['success'] ) && ! empty( $response['data']['package'] ) ? $response['data']['package'] : [];
	}

	/**
	 * Get instance of this site.
	 *
	 * @return string
	 */
	private function get_instance(): string {

		return rawurlencode( preg_replace( '/https?:\/\//', '', get_home_url() ) );
	}

}
