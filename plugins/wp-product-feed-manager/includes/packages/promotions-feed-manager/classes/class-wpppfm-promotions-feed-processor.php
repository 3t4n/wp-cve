<?php

/**
 * WPPPFM Promotions Feed Processor Class.
 *
 * @package WP Merchant Promotions Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Promotions_Feed_Processor' ) ) :


	/**
	 * Merchant Promotions Feed Processor Class
	 */
	class WPPPFM_Promotions_Feed_Processor extends WPPFM_Background_Process {

		use WPPFM_Feed_Processor_Functions;
		use WPPFM_Processing_Support;

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
		 * Container for the product filter selector options
		 *
		 * @var WPPPFM_Data
		 */
		private $_product_filter_selector_options;

		/**
		 * Container for all main elements for a review feed
		 *
		 * @var array
		 */
		private $_promotions_feed_elements;

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
		 * @return bool
		 */
		protected function task( $item, $feed_data, $feed_file_path, $pre_data, $channel_details, $relation_table ) {
			if ( ! $item ) {
				return false;
			}

			if ( ! class_exists( 'WPPPFM_Data' ) && file_exists( __DIR__ . '/class-wpppfm-data.php' ) ) {
				require_once __DIR__ . '/class-wpppfm-data.php';
			}

			$promotions_data_class = new WPPPFM_Data();

			$this->_feed_data      = $feed_data;
			$this->_feed_file_path = $feed_file_path;
			$this->_pre_data       = $pre_data;
			$this->_relation_table = $relation_table;

			$promotions_data_class->convert_input_data_to_feed_attributes( $this->_feed_data );
			$this->_product_filter_selector_options = $promotions_data_class->get_merchant_promotion_filter_selector_options();

			return $this->do_task();
		}

		/**
		 * Triggers the correct function for an item from the feed queue.
		 *
		 * @return string
		 */
		private function do_task() {
			$file_header['file_format_line'] = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . "\r\n";
			return $this->generate_merchant_promotions_feed( $file_header );
		}

		private function generate_merchant_promotions_feed( $file_header ) {
			$this->add_file_format_line_to_feed( $file_header ); // XML Header line

			foreach ( $this->_feed_data->promotions as $promotion ) {
				file_put_contents( $this->_feed_file_path, '<promotion>' . "\r\n", FILE_APPEND ); // XML Start promotion element

				foreach ( $promotion as $key => $value ) {
					$this->handle_promotion_elements( $key, $value );
				}

				file_put_contents( $this->_feed_file_path, '</promotion>' . "\r\n", FILE_APPEND ); // XML End promotion element
			}

			file_put_contents( $this->_feed_file_path, '</rss>', FILE_APPEND ); // XML Footer line

			return 'promotions added';
		}

		private function handle_promotion_elements( $key, $value ) {

			if ( 'promotion_destination' === $key ) {
				foreach ( $value as $destination_value ) {
					$this->write_single_general_xml_string_to_current_file( $key, $destination_value );
				}

				return;
			}

			if ( 'product_filter_selector_include' === $key ) {
				foreach ( $value as $filter_selection_value ) {
					$filter_selection_value_attribute = $this->find_attribute_value_by_id( $filter_selection_value );
					$this->write_single_general_xml_string_to_current_file( $filter_selection_value_attribute, $filter_selection_value );
				}

				return;
			}

			if ( 'product_filter_selector_exclude' === $key ) {
				foreach ( $value as $filter_selection_value ) {
					$filter_selection_value_attribute = $this->find_attribute_value_by_id( $filter_selection_value );
					$this->write_single_general_xml_string_to_current_file( $filter_selection_value_attribute . '_exclusion', $filter_selection_value );
				}

				return;
			}

			$this->write_single_general_xml_string_to_current_file( $key, $value );
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
			$this->register_feed_update( $this->_feed_data->feedId, $feed_title, count( $this->_feed_data->promotions ), $feed_status );
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

		private function find_attribute_value_by_id( $attribute_id ) {
			foreach ( $this->_product_filter_selector_options as $attributes ) {
				$ids = array_column( $attributes->children, 'id' );
				$id  = array_search( $attribute_id, $ids, true );
				if ( false !== $id ) {
					return $attributes->children[ $id ]['attribute'];
				}
			}

			return null; // Return null if the id is not found
		}
	}

endif;
