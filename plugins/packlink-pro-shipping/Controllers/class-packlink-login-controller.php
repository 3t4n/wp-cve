<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Controllers;

use Logeecom\Infrastructure\ORM\Exceptions\RepositoryNotRegisteredException;
use Logeecom\Infrastructure\TaskExecution\Exceptions\QueueStorageUnavailableException;
use Packlink\BusinessLogic\Controllers\LoginController;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Packlink_Login_Controller
 *
 * @package Packlink\WooCommerce\Controllers
 */
class Packlink_Login_Controller extends Packlink_Base_Controller {

	/**
	 * Performs user login.
	 *
	 * @throws RepositoryNotRegisteredException When repo is not registered.
	 * @throws QueueStorageUnavailableException When storage is unavailable.
	 */
	public function login() {
		$this->validate( 'yes', true );
		$raw        = $this->get_raw_input();
		$payload    = json_decode( $raw, true );
		$controller = new LoginController();
		$status     = $controller->login(
			! empty(
				$payload['apiKey']
			) ? $payload['apiKey'] : ''
		);

		$this->return_json( array( 'success' => $status ) );
	}

}
