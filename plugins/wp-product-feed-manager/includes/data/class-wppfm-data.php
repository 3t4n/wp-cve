<?php

/**
 * WP Data Class.
 *
 * @package WP Product Feed Manager/Data/Classes
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Data' ) ) :

	/**
	 * Data Class
	 */
	class WPPFM_Data {

		private $_queries_class;
		private $_files_class;

		public function __construct() {
			$this->_queries_class = new WPPFM_Queries();
			$this->_files_class   = new WPPFM_File();
		}

		/**
		 * Returns an array with Google Support feed types available to generate.
		 *
		 * @since 2.38.0.
		 * @return array[]
		 */
		public function get_google_support_feed_types() {
			return array(
				array(
					'feed_type_id' => '1',
					'short_name'   => 'product-feed',
					'name'         => 'Google Product Feed',
					'group'        => 'primary',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/merchants/answer/7439058?hl=en',
					'specs_url'    => 'https://support.google.com/merchants/answer/7052112?hl=en',
				),
				array(
					'feed_type_id' => '2',
					'short_name'   => 'google-product-review-feed',
					'name'         => 'Product Review Feed',
					'group'        => 'supplemental',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/merchants/answer/6059553?hl=en',
					'specs_url'    => 'https://developers.google.com/product-review-feeds/schema',
				),
				array(
					'feed_type_id' => '3',
					'short_name'   => 'google-merchant-promotions-feed',
					'name'         => 'Merchant Promotions Feed',
					'group'        => 'supplemental',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/merchants/answer/7322422?hl=en',
					'specs_url'    => 'https://support.google.com/merchants/answer/2906014?hl=en',
				),
				array(
					'feed_type_id' => '4',
					'short_name'   => 'google-local-product-inventory-feed',
					'name'         => 'Local Product Inventory Feed',
					'group'        => 'supplemental',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/merchants/answer/7023001?hl=en',
					'specs_url'    => 'https://support.google.com/merchants/answer/3061342?sjid=6792796007470289747-EU',
				),
				array(
					'feed_type_id' => '5',
					'short_name'   => 'google-dynamic-remarketing-feed',
					'name'         => 'Dynamic Remarketing Feed',
					'group'        => 'supplemental',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/google-ads/answer/6053288?hl=en',
					'specs_url'    => 'https://support.google.com/google-ads/answer/6053288?hl=en#Feed_templates&zippy=',
				),
				array(
					'feed_type_id' => '6',
					'short_name'   => 'google-vehicle-ads-feed',
					'name'         => 'Vehicle Ads Feed',
					'group'        => 'supplemental',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/merchants/answer/11189169?hl=en',
					'specs_url'    => 'https://support.google.com/merchants/answer/11192663?hl=en',
				),
				array(
					'feed_type_id' => '7',
					'short_name'   => 'google-dynamic-search-ads-feed',
					'name'         => 'Dynamic Search Ads Feed',
					'group'        => 'supplemental',
					'disabled'     => false,
					'info_url'     => 'https://support.google.com/google-ads/answer/7166527?hl=en',
					'specs_url'    => 'https://support.google.com/google-ads/answer/7166527?sjid=17382708960891476328-EU',
				),
				array(
					'feed_type_id' => '8',
					'short_name'   => 'google-local-product-feed',
					'name'         => 'Local Product Feed (deprecated by Google)',
					'group'        => 'supplemental',
					'disabled'     => true,
					'info_url'     => 'https://support.google.com/merchants/answer/3540291',
					'specs_url'    => '',
				),
				array(
					'feed_type_id' => '9',
					'short_name'   => 'buy-on-google-feed',
					'name'         => 'Buy on Google Feed (deprecated by Google)',
					'group'        => 'supplemental',
					'disabled'     => true,
					'info_url'     => 'https://support.google.com/merchants/topic/7679088?hl=en',
					'specs_url'    => '',
				),
			);
		}

		public function get_support_feed_specifications_url( $short_name ) {
			$support_feed_data = $this->get_google_support_feed_types();

			foreach ( $support_feed_data as $support_feed ) {
				if ( $support_feed['short_name'] === $short_name ) {
					return $support_feed['specs_url'];
				}
			}
		}

		public function get_countries() {
			return $this->_queries_class->read_countries();
		}

		public function get_channel_id_from_short_name( $channel_short_name ) {
			return $this->_queries_class->get_channel_id( $channel_short_name );
		}

		public function get_channels() {
			return $this->_queries_class->read_installed_channels();
		}

		public function delete_channel( $channel_short ) {
			$result = $this->_queries_class->remove_channel_from_db( $channel_short );

			if ( ! $result ) {
				$result = $this->_queries_class->clean_channel_table();
			}

			return $result;
		}

		public function delete_channel_feeds( $channel_id ) {
			$feeds = $this->_queries_class->get_feeds_from_specific_channel( $channel_id );

			foreach ( $feeds as $feed_id ) {
				$this->_queries_class->delete_meta( $feed_id['product_feed_id'] );
				$this->_queries_class->delete_feed( $feed_id['product_feed_id'] );
			}
		}

		public function get_sources() {
			return $this->_queries_class->read_sources();
		}

		private function get_country_id_from_short_code( $country_code ) {
			if ( '0' !== $country_code && 0 !== $country_code ) {
				return $this->_queries_class->get_country_id( $country_code );
			} else {
				$id             = new stdClass();
				$id->country_id = '233';

				return $id;
			}
		}

		public function get_schedule_data() {
			return $this->_queries_class->read_active_schedule_data();
		}

		public function get_failed_feeds() {
			return $this->_queries_class->read_failed_feeds();
		}

		public function get_feed_status( $feed_id ) {
			$feed_status = $this->_queries_class->get_current_feed_status( $feed_id );

			return $feed_status[0]->status_id;
		}

		public function set_nr_of_feed_products( $feed_id, $nr ) {
			return $this->_queries_class->set_nr_feed_products( $feed_id, $nr );
		}

		public function update_feed_data( $feed_id, $feed_url, $nr_products ) {
			return $this->_queries_class->update_feed_update_data( $feed_id, $feed_url, $nr_products );
		}

		/**
		 * Sets the status id of a feed
		 *
		 * 0 = unknown
		 * 1 = OK (will be updated automatically)
		 * 2 = On hold (will not be updated automatically)
		 * 3 = Processing
		 * 4 = In processing queue
		 * 5 = Has errors
		 * 6 = Failed processing
		 *
		 * @param   string  $feed_id
		 * @param   int     $status
		 *
		 * @return  bool    True if the update succeeded.
		 */
		public function update_feed_status( $feed_id, $status ) {

			$message_level = 'MESSAGE';

			switch ( $status ) {
				case 1:
					$message = sprintf( 'The feed status of feed %s has been set to OK (1)', $feed_id );
					break;

				case 2:
					$message = sprintf( 'The feed status of feed %s has been set to On Hold (2)', $feed_id );
					break;

				case 3:
					$message = sprintf( 'The feed status of feed %s has been set to Processing (3)', $feed_id );
					break;

				case 4:
					$message = sprintf( 'The feed status of feed %s has been set to In processing queue (4)', $feed_id );
					break;

				case 5:
					$message       = sprintf( 'The feed status of feed %s has been set to Has errors (5)', $feed_id );
					$message_level = 'ERROR';
					break;

				case 6:
					$message       = sprintf( 'The feed status of feed %s has been set to Failed processing (6)', $feed_id );
					$message_level = 'ERROR';
					break;

				default:
					$message       = sprintf( 'Tried to update the status of feed %s but the status is unknown!', $feed_id );
					$message_level = 'ERROR';
			}

			do_action( 'wppfm_feed_generation_message', $feed_id, $message, $message_level );

			return $this->_queries_class->update_feed_file_status( $feed_id, $status );
		}

		/**
		 * Fills output fields with stored metadata
		 *
		 * @access public
		 *
		 * @param string $feed_id
		 * @param array $outputs
		 *
		 * @return array
		 */
		public function fill_output_fields_with_metadata( $feed_id, $outputs ) {
			// read the metadata from the database
			$metadata = $this->_queries_class->read_metadata( $feed_id );

			// loop through the output rows
			for ( $i = 0; $i < count( $outputs ); $i ++ ) {
				// check if there is specific metadata for this output row
				if ( count( $metadata ) > 0 ) {
					foreach ( $metadata as $meta ) {
						// look for a match
						if ( $meta['meta_key'] === $outputs[ $i ]->field_label ) {
							// put the metadata in the value variable of the output row
							$outputs[ $i ]->value = $meta['meta_value'];
							break; // break is required to stop the foreach loop and prevent the following loop from clearing the value
						} else {
							// as long as there is no match, leave the value empty
							$outputs[ $i ]->value = '';
						}
					}
				} else {
					$outputs[ $i ]->value = '';
				}
			}

			return $outputs;
		}

		/**
		 * Collects the source fields from all the different attributes.
		 *
		 * @param   string  $source_id  The source id (not in use at the moment). Default 1.
		 *
		 * @return  mixed|void|null
		 */
		public function get_source_fields( $source_id = '1' ) {
			$source_fields = null;

			switch ( $source_id ) {
				case '1':
					$data_class = new WPPFM_Data();


					$custom_product_attributes = $this->_queries_class->get_custom_product_attributes();
					$custom_product_fields     = $this->_queries_class->get_custom_product_fields();
					$product_attributes        = $this->_queries_class->get_all_product_attributes();
					$product_taxonomies        = get_taxonomies();
					$third_party_custom_fields = $data_class->get_third_party_custom_fields();

					$combined_source_fields = $this->combine_custom_attributes_and_feeds(
						$custom_product_attributes,
						$custom_product_fields,
						$product_attributes,
						$product_taxonomies,
						$third_party_custom_fields
					);

					$source_fields = apply_filters( 'wppfm_all_source_fields', $combined_source_fields );
					break;

				default:
					if ( 'valid' === get_option( 'wppfm_lic_status' ) ) { // error message for paid versions
						echo '<div id="error">' . __(
							'Could not add custom fields because I could not identify the channel.
								If not already done add the correct channel in the Manage Channels page.
							Also try to deactivate and then activate the plugin.',
							'wp-product-feed-manager'
						) . '</div>';

						wppfm_write_log_file( sprintf( 'Could not define the channel in a valid Premium plugin version. Feed id = %s', $source_id ) );
					} else { // error message for free version
						echo '<div id="error">' . __(
							'Could not identify the channel.
								Try to deactivate and then activate the plugin.
								If that does not work remove the plugin through the WordPress Plugins page and than reinstall and activate it again.',
							'wp-product-feed-manager'
						) . '</div>';

						wppfm_write_log_file( sprintf( 'Could not define the channel in a free plugin version. Feed id = %s', $source_id ) );
					}

					break;
			}

			return $source_fields;
		}

		/**
		 * Get the attribute data of a specific feed.
		 *
		 * @param   string  $feed_id      The id of the feed from which the attribute data is needed.
		 * @param   string  $channel_id   The id of  the channel of the feed.
		 * @param   string  $feed_type_id The id of the feed type of the feed.
		 *
		 * @return  array   The attribute data.
		 */
		public function get_attribute_data( $feed_id, $channel_id, $feed_type_id ) {
			$is_custom    = function_exists( 'wppfm_channel_is_custom_channel' ) && wppfm_channel_is_custom_channel( $channel_id );
			$channel_name = trim( $this->_queries_class->get_channel_short_name_from_db( $channel_id ) );

			if ( ! $is_custom ) {
				// read the output fields
				$attributes_data = apply_filters( 'wppfm_get_feed_attributes', $this->_files_class->get_output_fields_for_specific_channel( $channel_name ), $feed_id, $feed_type_id );

				// if the feed is a stored feed, look for metadata to add (a feed an id of -1 is a new feed that not yet has been saved)
				if ( $feed_id >= 0 ) {
					// add metadata to the feeds output fields
					$attributes_data = $this->fill_output_fields_with_metadata( $feed_id, $attributes_data );
				}
			} else {
				$attributes_data = $this->get_custom_fields_with_metadata( $feed_id );
			}

			return $attributes_data;
		}

		public function get_filter_query( $feed_id ) {
			return $this->_queries_class->get_product_filter_query( $feed_id );
		}

		public function get_own_variation_data( $variation_id ) {
			return $this->_queries_class->get_own_variable_product_attributes( $variation_id );
		}

		public function add_parent_data( &$product_data, $parent_id, $post_columns_query_string, $language ) {
			$parent_product_data = $this->_queries_class->read_post_data( $parent_id, $post_columns_query_string );

			// WPML support.
			if ( has_filter( 'wpml_translation' ) ) {
				$parent_product_data = apply_filters( 'wpml_translation', $parent_product_data, $language );
			}

			// Polylang support.
			if ( has_filter( 'pll_translation' ) ) {
				$parent_product_data = apply_filters( 'pll_translation', $parent_product_data, $language );
			}

			// Translatepress support.
			if ( has_filter( 'wppfm_transpress_translation' ) ) {
				$parent_product_data = apply_filters( 'wppfm_transpress_translation', $parent_product_data, $language );
			}

			$parent_product_data = (array) $parent_product_data;

			$sources_that_always_use_parent_data = apply_filters( 'sources_that_always_use_data_from_parent', array( 'post_excerpt' ) );

			$columns = explode( ', ', $post_columns_query_string );

			foreach ( $columns as $column ) {
				if ( ( '' === $product_data[ $column ] && array_key_exists( $column, $parent_product_data ) && '' !== $parent_product_data[ $column ] )
					|| in_array( $column, $sources_that_always_use_parent_data, true ) ) {
					$product_data[ $column ] = array_key_exists( $column, $parent_product_data ) ? $parent_product_data[ $column ] : '';
				}
			}
		}

		public function get_custom_fields_with_metadata( $feed_id ) {
			// read the metadata from the database
			$metadata = $this->_queries_class->read_metadata( $feed_id );
			$outputs  = array();

			// loop through the output rows
			for ( $i = 0; $i < count( $metadata ); $i ++ ) {
				$object = new stdClass();

				$object->field_id    = $i + 1;
				$object->category_id = '5';
				$object->field_label = $metadata[ $i ]['meta_key'];
				$object->value       = $metadata[ $i ]['meta_value'];

				$outputs[] = $object;
			}

			return $outputs;
		}

		public function get_third_party_custom_fields() {
			$custom_fields = array();

			// YITH Brands plugin
			$yith_brand_label = get_option( 'yith_wcbr_brands_label' );
			if ( $yith_brand_label ) {
				$custom_fields[] = $yith_brand_label;
			}

			// WooCommerce Brands
			if ( in_array(
				'woocommerce-brands/woocommerce-brands.php',
				apply_filters(
					'active_plugins',
					get_option( 'active_plugins' )
				),
				true
			) ) {
				$custom_fields[] = 'Brand';
			}

			// Advanced Custom Fields (ACF) plugin support
			// Adds the ACF fields to the WooCommerce source selectors
			// @since 3.1.0
			if ( function_exists( 'acf_get_internal_post_type_posts' ) && function_exists( 'acf_get_fields' ) ) {
				$array_with_acf_field_groups = acf_get_internal_post_type_posts();

				foreach ( $array_with_acf_field_groups as $field_group ) {
					$fields = acf_get_fields( $field_group );

					foreach ( $fields as $field ) {
						$custom_fields[] = $field['_name'];
					}
				}
			}

			return $custom_fields;
		}

		/**
		 * Checks if other feeds than the currently active feed are still on processing status (status 3). If so, set these feeds to an error status (status 6).
		 *
		 * @param string $active_feed_id
		 *
		 * @since 1.10.0
		 *
		 */
		public function check_for_failed_feeds( $active_feed_id ) {
			$processing_feeds = $this->_queries_class->get_feed_ids_with_specific_status( '3' );
			$failed_feed_ids  = '';

			foreach ( $processing_feeds as $feed ) {
				if ( $active_feed_id !== $feed->product_feed_id ) {
					$this->update_feed_status( $feed->product_feed_id, 6 );
					$failed_feed_ids .= ', ' . $feed->product_feed_id;
				}
			}

			if ( $failed_feed_ids ) {
				$message = sprintf( 'Starting the update of feed %s, the following feeds where still registered as being active: %s and are now set to the status FAIL.', $active_feed_id, $failed_feed_ids );
				do_action( 'wppfm_feed_generation_message', $active_feed_id, $message, 'ERROR' );
			}
		}

		/**
		 * Converts feed data items that are send through an ajax call to the corresponding database names.
		 *
		 * @param $feed_data
		 *
		 * @return array
		 * @since 2.5.0
		 *
		 * @since 2.31.0 implemented a conversion to integer for %d value items.
		 */
		public function convert_ajax_feed_data_to_database_format( $feed_data ) {
			$result = array();

			foreach ( $feed_data as $data_item ) {
				if ( 'product_feed_id' !== $data_item->name ) {

					if ( 'url' === $data_item->name ) {
						$data_item->value = $this->verify_url( $data_item->value );
					}

					if ( 'country_id' === $data_item->name ) {
						$data_item->value = $this->get_country_id_from_short_code( $data_item->value )->country_id;
					}

					$result[ $data_item->name ] = '%d' !== $data_item->type ? $data_item->value : intval( $data_item->value );
				}
			}

			return $result;
		}

		/**
		 * Gets the correct data types from the feed data and puts them into an array in the correct order.
		 *
		 * @param $feed_data
		 * @param $ajax_feed_data
		 *
		 * @return array
		 * @since 2.5.0
		 *
		 */
		public function get_types_from_feed_data( $feed_data, $ajax_feed_data ) {
			$result = array();

			foreach ( $feed_data as $data_key => $value ) {
				$feed_item = array_filter(
					$ajax_feed_data,
					function ( $item ) use ( $data_key ) {
						return $item->name === $data_key;
					}
				);

				$result[] = reset( $feed_item )->type;
			}

			return $result;
		}

		public function get_feed_data( $feed_id ) {
			// get the main data
			$main_feed_data = $this->_queries_class->read_feed( $feed_id );
			$main_data      = $this->convert_data_to_feed_data( $main_feed_data[0] );

			if ( false === $main_data ) {
				return false;
			}

			$main_data->attributes = array();

			$channel   = trim( $this->_queries_class->get_channel_short_name_from_db( $main_feed_data[0]['channel'] ) );
			$is_custom = function_exists( 'wppfm_channel_is_custom_channel' ) && wppfm_channel_is_custom_channel( $channel );

			// read the output fields
			if ( ! $is_custom ) {
				$outputs = apply_filters( 'wppfm_get_feed_attributes', $this->_files_class->get_output_fields_for_specific_channel( $channel ), $feed_id, $main_feed_data[0]['feed_type_id'] );
			} else {
				$outputs = $this->get_custom_fields_with_metadata( $feed_id );
			}

			// add metadata to the feeds output fields
			$output_fields = $this->fill_output_fields_with_metadata( $feed_id, $outputs );
			$inputs        = $this->get_advised_inputs( $main_data->channel, $main_feed_data[0]['feed_type_id'] );

			for ( $i = 0; $i < count( $output_fields ); $i ++ ) {
				$output_title = $output_fields[ $i ]->field_label;
				$is_active    = false;

				if ( $output_fields[ $i ]->category_id > 0 && $output_fields[ $i ]->category_id < 3 ) {
					$is_active = true;
				}
				if ( ! empty( $output_fields[ $i ]->value ) && 'undefined' !== $output_fields[ $i ]->value ) {
					$is_active = true;
				}

				$advised_source = property_exists( $inputs, $output_title ) ? $inputs->{$output_title} : '';
				$this->add_attribute( $main_data->attributes, $i, $output_title, $advised_source, $output_fields[ $i ]->value, $output_fields[ $i ]->category_id, $is_active );
			}

			$this->set_output_attribute_levels( $main_data );

			return $main_data;
		}

		/**
		 * Verifies if the categories that are stored in the feeds' category mapping selections, are still active Shop Categories.
		 * Removes categories that are no longer registered in the Shop.
		 *
		 * @param string $category_mapping The currently stored category mapping.
		 *
		 * @return string Verified category mapping.
		 * @since 2.21.0.
		 * @since 2.21.1. Added a conversion of the $categories variable to an array to prevent a PHP Fatal error on line 485 when the $category_mapping contains an empty object.
		 * @since 2.26.0. Updated the get_terms() call to include empty categories.
		 */
		public function verify_categories_in_mapping( $category_mapping ) {
			$categories        = (array) json_decode( $category_mapping );
			$categories_length = count( $categories );
			$shop_categories   = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
				)
			);

			for ( $i = 0; $i < $categories_length; $i++ ) {
				$cat_mapping_id = $categories[ $i ]->shopCategoryId;

				$cat_exists = false;

				foreach ( $shop_categories as $shop_category ) {

					if ( $cat_mapping_id === (string) $shop_category->term_id ) {
						$cat_exists = true;
						break;
					}
				}

				if ( ! $cat_exists ) {
					// Remove the non-existing category.
					unset( $categories[ $i ] );
					// Resort the categories object.
					$categories = array_values( $categories );
				}
			}

			return json_encode( $categories );
		}

		// ALERT has a relation with the wppfm_setOutputAttributeLevels() function in the logic.js file
		private function set_output_attribute_levels( &$main_data ) {
			$channel_base_class = new WPPFM_Channel();
			$channel_short_name = $channel_base_class->get_channel_short_name( $main_data->channel );

			if ( class_exists( 'WPPFM_' . ucfirst( $channel_short_name ) . '_Feed_Class' ) ) {
				$class_name = 'WPPFM_' . ucfirst( $channel_short_name ) . '_Feed_Class';
				$feed_class = new $class_name();

				if ( method_exists( $feed_class, 'set_feed_output_attribute_levels' ) ) {
					$feed_class->set_feed_output_attribute_levels( $main_data );
				}
			}
		}

		private function add_attribute(
			&$attribute, $id, $title, $advised_source, $value, $field_level, $is_active
		) {
			$attribute_object = new stdClass();

			/* phpcs:disable */
			$attribute_object->rowId             = $id;
			$attribute_object->fieldName         = $title;
			$attribute_object->advisedSource     = $advised_source;
			$attribute_object->value             = $value;
			$attribute_object->fieldLevel        = $field_level;
			$attribute_object->isActive          = $is_active;
			$attribute_object->nrQueries         = 0;
			$attribute_object->nrValueEdits      = 0;
			$attribute_object->nrValueConditions = 0;

			$attribute[] = $attribute_object;
		}

		private function convert_data_to_feed_data( $data ) {

			if ( ! key_exists( 'product_feed_id', $data ) ) {
				return false;
			}

			$feed = new stdClass();

			$feed->feedId            = $data['product_feed_id'];
			$feed->title             = $data['title'];
			$feed->mainCategory      = $data['main_category'];
			$feed->categoryMapping   = $data['category_mapping'];
			$feed->isAggregator      = $data['is_aggregator'];
			$feed->includeVariations = $data['include_variations'];
			$feed->feedTitle         = $data['feed_title'] !== null ? $data['feed_title'] : '';
			$feed->feedDescription   = $data['feed_description'] !== null ? $data['feed_description'] : '';
			$feed->url               = $data['url'];
			$feed->dataSource        = $data['source'];
			$feed->channel           = $data['channel'];
			$feed->country           = $data['country'];
			$feed->status            = $data['status_id'];
			$feed->baseStatusId      = $data['base_status_id'];
			$feed->feedTypeId        = $data['feed_type_id'];
			$feed->updateSchedule    = $data['schedule'];
			$feed->language          = $data['language'] !== null ? $data['language'] : '';
			$feed->currency          = $data['currency'] !== null ? $data['currency'] : ''; // @since 2.28.0

			return $feed;
		}

		// WPPFM_CHANNEL_RELATED
		private function get_advised_inputs( $channel_id, $feed_type_id ) {
			$feed_class = new WPPFM_Google_Feed_Class();
			// as long as only WooCommerce is supported, I can get away with only switching on a specific channel
			$advised_inputs = $feed_class->woocommerce_to_feed_fields();
			return apply_filters( 'wppfm_advised_inputs', $advised_inputs, $feed_type_id );
		}

		/**
		 * Makes sure that the url is correct and has no forbidden characters before it's being stored in the database
		 *
		 * @param $url string complete url
		 *
		 * @return string verified url
		 */
		private function verify_url( $url ) {
			$forbidden_name_chars = wppfm_forbidden_file_name_characters();
			$last_slash           = strrpos( $url, '/' );
			$url_string           = substr( $url, 0, $last_slash + 1 );
			$feed_name            = substr( $url, $last_slash + 1 );
			$correct_feed_name    = str_replace( $forbidden_name_chars, '-', $feed_name );
			return $url_string . $correct_feed_name;
		}

		public function register_channel( $channel_short_name, $channel_data ) {
			if ( ! $this->_queries_class->get_channel_id( $channel_short_name ) ) { // make sure the channel is not yet registered
				$this->_queries_class->register_a_channel( $channel_short_name, $channel_data->channel_id, $channel_data->channel_name );
			}
		}

		private function combine_custom_attributes_and_feeds( $attributes, $feeds, $product_attributes, $product_taxonomies, $third_party_fields ) {
			$prev_dup_array = array(); // used to prevent doubles

			foreach ( $feeds as $feed ) {
				$obj = new stdClass();

				$obj->attribute_name  = $feed;
				$obj->attribute_label = $feed;

				$attributes[]     = $obj;
				$prev_dup_array[] = $obj->attribute_label;
			}

			foreach ( $product_taxonomies as $taxonomy ) {
				if ( ! in_array( $taxonomy, $prev_dup_array, true ) ) {
					$obj                  = new stdClass();
					$obj->attribute_name  = $taxonomy;
					$obj->attribute_label = $taxonomy;

					$attributes[]     = $obj;
					$prev_dup_array[] = $taxonomy;
				}
			}

			foreach ( $product_attributes as $attribute_string ) {
				$attribute_object = maybe_unserialize( $attribute_string->meta_value );

				if ( $attribute_object && ( is_object( $attribute_object ) || is_array( $attribute_object ) ) ) {
					foreach ( $attribute_object as $attribute ) {
						if ( is_array( $attribute ) && array_key_exists( 'name', $attribute ) && ! in_array( $attribute['name'], $prev_dup_array, true ) ) {
							$obj                  = new stdClass();
							$obj->attribute_name  = $attribute['name'];
							$obj->attribute_label = $attribute['name'];

							$attributes[]     = $obj;
							$prev_dup_array[] = $attribute['name'];
						} elseif ( ! is_array( $attribute ) ) {
							wppfm_write_log_file( sprintf( 'An attribute object could not be processed as it\'s not in an array format. The content of the attribute object = %s', $attribute ), 'debug' );
						}
					}
				} else {
					if ( $attribute_object ) {
						wppfm_write_log_file( $attribute_object, 'debug' );
					}
				}
			}

			foreach ( $third_party_fields as $field_label ) {
				if ( ! in_array( $field_label, $prev_dup_array, true ) ) {
					$obj                  = new stdClass();
					$obj->attribute_name  = $field_label;
					$obj->attribute_label = $field_label;

					$attributes[]     = $obj;
					$prev_dup_array[] = $field_label;
				}
			}

			return $attributes;
		}

	}

	// end of WPPFM_Data_Class

endif;

$dataclass = new WPPFM_Data();
