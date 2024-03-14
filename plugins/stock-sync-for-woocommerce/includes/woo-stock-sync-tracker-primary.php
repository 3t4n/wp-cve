<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Tracker_Primary {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Stock quantity
		add_action( 'woocommerce_product_set_stock', array( $this, 'create_job_qty' ), 20, 1 );
		add_action( 'woocommerce_variation_set_stock', array( $this, 'create_job_qty' ), 20, 1 );

		// Log changes
		add_filter( 'woocommerce_update_product_stock_query', [$this, 'trigger_delta_log_change'], 10, 4 );
		add_action( 'woocommerce_product_set_stock', array( $this, 'log_change' ), 10, 1 );
		add_action( 'woocommerce_variation_set_stock', array( $this, 'log_change' ), 10, 1 );

		if ( ! isset( $GLOBALS['wss_logged_changes'] ) ) {
			$GLOBALS['wss_logged_changes'] = [];
		}
	}

	/**
	 * Trigger log change
	 */
	public function trigger_delta_log_change( $sql, $product_id_with_stock, $new_stock, $operation ) {
		$this->log_delta_change( $product_id_with_stock, $new_stock, $operation );

		return $sql;
	}

	/**
	 * Log increase / decrease in stock quantity
	 */
	public function log_delta_change( $product_id_with_stock, $new_stock, $operation ) {
		if ( ! wss_is_primary() ) {
			return;
		}

		if ( ! in_array( $operation, ['increase', 'decrease'], true ) ) {
			return;
		}

		$product = wc_get_product( $product_id_with_stock );
		if ( ! $product ) {
			return;
		}

		$current_stock = wss_current_stock( $product_id_with_stock );

		if ( $operation === 'increase' ) {
			$msg = __( 'Stock level increased: <a href="%s">%s (%s)</a> %d &rarr; %d', 'woo-stock-sync' );
		} else {
			$msg = __( 'Stock level reduced: <a href="%s">%s (%s)</a> %d &rarr; %d', 'woo-stock-sync' );
		}

		$msg = sprintf( $msg, wss_product_url( $product->get_id() ), $product->get_name(), $product->get_sku( 'edit' ), $current_stock, $new_stock );

		if ( isset( $GLOBALS['wcs_source_order_id'] ) && $GLOBALS['wcs_source_order_id'] ) {
			$source_url = add_query_arg( [
				'post' => $GLOBALS['wcs_source_order_id'],
				'action' => 'edit',
			], admin_url( 'post.php' ) );
			$source_desc = sprintf( __( 'Order #%s', 'woo-stock-sync' ), $GLOBALS['wcs_source_order_id'] );
		}

		$log_id = Woo_Stock_Sync_Logger::log( $msg, 'stock_change', $product->get_id(), [
			'source' => get_site_url(),
			'source_desc' => isset( $source_desc ) ? $source_desc : null,
			'source_url' => isset( $source_url ) ? $source_url : null,
		], 'queued' );

		$GLOBALS['wss_logged_changes'][] = [
			'product_id' => $product_id_with_stock,
			'qty' => $new_stock,
			'log_id' => $log_id,
		];
	}

	/**
	 * Log change
	 */
	public function log_change( $product ) {
		if ( ! wss_is_primary() ) {
			return;
		}

		// Check this hasn't been logged previously
		foreach ( $GLOBALS['wss_logged_changes'] as $change ) {
			if ( $change['product_id'] == $product->get_id() && $change['qty'] == $product->get_stock_quantity( 'edit' ) ) {
				return;
			}
		}

		// Check this is not done by REST request
		if ( wss_request() ) {
			return;
		}

		$msg = sprintf( __( 'Set stock quantity: <a href="%s">%s (%s)</a> %d', 'woo-stock-sync' ), wss_product_url( $product->get_id() ), $product->get_name(), $product->get_sku( 'edit' ), $product->get_stock_quantity( 'edit' ) );

		if ( is_admin() ) {
			$source_desc = __( 'via admin', 'woo-stock-sync' );
			$source_url = add_query_arg( [
				'post' => $product->get_parent_id() ? $product->get_parent_id() : $product->get_id(),
				'action' => 'edit',
			], admin_url( 'post.php' ) );
		}

		$log_id = Woo_Stock_Sync_Logger::log( $msg, 'stock_change', $product->get_id(), [
			'source' => get_site_url(),
			'source_desc' => isset( $source_desc ) ? $source_desc : null,
			'source_url' => isset( $source_url ) ? $source_url : null,
		], 'queued' );

		$GLOBALS['wss_logged_changes'][] = [
			'product_id' => $product->get_id(),
			'qty' => $product->get_stock_quantity( 'edit' ),
			'log_id' => $log_id,
		];
	}

	/**
	 * Create stock sync job for stock quantity
	 */
	public function create_job_qty( $product ) {
		// Not Primary Inventory
		if ( ! wss_is_primary() ) {
			return false;
		}

		if ( ! wss_should_sync( $product ) ) {
			return false;
		}

		// Find if there is a log reference
		$log_id = null;
		foreach ( $GLOBALS['wss_logged_changes'] as $change ) {
			if ( $change['product_id'] == $product->get_id() && $change['qty'] == $product->get_stock_quantity( 'edit' ) && isset( $change['log_id'] ) && $change['log_id'] ) {
				$log_id = $change['log_id'];
				break;
			}
		}

		// Run syncing
		wss_dispatch_sync( [
			'product_id' => $product->get_id(),
			'operation' => 'set',
			'value' => $product->get_stock_quantity( 'edit' ),
			'log_id' => $log_id,
		] );
	}
}
