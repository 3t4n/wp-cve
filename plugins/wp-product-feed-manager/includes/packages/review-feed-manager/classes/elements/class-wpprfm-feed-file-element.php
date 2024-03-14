<?php

/**
 * WPPRFM Feed File Element Class.
 *
 * @package WP Google Product Review Feed Manager/Classes/Elements
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Feed_File_Element' ) ) :

	class WPPRFM_Feed_File_Element {

		public static function google_review_feed_header_element( $feed_id ) {
			$queries_class = new WPPRFM_Queries();
			$header_data   = $queries_class->get_review_feed_header_data( $feed_id );

			$header_data_string  = '<version>' . WPPRFM_FEED_VERSION . '</version>';
			$header_data_string .= $header_data['aggregator_name'] ? '<aggregator><name>' . $header_data['aggregator_name'] . '</name></aggregator>' : '';
			$header_data_string .= $header_data['publisher_name'] || $header_data['publisher_favicon_url'] ? '<publisher>' : '';
			$header_data_string .= $header_data['publisher_name'] ? '<name>' . $header_data['publisher_name'] . '</name>' : '';
			$header_data_string .= $header_data['publisher_favicon_url'] ? '<favicon>' . $header_data['publisher_favicon_url'] . '</favicon>' : '';
			$header_data_string .= $header_data['publisher_name'] || $header_data['publisher_favicon_url'] ? '</publisher>' : '';

			$header_data_string .= '<reviews>';

			return '<?xml version="1.0" encoding="UTF-8"?>
			<feed xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:g="http://base.google.com/ns/1.0"
			xsi:noNamespaceSchemaLocation="http://www.google.com/shopping/reviews/schema/product/2.2/product_reviews.xsd">'
				. $header_data_string;
		}

		public static function google_review_feed_footer_element() {
			return '</reviews></feed>';
		}
	}

	// end of WPPRFM_Feed_File_Element class

endif;
