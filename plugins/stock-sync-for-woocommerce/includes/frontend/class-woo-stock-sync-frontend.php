<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Frontend {
  /**
   * Constructor
   */
  public function __construct() {
		// Run batch sync
		add_action( 'shutdown', [ $this, 'run_batch_sync' ], 20, 0 );

		// Schedule log cleaning
		if ( ! wp_next_scheduled ( 'woo_stock_sync_log_clean' ) ) {
			wp_schedule_event( time(), 'daily', 'woo_stock_sync_log_clean' );
		}

		// Hook into log cleaning
		add_action( 'woo_stock_sync_log_clean', [ $this, 'clean_log' ] );
  }

	/**
	 * Runs batch sync
	 */
	public function run_batch_sync() {
		// No batch sync tasks
		if ( ! isset( $GLOBALS['wss_bulk_sync'] ) || empty( $GLOBALS['wss_bulk_sync'] ) ) {
			return;
		}

		// Update log records
		foreach ( $GLOBALS['wss_bulk_sync']['bulk_changes'] as $row ) {
			if ( isset( $row['log_id'] ) && ! empty( $row['log_id'] ) ) {
				Woo_Stock_Sync_Logger::log_update(
					$row['log_id'],
					wss_get_site_keys(),
					false,
					[
						__( 'Dispatching', 'woo-stock-sync' ),
					],
					'info'
				);
			}
		}

		$process = new Woo_Stock_Sync_Process();
		$process->data( $GLOBALS['wss_bulk_sync'] );
		$process->dispatch();
	}

	/**
	 * Clean log
	 */
	public function clean_log() {
		$retention = intval( get_option( 'woo_stock_sync_log_retention', 0 ) );
		
		if ( $retention > 0 ) {
			Woo_Stock_Sync_Logger::delete_records( $retention );
		}
	}
}
