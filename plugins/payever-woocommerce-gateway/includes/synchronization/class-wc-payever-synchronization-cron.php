<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Cron' ) ) {
	return;
}

/**
 * WC_Payever_Synchronization_Cron class.
 */
class WC_Payever_Synchronization_Cron {

	use WC_Payever_Logger_Trait;
	use WC_Payever_Synchronization_Manager_Trait;
	use WC_Payever_Synchronization_Queue_Trait;
	use WC_Payever_WP_Wrapper_Trait;

	/**
	 * How many queue items we process during one cron job run
	 */
	const QUEUE_PROCESSING_SIZE = 50;

	/**
	 * How many times we give queue item a chance to be processed
	 */
	const QUEUE_PROCESSING_MAX_TRIES = 2;

	/**
	 * Cron job for processing sync queue
	 *
	 * @throws Exception
	 */
	public function process_synchronization_queue() {
		if ( ! $this->get_synchronization_manager()->is_products_sync_enabled() ) {
			$this->get_wp_wrapper()->wp_send_json(
				array(
					'status'  => 'error',
					'message' => __( 'Products synchronization has been disabled.', 'payever-woocommerce-gateway' ),
				),
				200
			);

			return;
		}

		$this->get_logger()->debug( __( 'START: Processing payever sync action queue', 'payever-woocommerce-gateway' ) );

		$queue_items = $this->get_sync_queue_manager()->get_items_list( static::QUEUE_PROCESSING_SIZE );
		$processed   = 0;

		foreach ( $queue_items as $queue_item ) {
			try {
				$this->get_synchronization_manager()->set_is_instant_mode( true )
					->handle_action(
						$queue_item->action,
						$queue_item->direction,
						$queue_item->payload
					);

				++$processed;
				$this->get_sync_queue_manager()->delete_item( $queue_item->id );
			} catch ( \Exception $exception ) {
				$message = sprintf(
					__( 'Processing queue item failed: %s', 'payever-woocommerce-gateway' ),
					$exception->getMessage()
				);
				$this->get_logger()->warning( $message );
				$this->update_attempts( $queue_item );
			}
		}

		$message = sprintf( __( 'FINISH: Processed %d queue records', 'payever-woocommerce-gateway' ), $processed );
		$this->get_wp_wrapper()->wp_send_json(
			array(
				'status'  => 'success',
				'message' => $message,
			),
			200
		);
		$this->get_logger()->debug( $message );
	}

	/**
	 * @param $queue_item
	 * @return $this
	 */
	private function update_attempts( $queue_item ) {
		if ( $queue_item->attempts >= static::QUEUE_PROCESSING_MAX_TRIES ) {
			$message = __( 'CAUTION: Queue item exceeded max processing tries count and going to be removed. This may lead to data loss and out of sync state.', 'payever-woocommerce-gateway' );
			$this->get_logger()->critical( $message );
			$this->get_sync_queue_manager()->delete_item( $queue_item->id );

			return $this;
		}
		$queue_item->attempts = $queue_item->attempts + 1;
		$this->get_sync_queue_manager()->update_item( (array) $queue_item );

		return $this;
	}
}
