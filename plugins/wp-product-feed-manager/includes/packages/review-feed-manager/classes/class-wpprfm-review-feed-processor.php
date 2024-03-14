<?php

/**
 * WPPRFM Review Feed Processor Class.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Review_Feed_Processor' ) ) :


	/**
	 * Review Feed Processor Class
	 */
	class WPPRFM_Review_Feed_Processor extends WPPFM_Background_Process {

		use WPPFM_Feed_Processor_Functions;
		use WPPFM_Processing_Support;
		use WPPRFM_Processing_Support;
		use WPPRFM_XML_Element_Functions;

		/**
		 * Action identifier
		 *
		 * @var string
		 */
		protected $action = 'feed_generation_process';

		/**
		 * General feed data
		 *
		 * @var stdClass
		 */
		private $_feed_data;

		/**
		 * Path to the feed file
		 *
		 * @var string
		 */
		private $_feed_file_path;

		/**
		 * Data required to make the feed
		 *
		 * @var array
		 */
		private $_pre_data;

		/**
		 * Contains the relations between WooCommerce and review fields
		 *
		 * @var array
		 */
		private $_relation_table;

		/**
		 * Container for all main elements for a review feed
		 *
		 * @var array
		 */
		private $_review_feed_elements;

		/**
		 * Container for the product filter selector options
		 *
		 * @var int
		 */
		private $_processed_reviews = 0;
		/**
		 * Starts a task for every item in the feed queue.
		 *
		 * @param mixed  $item
		 * @param array  $feed_data
		 * @param string $feed_file_path
		 * @param array  $pre_data
		 * @param array  $channel_details
		 * @param array  $relation_table
		 *
		 * @return bool|string
		 */
		protected function task( $item, $feed_data, $feed_file_path, $pre_data, $channel_details, $relation_table ) {
			if ( ! $item ) {
				return false;
			}

			$this->_feed_data      = $feed_data;
			$this->_feed_file_path = $feed_file_path;
			$this->_pre_data       = $pre_data;
			$this->_relation_table = $relation_table;

			return $this->do_task( $item );
		}

		/**
		 * Triggers the correct function for an item from the feed queue.
		 *
		 * @param array $review_task_data
		 *
		 * @return bool|string
		 */
		private function do_task( $review_task_data ) {
			if ( array_key_exists( 'product_id', $review_task_data ) ) {
				return $this->add_product_reviews_to_feed( $review_task_data['product_id'] );
			} elseif ( array_key_exists( 'file_format_line', $review_task_data ) ) {
				// the Wordfence plugin sometimes identifies the <link> string as a XSS vulnerability and blocks wp_remote_post action starting the feed process
				// To counter that, I changed the <link> string in the (google) xml feed header to <wf-connection-string> (in the Google channels class-feed.php file)
				// and now I need to change that back to <link> again.
				$review_task_data['file_format_line'] = str_replace( '<wf-connection-string>', '<link>', $review_task_data['file_format_line'] );
				return $this->add_file_format_line_to_feed( $review_task_data );
			} elseif ( array_key_exists( 'error_message', $review_task_data ) ) {
				return $this->add_error_message_to_feed( $review_task_data );
			} else {
				return false;
			}
		}

		/**
		 * Fetches the comments data from a specific product.
		 *
		 * @param $product_id
		 *
		 * @return string
		 */
		private function add_product_reviews_to_feed( $product_id ) {
			if ( ! $product_id ) {
				return false;
			}

			$review_args = array(
				'post_id'     => $product_id,
				'status'      => 'approve',
				'post_status' => 'publish',
				'post_type'   => 'product',
			);

			$comments = get_comments( $review_args );

			if ( ! $comments ) { // no comments found
				return 'no comments';
			}

			$post_columns_query_string   = $this->_pre_data['database_fields']['post_column_string'] ? substr( $this->_pre_data['database_fields']['post_column_string'], 0, - 2 ) : '';
			$product_data                = (array) $this->get_products_main_data( $product_id, $product_id, $post_columns_query_string );
			$this->_review_feed_elements = WPPRFM_Attributes_List::wpprfm_get_review_feed_main_elements();

			$row_filtered = $this->is_product_filtered( $this->_pre_data['filters'], $product_data );

			// only process the product if it's not filtered out
			if ( ! $row_filtered ) {
				foreach ( $comments as $comment ) {
					$review_placeholder = array();
					$comment_meta       = get_comment_meta( $comment->comment_ID );

					if ( '0' !== $comment->comment_parent ) { // only process reviews.
						continue;
					}

					if ( ! $comment->comment_content ) { // only process reviews with comment data (as req. by Google).
						continue;
					}

					// push the comment data into product_data
					$this->add_review_data_to_product_data( $product_data, $comment, $comment_meta, $this->_pre_data['active_fields'], $this->_relation_table );

					foreach ( $this->_pre_data['active_fields'] as $field ) {
						// gets the metadata from a field
						$field_meta_data = $this->get_meta_data_from_specific_field( $field, $this->_feed_data->attributes );

						// processes the correct data to a field by checking filters and metadata
						$review_object = $this->process_product_field(
							$product_data,
							$field_meta_data,
							'',
							'',
							'',
							'',
							$this->_relation_table
						);

						$key = key( $review_object );

						if ( '0' === $review_object[ $key ] || $review_object[ $key ] ) {
							$review_placeholder[ $key ] = $review_object[ $key ];
						}

						// register the review_url type, singleton when only a single review is on the url or else group
						$review_placeholder['review_url_type'] = count( $comments ) > 1 ? 'group' : 'singleton';
					}

					if ( $review_placeholder ) {
						$review_placeholder = apply_filters( 'wppfm_review_feed_item_value', $review_placeholder, $this->_feed_data->feedId, $product_id );
						$this->_processed_reviews++;

						$this->write_review_object( $review_placeholder, $this->_feed_data->feedId, $comment->comment_ID );
					} else {
						wppfm_write_log_file( sprintf( 'Review placeholder for product review %s failed because it was empty.', $comment->comment_ID ) );
					}
				}
			} else {
				$message = sprintf( 'Product %s is filtered out', $product_id );
				do_action( 'wppfm_feed_generation_message', $this->_feed_data->feedId, $message );

				return 'filtered';
			}

			return 'product added';
		}

		private function write_review_object( $review_placeholder, $feed_id, $review_id ) {
			do_action( 'wpprfm_add_product_review_to_feed', $feed_id, $review_id );

			$review_text = '<review>';

			// add the review id
			$review_text .= sprintf( '<review_id>%s</review_id>', $review_id );

			foreach ( $this->_review_feed_elements as $element_key => $element_method_name ) {
				if ( method_exists( $this, $element_method_name ) ) {
					$review_text .= 'wpprfm_handle_simple_element' === $element_method_name ?
						$this->wpprfm_handle_simple_element( $element_key, $review_placeholder ) :
						$this->$element_method_name( $review_placeholder );
				}
			}

			$review_text .= '</review>';

			if ( false === file_put_contents( $this->_feed_file_path, $review_text, FILE_APPEND ) ) {
				wppfm_write_log_file( sprintf( 'Could not write product review %s to the feed', $review_id ) );

				return false;
			} else {
				return true;
			}
		}

		public function complete() {
			parent::complete();

			// remove the properties from the options table
			$properties_key = get_site_option( 'wppfm_background_process_key' );
			delete_site_option( 'wppfm_background_process_key' );
			delete_site_option( 'file_path_' . $properties_key );
			delete_site_option( 'feed_data_' . $properties_key );
			delete_site_option( 'pre_data_' . $properties_key );
			delete_site_option( 'channel_details_' . $properties_key );
			delete_site_option( 'relations_table_' . $properties_key );

			$feed_status = '0' !== $this->_feed_data->status && '3' !== $this->_feed_data->status && '4' !== $this->_feed_data->status ? $this->_feed_data->status : $this->_feed_data->baseStatusId;
			$feed_title  = $this->_feed_data->title . '.' . pathinfo( $this->_feed_file_path, PATHINFO_EXTENSION );
			$this->register_feed_update( $this->_feed_data->feedId, $feed_title, $this->_processed_reviews, $feed_status );
			$this->clear_the_queue();

			// now the feed is ready to go, remove the feed id from the feed queue
			WPPFM_Feed_Controller::remove_id_from_feed_queue( $this->_feed_data->feedId );
			WPPFM_Feed_Controller::set_feed_processing_flag();

			do_action( 'wppfm_complete_a_feed', $this->_feed_data->feedId );

			if ( ! WPPFM_Feed_Controller::feed_queue_is_empty() ) {
				do_action( 'wppfm_next_in_queue_feed_update_activated', $this->_feed_data->feedId );

				// so there is another feed in the queue
				$feed_master_class = new WPPFM_Feed_Master_Class( WPPFM_Feed_Controller::get_next_id_from_feed_queue() );
				$feed_master_class->update_feed_file();
			}
		}
	}

endif;
