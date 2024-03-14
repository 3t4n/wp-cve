<?php

/**
 * WP Product Feed Support Class.
 *
 * @package WP Product Feed Manager/Application/Classes
 * @version 1.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPFM_Feed_Support' ) ) :

	/**
	 * Feed Support Class
	 */
	class WPPFM_Feed_Support {

		public function get_query_string_from_query_object( $query_object ) {
			// TODO: There's probably a better way to do this!
			foreach ( $query_object as $value ) {
				return $value;
			}

			return false;
		}

		public function find_relation( $feed_name, $relations_table ) {
			$result = '';

			foreach ( $relations_table as $relation ) {
				if ( $relation['field'] === $feed_name ) {
					$result = $relation['db'];
					break;
				}
			}

			$relations_table = null;

			return $result;
		}

		public function category_is_selected( $term_id, $category_mapping ) {
			for ( $i = 0; $i < count( $category_mapping ); $i ++ ) {
				if ( (string) $term_id === $category_mapping[ $i ]->shopCategoryId ) {
					return $i;
				}
			}

			return false;
		}

		public function check_query_result_on_specific_row( $query_split, $product_data ) {
			$queries_class = new WPPFM_Feed_Queries;
			$current_data  = key_exists( $query_split[1], $product_data ) ? $product_data[ $query_split[1] ] : '';

			// @since 2.24.0
			if ( '_weight' === $query_split[1] ) {
				$current_data = $this->format_weight_value( $current_data );
			}

			// the following attributes can or will contain an array so suppress the type warning for these attributes
			$suppress_type_warning_attributes = apply_filters(
				'wppfm_suppress_type_warning_attributes',
				array(
					'_wp_attachement_metadata',
				)
			);

			if ( is_array( $current_data ) && ! in_array( $query_split[1], $suppress_type_warning_attributes ) ) { // A user had this once where he had an attribute that only showed "Array()"  as value
				$product_id    = key_exists( 'ID', $product_data ) ? $product_data['ID'] : 'unknown';
				$product_title = key_exists( 'post_title', $product_data ) ? $product_data['post_title'] : 'unknown';

				$error_message = "There is something wrong with the '" . $query_split[1] . "' attribute of product '$product_title' with id $product_id. It seems to be of a wrong type.";

				wppfm_write_log_file( $error_message, 'debug' );

				$current_data = $current_data[0];
			}

			$result = true;

			switch ( $query_split[2] ) {
				case 0: // includes
					$result = $queries_class->includes_query( $query_split, $current_data );
					break;

				case 1: // does not include
					$result = $queries_class->does_not_include_query( $query_split, $current_data );
					break;

				case 2: // is equal to
					$result = $queries_class->is_equal_to_query( $query_split, $current_data );
					break;

				case 3: // is not equal to
					$result = $queries_class->is_not_equal_to_query( $query_split, $current_data );
					break;

				case 4: // is empty
					$result = $queries_class->is_empty( $current_data );
					break;

				case 5: // is not empty
					$result = $queries_class->is_not_empty_query( $current_data );
					break;

				case 6: // starts with
					$result = $queries_class->starts_with_query( $query_split, $current_data );
					break;

				case 7: // does not start with
					$result = $queries_class->does_not_start_with_query( $query_split, $current_data );
					break;

				case 8: // ends with
					$result = $queries_class->ends_with_query( $query_split, $current_data );
					break;

				case 9: // does not end with
					$result = $queries_class->does_not_end_with_query( $query_split, $current_data );
					break;

				case 10: // is greater than
					$result = $queries_class->is_greater_than_query( $query_split, $current_data );
					break;

				case 11: // is greater or equal to
					$result = $queries_class->is_greater_or_equal_to_query( $query_split, $current_data );
					break;

				case 12: // is smaller than
					$result = $queries_class->is_smaller_than_query( $query_split, $current_data );
					break;

				case 13: // is smaller or equal to
					$result = $queries_class->is_smaller_or_equal_to_query( $query_split, $current_data );
					break;

				case 14: // is between
					$result = $queries_class->is_between_query( $query_split, $current_data );
					break;

				default:
					break;
			}

			return $result;
		}

		public function edit_value( $current_value, $edit_string, $combination_string, $combined_data_elements, $feed_language, $feed_currency ) {
			$value_editors = new WPPFM_Feed_Value_Editors;

			$query_split = explode( '#', $edit_string );

			switch ( $query_split[1] ) {
				case 'change nothing':
					$result = $current_value;
					break;

				case 'overwrite':
					$result = $value_editors->overwrite_value( $query_split );
					break;

				case 'replace':
					$result = $value_editors->replace_value( $query_split, $current_value );
					break;

				case 'remove':
					$result = $value_editors->remove_value( $query_split, $current_value );
					break;

				case 'add prefix':
					$result = $value_editors->add_prefix_value( $query_split, $current_value );
					break;

				case 'add suffix':
					$result = $value_editors->add_suffix_value( $query_split, $current_value );
					break;

				case 'recalculate':
					$result = $value_editors->recalculate_value( $query_split, $current_value, $combination_string, $combined_data_elements, $feed_language, $feed_currency );
					break;

				case 'convert to child-element':
					$result = $value_editors->convert_to_element( $query_split, $current_value );
					break;

				case 'strip tags':
					$result = $value_editors->strip_tags_from_value( $current_value );
					break;

				// @since 2.34.0.
				case 'html entity decode':
					$result = $value_editors->html_entity_decode_value( $current_value );
					break;

				case 'limit characters':
					$result = $value_editors->limit_characters_value( $query_split, $current_value );
					break;

				default:
					$result = false;
					break;
			}

			return $result;
		}

		public function get_column_names_from_feed_filter_array( $feed_filter_array ) {
			$empty_array  = array();
			$filters      = $feed_filter_array ? json_decode( $feed_filter_array[0]['meta_value'] ) : $empty_array;
			$column_names = array();

			foreach ( $filters as $filter ) {
				$query_string = $this->get_query_string_from_query_object( $filter );
				$query_parts  = explode( '#', $query_string );

				array_push( $column_names, $query_parts[1] );
			}

			return $column_names;
		}

		/**
		 * makes a unique feed for a copy of an existing feed
		 *
		 * @param string $current_feed_name
		 *
		 * @return string
		 */
		public function next_unique_feed_name( $current_feed_name ) {
			$queries_class = new WPPFM_Queries();

			$title_end = explode( '_', $current_feed_name );
			$end_nr    = end( $title_end );

			if ( count( $title_end ) > 1 && is_numeric( $end_nr ) ) {
				$new_title = substr_replace( $current_feed_name, ( $end_nr + 1 ), - strlen( $end_nr ) );
			} else {
				$new_title = $current_feed_name . '_1';
				$end_nr    = '1';
			}

			// increase the end number of the title already exists
			while ( $queries_class->title_exists( $new_title ) ) {
				$new_title = substr_replace( $new_title, ( $end_nr + 1 ), - strlen( $end_nr ) );
				$end_nr ++;
			}

			return $new_title;
		}

		/**
		 * Adds multiple single draft image urls to the product, specific for the Ricardo.ch channel
		 *
		 * @since 1.9.0
		 *
		 * @param array $product
		 * @param array $images
		 */
		public function process_ricardo_draftimages( &$product, $images ) {
			for ( $i = 0; $i < 10; $i ++ ) {
				$product["DraftImages[$i]"] = isset( $images[ $i ] ) ? $images[ $i ] : '';
			}
		}

		/**
		 * Corrects issues where the active list is not the same as the data keys
		 *
		 * @since 1.9.0
		 *
		 * @param array $active_fields
		 */
		public function correct_active_fields_list( &$active_fields ) {
			// correct for draft images in Ricardo.ch feed
			if ( ( $key = array_search( 'DraftImages', $active_fields ) ) !== false ) {
				unset( $active_fields[ $key ] );
				for ( $i = 0; $i < 10; $i ++ ) {
					array_push( $active_fields, "DraftImages[$i]" );
				}
			}
		}

		/**
		 * The Weight data of a WooCommerce product always uses a period as decimal separator, independent of the WC price decimal separator
		 * setting. This function will convert a weight value to a format with the correct decimal separator for calculations.
		 *
		 * @since 2.24.0
		 *
		 * @param $weight
		 *
		 * @return string
		 */
		private function format_weight_value( $weight )
		{
			if ( ',' === wc_get_price_decimal_separator() ) {
				return str_replace( '.', ',', $weight );
			} else {
				return $weight;
			}
		}

	}

	// end of WPPFM_Feed_Support

endif;
