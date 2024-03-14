<?php

namespace WCPOS\WooCommercePOS\API\Traits;

use WC_Data;
use WCPOS\WooCommercePOS\Logger;
use WP_REST_Response;

trait WCPOS_REST_API {
	/**
	 * @param string $id
	 *
	 * @return object
	 */
	public function wcpos_format_id( string $id ): object {
		return (object) array( 'id' => (int) $id );
	}

	/**
	 * BUG FIX: some servers are not returning the correct meta_data if it is left as WC_Meta_Data objects
	 * NOTE: it only seems to effect some versions of PHP, or some plugins are adding weird meta_data types
	 * The result is mata_data: [{}, {}, {}] ie: empty objects, I think json_encode can't handle the WC_Meta_Data objects.
	 *
	 * @TODO - I need to find out why this is happening
	 *
	 * @param WC_Data $object
	 *
	 * @return array
	 */
	public function wcpos_parse_meta_data( WC_Data $object ): array {
		return array_map(
			function ( $meta_data ) {
				return $meta_data->get_data();
			},
			$object->get_meta_data()
		);
	}

	/**
	 * BUG FIX: the response for some records can be huge, eg:
	 * - product descriptions with lots of HTML,
	 * - I've seen products with 1800+ meta_data objects.
	 *
	 * This is just a helper function to try and alert us to these large responses
	 *
	 * @param WP_REST_Response $response
	 * @param int              $id
	 */
	public function wcpos_log_large_rest_response( WP_REST_Response $response, int $id ): void {
		$response_size     = \strlen( serialize( $response->data ) );
		$max_response_size = 100000;
		if ( $response_size > $max_response_size ) {
			Logger::log( "ID {$id} has a response size of {$response_size} bytes, exceeding the limit of {$max_response_size} bytes." );
		}
	}

		/**
		 * Get barcode field from settings.
		 *
		 * @return bool
		 */
	public function wcpos_allow_decimal_quantities() {
		$allow_decimal_quantities = woocommerce_pos_get_settings( 'general', 'decimal_qty' );

		// Check for WP_Error
		if ( is_wp_error( $allow_decimal_quantities ) ) {
			Logger::log( 'Error retrieving decimal_qty: ' . $allow_decimal_quantities->get_error_message() );

			return false;
		}

		// make sure it's true, just in case there's a corrupt setting
		return true === $allow_decimal_quantities;
	}
}
