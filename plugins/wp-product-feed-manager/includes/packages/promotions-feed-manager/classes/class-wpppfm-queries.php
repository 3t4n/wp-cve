<?php

/**
 * WPPPFM Queries Class.
 *
 * @package WP Merchant Promotions Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Queries' ) ) :

	class WPPPFM_Queries {

		/**
		 * @var wpdb
		 */
		private $_wpdb;

		/**
		 * WPPPM_Queries Constructor
		 */
		public function __construct() {
			// get global WordPress database functions
			global $wpdb;

			// assign the global wpdb to a variable
			$this->_wpdb = &$wpdb;
		}

		public function read_feed( $feed_id ) {
			if ( ! $feed_id ) {
				return array( array() );
			}

			/** @noinspection SqlResolve */
			return $this->_wpdb->get_results(
				"SELECT product_feed_id, title, url, status_id, feed_type_id
				FROM {$this->_wpdb->prefix}feedmanager_product_feed
				WHERE product_feed_id = {$feed_id}",
				ARRAY_A
			);
		}

		public function get_meta_data( $feed_id ) {
			if ( ! $feed_id ) {
				return array();
			}

			/** @noinspection SqlResolve */
			return $this->_wpdb->get_results(
				"	SELECT meta_key, meta_value
				FROM {$this->_wpdb->prefix}feedmanager_product_feedmeta
				WHERE product_feed_id = {$feed_id}
				ORDER BY meta_key DESC",
				ARRAY_A
			);
		}
	}

	// end of WPPPFM_Queries class

endif;
