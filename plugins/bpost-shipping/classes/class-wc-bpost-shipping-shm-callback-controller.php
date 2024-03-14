<?php

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Shm_Callback;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Management;
use WC_BPost_Shipping\Container\WC_BPost_Shipping_Container_Postalcode;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Builder;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Solver;

/**
 * Class WC_BPost_Shipping_Shm_Callback_Controller create the call back on which the shm redirects after a failed or succeed request
 * It redirect info received on checkout ajax call.
 */
class WC_BPost_Shipping_Shm_Callback_Controller extends WC_BPost_Shipping_Controller_Base {

	/** Callback status */
	const RESULT_SHM_CALLBACK_CONFIRM = 'confirm';
	const RESULT_SHM_CALLBACK_ERROR   = 'error';
	const RESULT_SHM_CALLBACK_CANCEL  = 'cancel';

	/** @var string */
	private $result;
	/** @var WC_BPost_Shipping_Logger */
	private $logger;
	/** @var WC_BPost_Shipping_Assets_Management */
	private $assets_management;

	/**
	 * WC_BPost_Shipping_Shm_Callback_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Assets_Management $assets_management
	 * @param WC_BPost_Shipping_Logger $logger
	 * @param string|false|null $result
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Assets_Management $assets_management,
		WC_BPost_Shipping_Logger $logger,
		$result
	) {
		parent::__construct( $adapter );
		$this->assets_management = $assets_management;
		$this->logger            = $logger;
		$this->result            = (string) $result;
	}

	public function load_template() {
		$this->assets_management->callback_page( $this->get_callback_data() );
	}

	/**
	 * create data to pass to template in case of succeed or failed
	 * @return array json
	 */
	private function get_callback_data() {
		switch ( $this->result ) {
			case self::RESULT_SHM_CALLBACK_CONFIRM:
				$shm_saver = new WC_BPost_Shipping_SHM_Saver(
					new WC_BPost_Shipping_Adapter_Shm_Callback(
						new WC_BPost_Shipping_Container_Postalcode(),
						array_map( 'stripslashes_deep', $_POST )
					),
					new WC_BPost_Shipping_Street_Builder( new WC_BPost_Shipping_Street_Solver() ),
					$this->logger
				);

				return $shm_saver->get_data_to_post();

			case self::RESULT_SHM_CALLBACK_ERROR:
			case self::RESULT_SHM_CALLBACK_CANCEL:
				return array(
					'status' => false,
				);
			default: //could be false (filter failed), or null (var not defined)
				return array();
		}
	}
}
