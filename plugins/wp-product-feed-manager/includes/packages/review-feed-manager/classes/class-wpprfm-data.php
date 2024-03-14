<?php

/**
 * WPPRFM Data Class.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Data' ) ) :

	class WPPRFM_Data {

		public function get_product_review_feed_attributes( $feed_id ) {

			$product_review_feed_attributes = WPPRFM_Attributes_List::wpprfm_get_review_feed_attributes();

			// if the feed is a stored feed, look for metadata to add (a feed with an id of -1 is a new feed that not yet has been saved)
			if ( $feed_id >= 0 ) {
				$data_class = new WPPFM_Data();
				// add metadata to the feeds output fields
				$product_review_feed_attributes = $data_class->fill_output_fields_with_metadata( $feed_id, $product_review_feed_attributes );
			}

			return json_encode( $product_review_feed_attributes );
		}
	}

	// end of WPPRFM_Data class

endif;
