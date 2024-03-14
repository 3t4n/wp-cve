<?php
/**
 * Indexer class
 *
 * @author  YITH
 * @package YITH/Search/DataIndex
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Data_Index_Indexer' ) ) {
	/**
	 * Store the data to database
	 *
	 * @since 2.0.0
	 */
	class YITH_WCAS_Data_Index_Indexer {


		/**
		 * Number of element to process
		 *
		 * @var int
		 */
		protected $limit = 50;

		/**
		 * The logger
		 *
		 * @var YITH_WCAS_Logger
		 */
		protected $logger;

		/**
		 * Constuctor
		 */
		public function __construct() {
			$this->logger = YITH_WCAS_Logger::get_instance();

			add_action( 'init', array( $this, 'check_scheduled_index' ) );
			add_action( 'yith_wcas_data_index_lookup', array( $this, 'add_scheduled_data' ), 10, 2 );

			add_action( 'yith_wcas_index_schedule', array( $this, 'process_data' ) );
		}

		/**
		 * Check the scheduling
		 *
		 * @return void
		 */
		public function check_scheduled_index() {
			if ( 'yes' === ywcas()->settings->get_schedule_indexing() ) {
				YITH_WCAS_Data_Index_Scheduler::get_instance()->schedule_index( 'yith_wcas_index_schedule' );
			} else {
				YITH_WCAS_Data_Index_Scheduler::get_instance()->unschedule( 'yith_wcas_index_schedule' );
			}
		}

		/**
		 * Start to fill the data table.
		 *
		 * @return string
		 */
		public function process_data() {
			$process_id = uniqid( 'WCAS' );
			$this->process_posts( $process_id );

			return $process_id;
		}

		/**
		 * Init the process truncate the index table and un-schedule current process
		 *
		 * @param string  $process_id Current process id.
		 * @param array   $data Data to index.
		 * @param boolean $taxonomy Processing taxonomies.
		 *
		 * @return void
		 * @since 2.0.0
		 */
		protected function init_process( $process_id, $data, $taxonomy = false ) {
			$num_of_items = count( $data );
			update_option( 'ywcas_last_index_process', $process_id );
			$this->logger->log( 'Init Data index process. Found ' . $num_of_items . ' objects', 'data-index' );
			YITH_WCAS_Data_Index_Lookup::get_instance()->clear_table();
			YITH_WCAS_Data_Index_Token::get_instance()->clear_table();
			YITH_WCAS_Data_Index_Relationship::get_instance()->clear_table();
			YITH_WCAS_Data_Index_Scheduler::get_instance()->unschedule( 'yith_wcas_data_index_lookup' );
			$this->set_process_transient( $process_id, $num_of_items, $taxonomy );
		}

		/**
		 * Init the process truncate the index table and un-schedule current process
		 *
		 * @param string $process_id Current process id.
		 * @param string $taxonomy Taxonomy.
		 *
		 * @return void
		 * @since 2.0.0
		 */
		protected function complete_process( $process_id, $taxonomy = false ) {
			$this->logger->log( 'Completed indexing with process id ' . $process_id . ' objects', 'data-index' );
			YITH_WCAS_Data_Index_Lookup::get_instance()->index_table();
			YITH_WCAS_Data_Index_Token::get_instance()->index_table();
			YITH_WCAS_Data_Index_Relationship::get_instance()->index_table();
		}

		/**
		 * Process scheduled data
		 *
		 * @param string $chunk Data to process.
		 *
		 * @return void
		 */
		public function add_scheduled_data( $chunk ) {
			$items = get_transient( $chunk );
			if ( $items ) {
				foreach ( $items as $item ) {
					$post = get_post( $item );
					if ( $post ) {
						$this->add( $post );
					}
				}

				$this->update_process_transient( $chunk, count( $items ) );
				delete_transient( $chunk );
			}
		}


		/**
		 * Start to schedule index process for products, post or pages.
		 *
		 * @param int $process_id Process id.
		 *
		 * @return void
		 */
		public function process_posts( $process_id ) {
			$data_query = new YITH_WCAS_Data_Index_Query();
			$data       = $data_query->get_data();
			$chunk      = 1;

			if ( $data ) {
				$this->init_process( $process_id, $data );

				$data_to_process = array();
				foreach ( $data as $object_id ) {
					$data_to_process[] = $object_id;
					if ( count( $data_to_process ) === $this->limit ) {
						$this->schedule( $process_id, $chunk ++, $data_to_process );
						$data_to_process = array();
					}
				}

				if ( ! empty( $data_to_process ) ) {
					$this->schedule( $process_id, $chunk, $data_to_process );
				}
			}
		}

		/**
		 * Schedule data index
		 *
		 * @param string $process_id Current process id.
		 * @param int    $chunk Current chunk.
		 * @param array  $data_to_process List of elements to process.
		 */
		public function schedule( $process_id, $chunk, $data_to_process ) {
			$transient_name = "yith_wcas_data_index_{$process_id}_{$chunk}";
			if ( ! get_transient( $transient_name ) ) {
				set_transient( $transient_name, $data_to_process, DAY_IN_SECONDS * 7 );
				YITH_WCAS_Data_Index_Scheduler::get_instance()->schedule( 'yith_wcas_data_index_lookup', $transient_name, 'data_index_lookup' );
			}
		}


		/**
		 * Return the formatted data to insert on table
		 *
		 * @param Object $data Data.
		 *
		 * @return array|boolean
		 */
		protected function get_formatted_data( $data ) {
			$import_data = false;
			if ( in_array( $data->post_type, array( 'product', 'product_variation' ), true ) ) {
				$import_data = $this->get_formatted_product( $data );
			} else {
				$import_data = apply_filters( 'ywcas_data_index_formatted_data', $import_data, $data );
			}

			return $import_data;
		}

		/**
		 * Return the product to insert on table
		 *
		 * @param Object $data Data content.
		 *
		 * @return array
		 */
		protected function get_formatted_product( $data ) {
			$product   = wc_get_product( $data->ID );
			$stock_qty = $product->get_stock_quantity();
			if ( 'variable' === $product->get_type() ) {
				$prices    = $product->get_variation_prices( true );
				$min_price = current( $prices['price'] );
				$max_price = end( $prices['price'] );
			} else {
				$min_price = $this->get_product_price_for_display( $product, $product->get_sale_price() );
				$max_price = $this->get_product_price_for_display( $product, $product->get_regular_price() );
			}

			$is_purchasable    = 'variation' !== $product->get_type() ? $product->is_purchasable() : ywcas_is_variation_purchasable( $product );
			$formatted_product = array(
				'post_id'         => $data->ID,
				'name'            => $product->get_type() === 'product_variation' ? $product->get_formatted_name() : $product->get_name(),
				'description'     => $product->get_description(),
				'summary'         => wp_strip_all_tags( $product->get_short_description() ),
				'url'             => $product->get_permalink(),
				'sku'             => $product->get_sku(),
				'thumbnail'       => ywcas_get_product_thumbnail_url( $product ),
				'min_price'       => $min_price,
				'max_price'       => $max_price,
				'onsale'          => $product->is_on_sale(),
				'instock'         => $product->is_in_stock(),
				'stock_quantity'  => empty( $stock_qty ) ? 0 : $stock_qty,
				'is_purchasable'  => $is_purchasable,
				'rating_count'    => $product->get_rating_count(),
				'average_rating'  => $product->get_average_rating(),
				'total_sales'     => $product->get_total_sales(),
				'post_type'       => $data->post_type,
				'post_parent'     => $data->post_parent,
				'product_type'    => $product->get_type(),
				'parent_category' => maybe_serialize( $this->get_parent_categories( $product, ywcas_get_language( $data->ID ) ) ),
				'tags'            => maybe_serialize( $this->get_tags( $product ) ),
				'custom_fields'   => $this->get_product_custom_fields( $product ),
				'lang'            => ywcas_get_language( $data->ID ),
				'featured'        => $product->is_featured(),
				'custom_taxonomies' => maybe_serialize( apply_filters( 'ywcas_index_custom_taxonomies', array(), $product ) ),
				'boost'             => $this->get_boost( $product ),

			);


			return apply_filters( 'yith_wcas_data_index_loockup_formatted_product', $formatted_product, $data );
		}


		/**
		 * Return the price for display ( so incl or excl tax )
		 *
		 * @param WC_Product $product The product.
		 * @param float      $price The price.
		 *
		 * @return float
		 */
		public function get_product_price_for_display( $product, $price ) {
			if ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
				$price = wc_get_price_including_tax( $product, array( 'qty' => 1, 'price' => $price ) );
			}else{
				$price = wc_get_price_excluding_tax( $product, array( 'qty' => 1, 'price' => $price ) );
			}

			return $price;
		}

		/**
		 * Return custom fields to tokenize
		 *
		 * @param WC_Product $product Product.
		 *
		 * @return string
		 */
		public function get_product_custom_fields( $product ) {
			$selected_custom_fields = ywcas()->settings->get_search_field_by_type( 'custom_fields' );
			$custom_field_values    = array();
			if ( ! empty( $selected_custom_fields['custom_field_list'] ) ) {
				foreach ( $selected_custom_fields['custom_field_list'] as $custom_field_name ) {
					$custom_field_values[] = $product->get_meta( $custom_field_name );
				}
			}

			return implode( ',', array_filter( $custom_field_values ) );
		}

		/**
		 * Return the list of terms of a product
		 *
		 * @param WC_Product $product Product.
		 *
		 * @return int[]|string|string[]|WP_Error|WP_Term[]
		 */
		private function get_tags( $product ) {
			$parent     = $product->get_parent_id();
			$product_id = 0 === $parent ? $product->get_id() : $parent;

			$tags       = wp_get_object_terms(
				$product_id,
				'product_tag',
				array(
					'fields'       => 'ids',
					'exclude_tree' => true,
				)
			);


			return apply_filters( 'ywcas_wpml_get_translated_terms_list_by_term_name', $tags, 'product_tag', ywcas_get_language( $product->get_id( 'edit' ) ) );
		}

		/**
		 * Return the list of terms of a product
		 *
		 * @param WC_Product $product Product.
		 * @param string     $lang Product language.
		 *
		 * @return int[]|string|string[]|WP_Error|WP_Term[]
		 */
		private function get_parent_categories( $product, $lang ) {
			$parent     = $product->get_parent_id();
			$product_id = 0 === $parent ? $product->get_id() : $parent;
			$terms      = wp_get_object_terms(
				$product_id,
				'product_cat',
				array(
					'exclude_tree' => false,
				)
			);
			$terms      = apply_filters( 'ywcas_wpml_get_translated_terms_list', $terms, 'product_cat', $lang );

			return wp_list_pluck( (array) $terms, 'term_id' );
		}


		/**
		 * Return the boost of product
		 *
		 * @param   WC_Product  $product  Product.
		 *
		 * @return string
		 * @since 2.1
		 */
		private function get_boost( $product ) {
			$parent  = $product->get_parent_id();
			$product = $parent ? wc_get_product( $product->get_id() ) : $product;

			$boost = $product->get_meta( 'ywcas_product_boost' );

			return empty( $boost ) ? 0 : $boost;
		}


		/**
		 * Return the name of transient.
		 *
		 * @param string  $process_id Process id.
		 * @param boolean $taxonomy Processing taxonomies.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		public function get_process_transient_name( $process_id, $taxonomy = false ) {
			return $taxonomy ? 'yith_wcas_indexing_taxonomy_process_' . $process_id : 'yith_wcas_indexing_process_' . $process_id;
		}

		/**
		 * Return the name of transient.
		 *
		 * @param string  $transient_name Transient name.
		 * @param boolean $taxonomy Processing taxonomies.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		public function get_process_id( $transient_name, $taxonomy = false ) {
			$process_id = '';
			if ( ! empty( $transient_name ) ) {
				$temp       = explode( '_', $transient_name );
				$process_id = $temp[4] ?? '';
			}

			return $process_id;
		}

		/**
		 * Set the process transient to trace the progress of data index
		 *
		 * @param string  $process_id Process id.
		 * @param int     $num_of_items Total items.
		 * @param boolean $taxonomy Processing taxonomies.
		 * @param int     $processed_items Processed items.
		 * @param string  $start_date Start Date.
		 *
		 * @return void
		 */
		public function set_process_transient( $process_id, $num_of_items, $taxonomy = false, $processed_items = 0, $start_date = '' ) {
			$process = array(
				'progress'        => ( $processed_items && $num_of_items > 0 ) ? $processed_items / $num_of_items * 100 : 0,
				'total_items'     => $num_of_items,
				'processed_items' => $processed_items,
				'start_date'      => ! empty( $start_date ) ? $start_date : date_i18n( 'Y-m-d H:i', false, true ),
			);

			set_transient( $this->get_process_transient_name( $process_id, $taxonomy ), $process, 7 * DAY_IN_SECONDS );
		}

		/**
		 * Update the process transient after that a chunk has been executed
		 *
		 * @param string $transient_name Transient name.
		 * @param int    $processed_items Num of processed items.
		 * @param bool   $taxonomy Taxonomy.
		 *
		 * @return void
		 */
		public function update_process_transient( $transient_name, $processed_items, $taxonomy = false ) {
			$process_id = $this->get_process_id( $transient_name, $taxonomy );
			$process    = get_transient( $this->get_process_transient_name( $process_id, $taxonomy ) );
			if ( $process ) {
				$processed_items += $process['processed_items'];
				$this->set_process_transient( $process_id, $process['total_items'], $taxonomy, $processed_items );
				if ( $processed_items >= $process['total_items'] ) {
					$this->complete_process( $process_id, $taxonomy );
				}
			}
		}

		/**
		 * Add the post on database
		 *
		 * @param WP_Post $data Data to index.
		 *
		 * @return void
		 */
		public function add( $data ) {
			$data_type      = $data->post_type;
			$formatted_data = $this->get_formatted_data( $data );

			if ( $formatted_data ) {
				$object_id                   = YITH_WCAS_Data_Index_Lookup::get_instance()->insert( $formatted_data );
				$additional_data_to_tokenize = $this->get_additional_data_to_tokenize( $data, $formatted_data['lang'] );

				$formatted_data = ! empty( $additional_data_to_tokenize ) ? array_merge( $formatted_data, $additional_data_to_tokenize ) : $formatted_data;

				if ( $object_id ) {
					YITH_WCAS_Data_Index_Tokenizer::insert( $object_id, $formatted_data, $data_type );
				}
			}
		}


		/**
		 * Process data from single events
		 *
		 * @param WP_Post $item Item to remove.
		 *
		 * @return void
		 */
		public function delete( $item ) {
			YITH_WCAS_Data_Index_Relationship::get_instance()->remove_lookup( $item->ID );
			YITH_WCAS_Data_Index_Lookup::get_instance()->remove_data( $item->ID );
		}

		/**
		 * Get additional data to tokenize
		 *
		 * @param WP_Post $data Data to index.
		 * @param string  $lang Current language.
		 *
		 * @return array|boolean
		 */
		public function get_additional_data_to_tokenize( $data, $lang ) {
			$additional_fields = array();

			if ( ! in_array( $data->post_type, array( 'product', 'product_variation' ), true ) ) {
				return $additional_fields;
			}

			return apply_filters( 'ywcas_additional_data_to_tokenizer', $additional_fields, $data, $lang );
		}


	}
}