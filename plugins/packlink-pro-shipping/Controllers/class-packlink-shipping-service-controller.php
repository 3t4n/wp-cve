<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Controllers;

use Exception;
use Logeecom\Infrastructure\TaskExecution\QueueItem;
use Packlink\BusinessLogic\Controllers\ShippingMethodController;
use Packlink\BusinessLogic\Controllers\UpdateShippingServicesTaskStatusController;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Packlink_Shipping_Service_Controller
 *
 * @package Packlink\WooCommerce\Controllers
 */
class Packlink_Shipping_Service_Controller extends Packlink_Base_Controller {

	/**
	 * Shipping method controller.
	 *
	 * @var ShippingMethodController
	 */
	private $controller;

	/**
	 * Packlink_Shipping_Service_Controller constructor.
	 */
	public function __construct() {
		$this->controller = new ShippingMethodController();
	}

	/**
	 * Provides inactive shipping services.
	 */
	public function get() {
		$this->return_dto_entities_response( $this->controller->getInactive() );
	}

	/**
	 * Provides active shipping services.
	 */
	public function get_active() {
		$this->return_dto_entities_response( $this->controller->getActive() );
	}

	/**
	 * Provides UpdateShippingServicesTask status.
	 */
	public function get_task_status() {
		if ( count( $this->controller->getAll() ) > 0 ) {
			$this->return_json( array( 'status' => QueueItem::COMPLETED ) );

			return;
		}

		$controller = new UpdateShippingServicesTaskStatusController();
		try {
			$status = $controller->getLastTaskStatus();
		} catch ( Exception $e ) {
			$status = QueueItem::FAILED;
		}

		$this->return_json( array( 'status' => $status ) );
	}
}
