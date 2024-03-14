<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Logeecom\Infrastructure\AutoTest\AutoTestService;
use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Interfaces\AsyncProcessService;
use Packlink\WooCommerce\Components\Utility\Shop_Helper;
use Tools;

/**
 * Class Packlink_Async_Process_Controller
 *
 * @package Packlink\WooCommerce\Controllers
 */
class Packlink_Async_Process_Controller extends Packlink_Base_Controller {
	/**
	 * Packlink_Async_Process_Controller constructor.
	 */
	public function __construct() {
		$this->is_internal = false;
	}

	/**
	 * Runs process defined by guid request parameter.
	 */
	public function run() {
		if ( ! Shop_Helper::is_plugin_enabled() ) {
			$this->return_json(
				array(
					'success' => false,
					'error'   => 'Plugin not enabled',
				)
			);
		}

		$guid      = $this->get_param( 'guid' );
		$auto_test = $this->get_param( 'auto-test' );

		if ( $auto_test ) {
			$auto_test_service = new AutoTestService();
			$auto_test_service->setAutoTestMode();
			Logger::logInfo( 'Received auto-test async process request.', 'Integration', array( 'guid' => $guid ) );
		} else {
			Logger::logDebug( 'Received async process request.', 'Integration', array( 'guid' => $guid ) );
		}

		if ( 'auto-configure' !== $guid ) {
			/**
			 * Async process service.
			 *
			 * @var AsyncProcessService $service
			 */
			$service = ServiceRegister::getService( AsyncProcessService::CLASS_NAME );
			$service->runProcess( $this->get_param( 'guid' ) );
		}

		$this->return_json( array( 'success' => true ) );
	}
}
