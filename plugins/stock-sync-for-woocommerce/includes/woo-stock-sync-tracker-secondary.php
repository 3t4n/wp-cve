<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Tracker_Secondary {
	private $processed_changes = [];
	private $delta_job = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Send changes to the Primary Inventory
		add_filter( 'woocommerce_update_product_stock_query', [$this, 'trigger_delta_job'], 10, 4 );
		add_action( 'woocommerce_updated_product_stock', [$this, 'run_delta_job'], 10, 1 );

		// Stock quantity
		add_action( 'woocommerce_product_set_stock', [$this, 'create_job_qty'], 20, 1 );
		add_action( 'woocommerce_variation_set_stock', [$this, 'create_job_qty'], 20, 1 );
	}

	/**
	 * Trigger job creation
	 */
	public function trigger_delta_job( $sql, $product_id_with_stock, $new_stock, $operation ) {
		$this->create_delta_job_qty( $product_id_with_stock, $new_stock, $operation );

		return $sql;
	}

	/**
	 * Send changes to the Primary Inventory
	 */
	public function create_delta_job_qty( $product_id_with_stock, $new_stock, $operation ) {
		// Not Secondary Inventory
		if ( ! wss_is_secondary() ) {
			return false;
		}

		$product = wc_get_product( $product_id_with_stock );

		if ( ! $product || ! wss_should_sync( $product ) ) {
			return false;
		}

		if ( ! in_array( $operation, ['increase', 'decrease'], true ) ) {
			return false;
		}

		$current_stock = wss_current_stock( $product_id_with_stock );
		$change = $new_stock - $current_stock;

		if ( isset( $GLOBALS['wcs_source_order_id'] ) && $GLOBALS['wcs_source_order_id'] ) {
			$source_url = wss_order_url( $GLOBALS['wcs_source_order_id'] );
			$source_desc = sprintf( __( 'Order #%s', 'woo-stock-sync' ), $GLOBALS['wcs_source_order_id'] );
		}

		$this->delta_job = [
			'product_id' => $product->get_id(),
			'operation' => $operation,
			'value' => $change,
			'source_desc' => isset( $source_desc ) ? $source_desc : null,
			'source_url' => isset( $source_url ) ? $source_url : null,
			'new_stock' => $new_stock,
		];

		return true;
	}

	/**
	 * Run delta job
	 * 
	 * We cannot sync in woocommerce_update_product_stock_query because SQL has not yet
	 * run. There is a small change that REST API syncing takes place between
	 * woocommerce_update_product_stock_query and woocommerce_updated_product_stock which will
	 * mess up quantities so we will do syncing here after SQL has been run.
	 */
	public function run_delta_job( $product_id_with_stock ) {
		if ( $this->delta_job ) {
			$this->processed_changes[] = [
				'product_id' => $product_id_with_stock,
				'qty' => $this->delta_job['new_stock'],
			];

			wss_dispatch_sync( $this->delta_job );

			$this->delta_job = false;
		}
	}

	/**
	 * Create stock sync job for stock quantity
	 */
	public function create_job_qty( $product ) {
		// Not Secondary Inventory
		if ( ! wss_is_secondary() ) {
			return false;
		}
		
		if ( ! wss_should_sync( $product ) ) {
			return false;
		}

		// Check that this has not been processed in create_delta_job_qty() already
		foreach ( $this->processed_changes as $change ) {
			if ( $change['product_id'] == $product->get_id() && $change['qty'] == $product->get_stock_quantity( 'edit' ) ) {
				return false;
			}
		}

		$source_url = add_query_arg( [
			'post' => $product->get_parent_id() ? $product->get_parent_id() : $product->get_id(),
			'action' => 'edit',
		], admin_url( 'post.php' ) );

		// Run syncing
		wss_dispatch_sync( [
			'product_id' => $product->get_id(),
			'operation' => 'set',
			'value' => $product->get_stock_quantity( 'edit' ),
			'source_desc' => __( 'via admin', 'woo-stock-sync' ),
			'source_url' => $source_url,
		] );
	}
}
