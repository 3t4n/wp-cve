<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Export_Manager' ) ) {
	return;
}

class WC_Payever_Export_Manager {

	use WC_Payever_Generic_Manager_Trait;
	use WC_Payever_Inventory_Api_Client_Trait;
	use WC_Payever_Products_Api_Client_Trait;
	use WC_Payever_Subscription_Manager_Trait;
	use WC_Payever_Wpdb_Trait;

	const DEFAULT_LIMIT = 5;

	/** @var int|null */
	private $nextPage;

	/** @var int */
	private $aggregate = 0;

	/**
	 * @return int|null
	 */
	public function get_next_page() {
		return $this->nextPage;
	}

	/**
	 * @return int
	 */
	public function get_aggregate() {
		return $this->aggregate;
	}

	/**
	 * @return int
	 */
	public function get_total_pages() {
		return (int) ceil( count( $this->get_products( - 1 ) ) / self::DEFAULT_LIMIT );
	}

	/**
	 * @param int $current_page
	 * @param int $aggregate
	 * @return bool
	 */
	public function export( $current_page, $aggregate ) {
		$result = true;
		$this->errors = array();
		$this->nextPage = null;
		try {
			if ( ! $this->is_products_sync_enabled() ) {
				$this->errors[] = __(
					'Synchronization must be enabled in order to export products',
					'payever-woocommerce-gateway'
				);
				return false;
			}
			$this->aggregate = $aggregate;
			$pages = $this->get_total_pages();
			if ( ! $pages ) {
				$this->get_logger()->info(
					'No products to export',
					array(
						'pages'         => $pages,
						'product_count' => count( $this->get_products( - 1 ) ),
					)
				);
			}
			if ( $current_page < $pages ) {
				$this->aggregate += $this->process_batch( $current_page );
				$this->nextPage = $current_page + 1;
				if ( $this->nextPage >= $pages ) {
					$this->nextPage = null;
				}
			}
		} catch ( \Exception $exception ) {
			$result = false;
			$this->get_subscription_manager()->disable();
			$this->errors[] = $exception->getMessage();
			$this->nextPage = null;
		}

		return $result;
	}

	/**
	 * @param int $current_page
	 * @return int
	 * @throws Exception
	 */
	private function process_batch( $current_page ) {
		$wc_products = $this->get_products( self::DEFAULT_LIMIT, $current_page );
		$successCount = $this->export_products( $wc_products );
		$this->export_inventory( $wc_products );

		return $successCount;
	}

	/**
	 * @param array $wc_products
	 * @return int
	 * @throws Exception
	 */
	private function export_products( array $wc_products ) {
		$products_iterator  = new WC_Payever_Export_Products( $wc_products );

		return $this->get_product_api_client()->exportProducts( $products_iterator, $this->get_external_id() );
	}

	/**
	 * @param array $wc_products
	 * @throws Exception
	 */
	private function export_inventory( array $wc_products ) {
		$wc_products_inventory = array();
		foreach ( $wc_products as $wc_product ) {
			if ( self::need_send_stock( $wc_product ) ) {
				$wc_products_inventory[] = $wc_product;
			}

			foreach ( $wc_product->get_children() as $wc_child_product_id ) {
				$wc_child_product = $this->get_wp_wrapper()->wc_get_product( $wc_child_product_id );
				if ( self::need_send_stock( $wc_child_product ) ) {
					$wc_products_inventory[] = $wc_child_product;
				}
			}
		}
		$inventory_iterator = new WC_Payever_Export_Inventory( $wc_products_inventory );
		$this->get_inventory_api_client()->exportInventory( $inventory_iterator, $this->get_external_id() );
	}

	/**
	 * @param int $limit
	 * @param null $offset
	 * @return array|object|stdClass|null
	 */
	private function get_products( $limit, $offset = null ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$args = array(
				'limit' => $limit,
				'type'  => array(
					'simple',
					'external',
					'variable',
					'downloadable',
					'virtual',
				),
			);
			if ( $offset ) {
				$args['page'] = $offset;
			}

			return $this->get_wp_wrapper()->wc_get_products( $args );
		}

		return $this->get_product_collection( $limit, $offset );
	}

	private function get_product_collection( $limit, $offset ) {
		// phpcs:disable WordPress.DB.PreparedSQL
		// @codeCoverageIgnoreStart
		$query = 'SELECT id FROM ' . $this->get_wpdb()->posts . " AS posts
				WHERE posts.post_type = 'product'
				AND posts.post_parent = 0;";
		if ( $limit > 0 ) {
			$query .= ' LIMIT ' . esc_sql( $limit );
			if ( $offset ) {
				$query .= ' OFFSET ' . esc_sql( $offset );
			}
			$rows   = $this->get_wpdb()->get_results( $query );
			$result = array();
			if ( is_array( $rows ) ) {
				foreach ( $rows as $row ) {
					if ( is_object( $row ) && property_exists( $row, 'id' ) ) {
						$result[] = wc_get_product( $row->id );
					}
				}
			}

			return $result;
		}
		$result = $this->get_wpdb()->get_results( $query );
		// phpcs:enable WordPress.DB.PreparedSQL
		if ( ! $result ) {
			$result = array();
		}

		return $result;
		// @codeCoverageIgnoreEnd
	}

	/**
	 * @param WC_Product $product
	 * @return bool
	 */
	private function need_send_stock( $product ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $product->get_manage_stock()
				|| 'outofstock' === $product->get_stock_status();
		}

		$product_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;

		// @codeCoverageIgnoreStart
		return 'yes' === $this->get_wp_wrapper()->get_post_field( '_manage_stock', $product_id, 'db' )
			|| 'outofstock' === $this->get_wp_wrapper()->get_post_field( '_stock_status', $product_id, 'db' );
		// @codeCoverageIgnoreEnd
	}
}
