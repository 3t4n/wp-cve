<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Generic_Manager_Trait' ) ) {
	return;
}

trait WC_Payever_Generic_Manager_Trait {

	use WC_Payever_Helper_Wrapper_Trait;
	use WC_Payever_Logger_Trait;
	use WC_Payever_WP_Wrapper_Trait;

	/** @var array */
	private $errors = array();

	/** @var array */
	private $debug_messages = array();

	/**
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Cleans messages
	 */
	private function clean_messages() {
		$this->errors = array();
		$this->debug_messages = array();
	}

	/**
	 * Logs messages
	 */
	private function log_messages() {
		foreach ( $this->errors as $error ) {
			$this->get_logger()->warning( $error );
		}
		foreach ( $this->debug_messages as $debugMessage ) {
			$this->get_logger()->debug(
				! empty( $debugMessage['message'] ) ? $debugMessage['message'] : '',
				! empty( $debugMessage['context'] ) ? $debugMessage['context'] : ''
			);
		}
	}

	/**
	 * @return bool
	 */
	public function is_products_sync_enabled() {
		$result = $this->get_helper_wrapper()->is_products_sync_enabled();
		if ( ! $result ) {
			$this->errors[] = 'Products and inventory synchronization is disabled';
		}

		return $result;
	}

	/**
	 * @return string|null
	 */
	public function get_external_id() {
		return $this->get_helper_wrapper()->get_product_sync_token();
	}
}
