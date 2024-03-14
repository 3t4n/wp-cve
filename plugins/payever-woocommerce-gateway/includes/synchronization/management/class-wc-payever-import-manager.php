<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Import_Manager' ) ) {
	return;
}

class WC_Payever_Import_Manager {

	use WC_Payever_Generic_Manager_Trait;
	use WC_Payever_Subscription_Manager_Trait;
	use WC_Payever_Synchronization_Manager_Trait;

	/**
	 * @param string $action
	 * @param string $external_id
	 * @param string $payload
	 * @return bool
	 */
	public function import( $action, $external_id, $payload ) {
		$this->clean_messages();
		if (
			$this->is_products_sync_enabled() && $this->is_valid_action( $action )
			&& $this->is_valid_external_id( $external_id ) && $this->is_valid_payload( $payload )
		) {
			$this->get_synchronization_manager()->handle_inward_action( $action, $payload );
		}
		$this->log_messages();

		return ! $this->errors;
	}

	/**
	 * @param string $action
	 * @return bool
	 */
	private function is_valid_action( $action ) {
		$result = true;
		if ( ! in_array( $action, $this->get_subscription_manager()->get_supported_actions(), true ) ) {
			$this->errors[] = 'The action is not supported';
			$result = false;
		}

		return $result;
	}

	/**
	 * @param string $external_id
	 * @return bool
	 */
	private function is_valid_external_id( $external_id ) {
		$result = $this->get_external_id() === $external_id;
		if ( ! $result ) {
			$this->errors[] = __( 'Provided security token is invalid.', 'payever-woocommerce-gateway' );
		}

		return $result;
	}

	/**
	 * @param string $payload
	 * @return bool
	 */
	private function is_valid_payload( $payload ) {
		$result = \json_decode( $payload, true ) !== null;
		if ( ! $result ) {
			$this->errors[] = 'Cannot decode payload';
		}

		return $result;
	}
}
