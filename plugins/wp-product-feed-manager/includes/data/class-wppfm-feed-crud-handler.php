<?php

/**
 * WPPFM Feed CRUD Handler class.
 *
 * @package WP Product Feed Manager/Data/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Feed_CRUD_Handler' ) ) :

	class WPPFM_Feed_CRUD_Handler {

		public static function create_or_update_feed_data( $feed_data, $meta_data_encoded, $feed_filter ) {
			$data_class    = new WPPFM_Data();
			$queries_class = new WPPFM_Queries();

			$feed_id = self::get_feed_id_from_feed_data( $feed_data );

			// convert the feed data to a database format
			$feed_data_to_store = $data_class->convert_ajax_feed_data_to_database_format( $feed_data );
			$feed_data_types    = $data_class->get_types_from_feed_data( $feed_data_to_store, $feed_data );

			self::add_fixed_data_fields( $feed_data_to_store, $feed_data_types, $feed_id );

			// decode the meta data
			$meta_data = json_decode( $meta_data_encoded );

			// create or update the feed
			if ( ! $feed_id || $feed_id < 0 ) {
				$actual_feed_id = $queries_class->create_feed( $feed_data_to_store, $feed_data_types );
			} else {
				$update_result  = $queries_class->update_feed( $feed_id, $feed_data_to_store, $feed_data_types );
				$actual_feed_id = $update_result ? $feed_id : 0;
			}

			if ( count( $meta_data ) > 0 ) {
				$queries_class->update_meta_data( $actual_feed_id, $meta_data );
			}

			$queries_class->store_feed_filter( $actual_feed_id, $feed_filter );

			return $actual_feed_id;
		}

		private static function get_feed_id_from_feed_data( $feed_data ) {
			// use the array_filter to select the feed data element with product_feed_id as name
			$feed_id_item = array_filter(
				$feed_data,
				function( $element ) {
					return 'product_feed_id' === $element->name;
				}
			);

			// return the feed id
			return reset( $feed_id_item )->value;
		}

		private static function add_fixed_data_fields( &$feed_data, &$data_types, $feed_id ) {

			//phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
			$feed_data['updated'] = gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) );

			if ( $feed_id < 0 ) {
				$feed_data['products'] = 0; }

			array_push( $data_types, '%s', '%d' );
		}

	}

	// end of WPPFM_Feed_CRUD_Handler class

endif;
