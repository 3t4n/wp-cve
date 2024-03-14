<?php
/**
 * Admin Statistic List Table
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Admin_Statistic_List_Table' ) ) {
	/**
	 * Admin class for show the list table
	 *
	 * @since 2.1.0
	 */
	class YITH_WCAS_Admin_Statistic_List_Table extends WP_List_Table {
		protected $type = 'searched';
		protected $from = '';
		protected $to = '';


		public function __construct( $args = array() ) {
			parent::__construct( array() );
			$this->type = $args['type'];
			$this->from = $args['from'] ? $args['from'] . ' 00:00:01' : '';
			$this->to   = $args['to'] ? $args['to'] . ' 23:59:59' : '';
		}

		public function get_title(  ) {
			$title = '';
			switch ( $this->type ) {
				case 'searched':
					$title = __( 'Top searches', 'yith-woocommerce-ajax-search' );
					break;
				case 'clicked':
					$title = __( 'Top clicked products', 'yith-woocommerce-ajax-search' );
					break;
				case 'no_results':
					$title = __( 'Searches with "No Results"', 'yith-woocommerce-ajax-search' );
			}

			return $title;
		}

		/**
		 * Get sorttable columns.
		 *
		 * @return array
		 */
		public function get_sortable_columns() {
			$sortable_columns = array(
				'value' => array( 'value', false ),
			);

			return $sortable_columns;
		}

		/**
		 * Get the columns.
		 *
		 * @return array
		 */
		public function get_columns() {
			$columns = array();
			switch ( $this->type ) {
				case 'searched':
				case 'no_results':
					$columns = array(
						'query' => esc_html__( 'Query', 'yith-woocommerce-ajax-search' ),
						'value' => esc_html__( 'N. Searches', 'yith-woocommerce-ajax-search' ),
					);
					break;
				case 'clicked':
					$columns = array(
						'query' => esc_html__( 'Product', 'yith-woocommerce-ajax-search' ),
						'value' => esc_html__( 'Clicks', 'yith-woocommerce-ajax-search' ),
					);
					break;
			}


			return $columns;
		}

		/**
		 * Prepare items to show
		 */
		public function prepare_items() {
			global $_wp_column_headers;
			$screen     = get_current_screen();
			$totalitems = array();
			switch ( $this->type ) {
				case 'searched':
					$totalitems = YITH_WCAS_Data_Search_Query_Log::get_top_searches( $this->from, $this->to );
					break;

				case 'clicked':
					$totalitems = YITH_WCAS_Data_Search_Query_Log::get_top_clicked_products( $this->from, $this->to );
					break;

				case 'no_results':
					$totalitems = YITH_WCAS_Data_Search_Query_Log::get_top_no_results( $this->from, $this->to );
					break;
			}

			if ( empty( $totalitems ) ) {
				$this->items = $totalitems;
			} else {
				$order = ! empty( $_GET['order'] ) ? $_GET['order'] : 'DESC';       //phpcs:ignore

				$num_total_items = count( $totalitems );
				$offset          = 0;
				$perpage         = 20;
				$paged           = ! empty( $_GET['paged'] ) ? $_GET['paged'] : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( empty( $paged ) || ! is_numeric( $paged ) || $paged <= 0 ) {
					$paged = 1;
				}

				$totalpages = ceil( $num_total_items / $perpage );
				if ( ! empty( $paged ) && ! empty( $perpage ) ) {
					$offset = ( $paged - 1 ) * $perpage;
				}

				$this->set_pagination_args(
					array(
						'total_items' => $num_total_items,
						'total_pages' => $totalpages,
						'per_page'    => $perpage,
					)
				);

				switch ( $this->type ) {
					case 'searched':
						$this->items = YITH_WCAS_Data_Search_Query_Log::get_top_searches( $this->from, $this->to, $perpage, $offset );
						break;

					case 'clicked':
						$this->items = YITH_WCAS_Data_Search_Query_Log::get_top_clicked_products( $this->from, $this->to, $perpage, $offset );
						break;

					case 'no_results':
						$this->items = YITH_WCAS_Data_Search_Query_Log::get_top_no_results( $this->from, $this->to, $perpage, $offset );
						break;
				}

				if ( $this->items && strtolower( $order ) !== 'desc' ) {
					$this->items = array_reverse( $this->items, true );
				}

				$columns                           = $this->get_columns();
				$hidden                            = array();
				$sortable                          = $this->get_sortable_columns();
				$this->_column_headers             = array( $columns, $hidden, $sortable );
				$_wp_column_headers[ $screen->id ] = $columns;
			}

		}

		/**
		 * Fill the columns.
		 *
		 * @param   object  $item         Current Object.
		 * @param   string  $column_name  Current Column.
		 *
		 * @return string
		 */
		public function column_default( $item, $column_name ) {

			switch ( $this->type ) {
				case 'searched':
					$content_column = $this->get_column_content_for_searched( $item, $column_name );
					break;

				case 'clicked':
					$content_column = $this->get_column_content_for_clicked( $item, $column_name );
					break;

				case 'no_results':
					$content_column = $this->get_column_content_for_no_result( $item, $column_name );
					break;
			}

			return $content_column;

		}

		protected function get_column_content_for_searched( $item, $column_name ) {
			switch ( $column_name ) {
				case 'query':
					return $item['query'];
				case 'value':
					return $item['searches'];
			}
		}

		protected function get_column_content_for_clicked( $item, $column_name ) {
			switch ( $column_name ) {
				case 'query':
					$product = wc_get_product( $item['product_id'] );

					return $product ? $product->get_name() : $item['product_id'];
				case 'value':
					return $item['clicks'];
			}
		}

		protected function get_column_content_for_no_result( $item, $column_name ) {
			switch ( $column_name ) {
				case 'query':
					return $item['query'];
				case 'value':
					return $item['no_results'];
			}
		}


		public function no_items(  ) {
			echo ' <div class="ywcas-empty-statistic-details">';
			echo __('No items found', 'yith-woocommerce-ajax-search');
			echo '</div>';
		}
	}
}