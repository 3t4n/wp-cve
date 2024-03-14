<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Controllers;

use Packlink\BusinessLogic\Controllers\AutoConfigurationController;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Packlink_Auto_Configure_Controller
 *
 * @package Packlink\WooCommerce\Controllers
 */
class Packlink_Auto_Configure_Controller extends Packlink_Base_Controller {
	/**
	 * Starts the auto-configuration.
	 */
	protected function start() {
		$controller = new AutoConfigurationController();

		$this->return_json( array( 'success' => $controller->start( true ) ) );
	}
}
