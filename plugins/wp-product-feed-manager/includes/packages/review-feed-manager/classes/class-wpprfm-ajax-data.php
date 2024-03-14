<?php

/**
 * WPPRFM Ajax Data Class.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Ajax_Data' ) ) :

	class WPPRFM_Ajax_Data extends WPPFM_Ajax_Calls {

		public function __construct() {
			parent::__construct();

			// hooks
			add_action( 'wp_ajax_myajax-get-product-review-feed-attributes', array( $this, 'myajax_get_product_review_feed_attributes' ) );
			add_action( 'wp_ajax_myajax-get-review-data', array( $this, 'myajax_get_product_review_feed_main_data' ) );
		}

		/**
		 * Returns the attributes of a specific Google Review Feed to the caller.
		 */
		public function myajax_get_product_review_feed_attributes() {

			if ( $this->safe_ajax_call( filter_input( INPUT_POST, 'reviewFeedGetAttributesNonce' ), 'myajax-review-feed-get-attributes-nonce' ) ) {
				$feed_id = filter_input( INPUT_POST, 'feedId' );

				$product_review_feed_attributes = WPPRFM_Attributes_List::wpprfm_get_review_feed_attributes();

				// if the feed is a stored feed, look for metadata to add (a feed with an id of -1 is a new feed that not yet has been saved)
				if ( $feed_id >= 0 ) {
					$data_class = new WPPFM_Data();
					// add metadata to the feeds output fields
					$product_review_feed_attributes = $data_class->fill_output_fields_with_metadata( $feed_id, $product_review_feed_attributes );
				}

				echo json_encode( $product_review_feed_attributes );
			}

			// IMPORTANT: don't forget to exit
			exit;
		}

		/**
		 * Returns the main data of a specific Google Review Feed to the caller.
		 */
		public function myajax_get_product_review_feed_main_data() {

			if ( $this->safe_ajax_call( filter_input( INPUT_POST, 'reviewFeedGetMainDataNonce' ), 'myajax-review-feed-get-main-data-nonce' ) ) {
				$review_feed_query_class  = new WPPRFM_Queries();
				$product_feed_query_class = new WPPFM_Queries();

				$feed_id = filter_input( INPUT_POST, 'sourceId' );

				$result = $review_feed_query_class->read_feed( $feed_id );

				// add the category mapping to the result
				$category_mapping = $product_feed_query_class->read_category_mapping( $feed_id );

				if ( isset( $category_mapping[0]['meta_value'] ) && '' !== $category_mapping[0]['meta_value'] ) {
					$result[0]['category_mapping'] = $category_mapping[0]['meta_value'];
				} else {
					$result[0]['category_mapping'] = '';
				}

				// standard values
				$result[0]['include_variations'] = '0';
				$result[0]['country']            = 'US';

				echo json_encode( $result );
			} else {
				echo false;
			}

			// IMPORTANT: don't forget to exit
			exit;
		}
	}

	// end of WPPRFM_Ajax_Data class

endif;

$my_wpprfm_ajax_data_class = new WPPRFM_Ajax_Data();
