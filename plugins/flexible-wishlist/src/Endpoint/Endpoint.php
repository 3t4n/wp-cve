<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Endpoint;

/**
 * Stores the information required to create a route in the REST API.
 */
interface Endpoint {

	/**
	 * @return string
	 */
	public function get_route_method_type(): string;

	/**
	 * @return string
	 */
	public static function get_route_name(): string;

	/**
	 * Returns list of params for endpoint.
	 *
	 * @return mixed[]
	 */
	public function get_route_args(): array;

	/**
	 * @param int|null $id .
	 *
	 * @return string
	 */
	public static function get_route_url( int $id = null ): string;

	/**
	 * @param \WP_REST_Request $request REST request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 * @internal
	 */
	public function get_route_response( \WP_REST_Request $request );
}
