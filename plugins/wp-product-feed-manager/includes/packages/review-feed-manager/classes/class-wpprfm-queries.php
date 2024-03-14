<?php

/**
 * WPPRFM Queries Class.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Queries' ) ) :

	class WPPRFM_Queries {

		/**
		 * @var wpdb
		 */
		private $_wpdb;

		/**
		 * WPPFM_Queries Constructor
		 */
		public function __construct() {
			// get global WordPress database functions
			global $wpdb;

			// assign the global wpdb to a variable
			$this->_wpdb = &$wpdb;
		}

		public function read_feed( $feed_id ) {
			$wppfm_queries_class = new WPPFM_Queries();

			/** @noinspection SqlResolve */
			$result = $this->_wpdb->get_results(
				"
				SELECT product_feed_id, title, feed_description, schedule, url, status_id, feed_type_id, aggregator_name, publisher_name, publisher_favicon_url
				FROM {$this->_wpdb->prefix}feedmanager_product_feed
				WHERE product_feed_id = $feed_id
				",
				ARRAY_A
			);

			$category_mapping = $wppfm_queries_class->read_category_mapping( $feed_id );

			if ( isset( $category_mapping[0]['meta_value'] ) && '' !== $category_mapping[0]['meta_value'] ) {
				$result[0]['category_mapping'] = $category_mapping[0]['meta_value'];
			} else {
				$result[0]['category_mapping'] = '';
			}

			return $result;
		}

		public function get_review_feed_header_data( $feed_id ) {

			/** @noinspection SqlResolve */
			return $this->_wpdb->get_results(
				"SELECT aggregator_name, publisher_name, publisher_favicon_url
				FROM {$this->_wpdb->prefix}feedmanager_product_feed
				WHERE product_feed_id = $feed_id
				",
				ARRAY_A
			)[0];
		}

	}

	// end of WPPRFM_Queries class

endif;
