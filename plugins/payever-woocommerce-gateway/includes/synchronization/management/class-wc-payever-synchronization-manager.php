<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Manager' ) ) {
	return;
}

use Payever\Sdk\ThirdParty\Enum\DirectionEnum;

class WC_Payever_Synchronization_Manager {

	use WC_Payever_Bidirectional_Action_Processor_Trait;
	use WC_Payever_Generic_Manager_Trait;
	use WC_Payever_Subscription_Manager_Trait;
	use WC_Payever_Synchronization_Queue_Trait;

	/** @var bool */
	private $is_instant_mode = false;

	/**
	 * @param bool $is_instant_mode
	 * @return $this
	 */
	public function set_is_instant_mode( $is_instant_mode ) {
		$this->is_instant_mode = $is_instant_mode;

		return $this;
	}

	/**
	 * @param string $action
	 * @param string $payload
	 */
	public function handle_inward_action( $action, $payload ) {
		$this->handle_action( $action, DirectionEnum::INWARD, $payload );
	}

	/**
	 * @param string $action
	 * @param string $payload
	 */
	public function handle_outward_action( $action, $payload ) {
		$this->handle_action( $action, DirectionEnum::OUTWARD, $payload );
	}

	/**
	 * @param string $action
	 * @param string $direction
	 * @param string $payload
	 */
	public function handle_action( $action, $direction, $payload ) {
		$this->clean_messages();
		if ( ! $this->is_products_sync_enabled() ) {
			return;
		}

		try {
			if ( ! $this->is_instant_mode && $this->get_helper_wrapper()->is_products_sync_cron_mode() ) {
				$this->get_sync_queue_manager()->enqueue_action( $action, $direction, $payload );
			} elseif ( DirectionEnum::INWARD === $direction ) {
				$this->get_bidirectional_action_processor()->processInwardAction( $action, $payload );
			} elseif ( DirectionEnum::OUTWARD === $direction ) {
				try {
					$this->get_bidirectional_action_processor()->processOutwardAction( $action, $payload );
				} catch ( \Exception $exception ) {
					$this->get_subscription_manager()->disable();
					throw $exception;
				}
			}
		} catch ( \Exception $exception ) {
			$this->errors[] = $exception->getMessage();
		}
		$this->log_messages();
	}
}
