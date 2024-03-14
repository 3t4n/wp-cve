<?php

namespace WpifyWoo\Modules\HeurekaOverenoZakazniky;

use mysql_xdevapi\Exception;
use WpifyWooDeps\Heureka\ShopCertification\ApiEndpoint;
use WpifyWooDeps\Heureka\ShopCertification\IRequester;

/**
 * @author Jakub Chábek <jakub.chabek@heureka.cz>
 */
class WpRequester implements IRequester {
	/**
	 * @var ApiEndpoint
	 */
	private $endpoint;

	/**
	 * @param ApiEndpoint $endpoint
	 */
	public function setApiEndpoint( ApiEndpoint $endpoint ) {
		$this->endpoint = $endpoint;
	}

	/**
	 * @inheritdoc
	 */
	public function request( $action, array $getData = [], array $postData = [] ) {
		if ( $postData ) {
			$json = \json_encode( $postData, \JSON_PRETTY_PRINT );
			if ( $json === \false ) {
				throw new Exception( 'Failed to serialize data into JSON. Data: ' . \var_export( $postData, \true ) );
			}
		}

		$url = add_query_arg( $getData, $this->endpoint->getUrl() . 'order/log' );

		if ( ! empty( $postData ) ) {
			$args   = [
				'headers' => [
					'content-type' => 'application/json',
				],
				'body'    => $json,
			];
			$result = wp_remote_post( $url, $args );

			return json_decode( wp_remote_retrieve_body( $result ) );
		} else {
			$result = wp_remote_get( $url );

			return json_decode( wp_remote_retrieve_body( $result ) );
		}
	}
}
