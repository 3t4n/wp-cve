<?php


namespace BigCommerce\Rest;


use BigCommerce\Shortcodes;

class Orders_Shortcode_Controller extends Rest_Controller {

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/html', [
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_rendered_item' ],
				'permission_callback' => [ $this, 'get_rendered_item_permissions_check' ],
				'args'                => $this->get_rendered_item_params(),
			],
			'schema' => [ $this, 'get_rendered_item_schema' ],
		] );
	}


	public function get_rendered_item_params() {
		$params = [];
		foreach ( Shortcodes\Order_History::default_attributes() as $key => $default ) {
			$params[ $key ] = [
				'type'    => is_int( $default ) ? 'integer' : 'string',
				'default' => $default,
			];
		}

		return $params;
	}

	/**
	 * Checks if a given request has access to read the rendered shortcodes.
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return true|\WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_rendered_item_permissions_check( $request ) {
		// no access checks for now
		return true;
	}

	public function get_rendered_item_schema() {
		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'bigcommerce_order_history_shortcode_rendered',
			'type'       => 'object',
			'properties' => [
				'rendered' => [
					'description' => __( 'The rendered shortcode string', 'bigcommerce' ),
					'type'        => 'string',
					'context'     => [ 'view', 'edit', 'embed' ],
					'readonly'    => true,
				],
			],
		];

		return $schema;
	}

	/**
	 * Retrieves a single shortcode
	 *
	 * @since 4.7.0
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_REST_Response|\WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_rendered_item( $request ) {
		$attributes = $request->get_params();
		$shortcode  = $this->build_shortcode_string( $attributes );
		$output     = do_shortcode( $shortcode );
		$response   = rest_ensure_response( [
			'rendered' => $output,
		] );

		return $response;
	}

	private function build_shortcode_string( $args ) {
		$attributes = '';
		foreach ( $args as $key => $value ) {
			$attributes .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}

		return sprintf( '[%s%s]', Shortcodes\Order_History::NAME, $attributes );
	}
}