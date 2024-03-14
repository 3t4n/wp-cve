<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

/**
 * {@inheritdoc}
 */
abstract class EndpointBase implements Endpoint {

	/**
	 * {@inheritdoc}
	 */
	public static function get_route_url( int $id = null ): string {
		$url = get_rest_url(
			null,
			sprintf(
				'%1$s/%2$s',
				EndpointIntegrator::ROUTE_NAMESPACE,
				static::get_route_name()
			)
		);
		if ( $id !== null ) {
			$url = preg_replace( '/\(\?P(.*?)\)/', (string) $id, $url );
		}
		return add_query_arg( EndpointIntegrator::ROUTE_NONCE_PARAM, wp_create_nonce( 'wp_rest' ), $url );
	}
}
