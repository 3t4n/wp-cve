<?php

/**
 * WPPPFM Data Class.
 *
 * @package WP Google Merchant Promotions Feed Manager/Classes
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Data' ) ) :

	class WPPPFM_Data {

		/**
		 * Returns the options for the merchant promotion filter selector. These options include all the available product ids (through product names),
		 * product types, brands and group ids.
		 *
		 * @return array
		 */
		public function get_merchant_promotion_filter_selector_options() {
			$filter_selector_options[] = $this->get_products_group();
			$filter_selector_options[] = $this->get_wc_product_types_group();
			$filter_selector_options[] = $this->get_brands_group();
			$filter_selector_options[] = $this->get_item_group_ids();

			return $filter_selector_options;
		}

		public function convert_input_data_to_feed_attributes( $feed_data ) {
			if ( ! class_exists( 'WPPPFM_Queries' ) && file_exists( __DIR__ . '/class-wpppfm-queries.php' ) ) {
				require_once __DIR__ . '/class-wpppfm-queries.php';
			}

			$queries_class   = new WPPPFM_Queries();
			$promotion_items = array();

			// @codingStandardsIgnoreLine
			$promotions_data = json_decode( $queries_class->get_meta_data( $feed_data->feedId )[0]['meta_value'] );

			foreach ( $promotions_data as $promotion ) {
				$promotion_item    = $this->make_promotion_feed_attributes( $promotion );
				$promotion_items[] = $promotion_item;
			}

			$feed_data->promotions = $promotion_items;
		}

		/**
		 * Returns the options for the promotion destination selector.
		 *
		 * @return array
		 */
		public function get_promotion_destination_options() {

			return array(
				array(
					'id'   => 'local_inventory_ads',
					'text' => __( 'Local Inventory Ads', 'wp-product-promotions-feed-manager' ),
				),
				array(
					'id'       => 'shopping_ads',
					'text'     => __( 'Shopping Ads', 'wp-product-promotions-feed-manager' ),
					'selected' => 'true',
				),
				array(
					'id'   => 'buy_on_google_listings',
					'text' => __( 'Buy on Google Listings', 'wp-product-promotions-feed-manager' ),
				),
				array(
					'id'       => 'free_listings',
					'text'     => __( 'Free Listings', 'wp-product-promotions-feed-manager' ),
					'selected' => 'true',
				),
			);
		}

		private function get_products_group() {
			$products = new stdClass();

			$products->text = 'Products';

			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
			);

			$products_query = new WP_Query( $args );
			$product_names  = array();

			while ( $products_query->have_posts() ) {
				$products_query->the_post();
				$product_id   = get_the_ID();
				$product_name = get_the_title();

				$name = array(
					'id'        => (string) $product_id,
					'text'      => $product_name,
					'attribute' => 'item_id',
				);

				$product_names[] = $name;
			}

			$products->children = $product_names;

			wp_reset_postdata();

			return $products;
		}

		private function get_wc_product_types_group() {
			$types = new stdClass();

			$types->text     = 'Product Types';
			$types->children = array(
				array(
					'id'        => 'simple',
					'text'      => __( 'Simple', 'wp-product-promotions-feed-manager' ),
					'attribute' => 'product_type',
				),
				array(
					'id'        => 'grouped',
					'text'      => __( 'Grouped', 'wp-product-promotions-feed-manager' ),
					'attribute' => 'product_type',
				),
				array(
					'id'        => 'virtual',
					'text'      => __( 'Virtual', 'wp-product-promotions-feed-manager' ),
					'attribute' => 'product_type',
				),
				array(
					'id'        => 'downloadable',
					'text'      => __( 'Downloadable', 'wp-product-promotions-feed-manager' ),
					'attribute' => 'product_type',
				),
				array(
					'id'        => 'external',
					'text'      => __( 'External/affiliate', 'wp-product-promotions-feed-manager' ),
					'attribute' => 'product_type',
				),
				array(
					'id'        => 'variable',
					'text'      => __( 'Variable', 'wp-product-promotions-feed-manager' ),
					'attribute' => 'product_type',
				),
			);

			return $types;
		}

		private function get_brands_group() {
			global $wpdb;

			$brands = new stdClass();

			$brands->text = 'Brands';

			$product_brands = array();
			$brands_list    = array();

			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s",
					'%brand%'
				)
			);

			foreach ( $results as $result ) {
				$meta_value = $result->meta_value;

				if ( ! $meta_value || in_array( $meta_value, $brands_list, true ) ) {
					continue;
				}

				$brands_list[] = $meta_value;

				$product_brand = array(
					'id'        => $meta_value,
					'text'      => $meta_value,
					'attribute' => 'brand',
				);

				$product_brands[] = $product_brand;
			}

			$brands->children = $product_brands;

			return $brands;
		}

		private function get_item_group_ids() {
			global $wpdb;

			$group_ids = array();

			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s",
					'%group_id%'
				)
			);

			foreach ( $results as $result ) {
				$meta_value = $result->meta_value;

				if ( ! $meta_value || in_array( $meta_value, $group_ids, true ) ) {
					continue;
				}

				$group_id = array(
					'id'        => $meta_value,
					'text'      => $meta_value,
					'attribute' => 'group_id',
				);

				$group_ids[] = $group_id;
			}

			return $group_ids;
		}

		private function make_promotion_feed_attributes( $promotion_data ) {
			$promotion_feed_data = array();
			$start_date          = '';
			$end_date            = '';
			$display_start_date  = '';
			$display_end_date    = '';

			foreach ( $promotion_data as $value ) {

				if ( 'promotion_effective_start_date' === $value->meta_key ) {
					$start_date = $value->meta_value;
					continue;
				}

				if ( 'promotion_effective_end_date' === $value->meta_key ) {
					$end_date = $value->meta_value;
					continue;
				}

				if ( 'promotion_display_start_date' === $value->meta_key ) {
					$display_start_date = $value->meta_value;
					continue;
				}

				if ( 'promotion_display_end_date' === $value->meta_key ) {
					$display_end_date = $value->meta_value;
					continue;
				}

				if ( 'promotion_destination' === $value->meta_key ) {
					foreach ( $value->meta_value as $destination ) {
						$promotion_feed_data['promotion_destination'] = $destination;
					}
				}

				$promotion_feed_data[ $value->meta_key ] = $value->meta_value;
			}

			$promotion_feed_data['promotion_effective_dates'] = $start_date . '00+00:00/' . $end_date . '00+00:00';
			$promotion_feed_data['promotion_display_dates']   = $display_start_date . '00+00:00/' . $display_end_date . '00+00:00';
			return $promotion_feed_data;
		}
	}

	// end of WPPPFM_Data class

endif;
