<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Controllers;

use Logeecom\Infrastructure\TaskExecution\Exceptions\QueueStorageUnavailableException;
use Packlink\BusinessLogic\Controllers\DefaultParcelController;
use Packlink\BusinessLogic\DTO\Exceptions\FrontDtoValidationException;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Packlink_Parcel_Controller
 *
 * @package Packlink\WooCommerce\Controllers
 */
class Packlink_Parcel_Controller extends Packlink_Base_Controller {

	/**
	 * Base default parcel controller.
	 *
	 * @var DefaultParcelController
	 */
	private $controller;

	/**
	 * Packlink_Parcel_Controller constructor.
	 */
	public function __construct() {
		$this->controller = new DefaultParcelController();
	}

	/**
	 * Provides default parcel data.
	 */
	public function get() {
		$parcel = $this->controller->getDefaultParcel();

		$this->return_json( $parcel ? $parcel->toArray() : array() );
	}

	/**
	 * Updates default parcel data.
	 *
	 * @throws QueueStorageUnavailableException When queue storage is not available.
	 * @throws FrontDtoValidationException When default parcel data is not valid.
	 */
	public function submit() {
		$this->validate( 'yes', true );
		$raw     = $this->get_raw_input();
		$payload = json_decode( $raw, true );
		$this->controller->setDefaultParcel( $payload );

		$this->get();
	}
}
