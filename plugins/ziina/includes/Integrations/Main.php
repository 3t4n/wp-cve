<?php
/**
 * Integrations activation
 *
 * @package ZiinaPayment\Checkout\Integrations
 */

namespace ZiinaPayment\Integrations;

use ZiinaPayment\Integrations\WooBlocks\WooBlocksIntegration;

defined( 'ABSPATH' ) || exit();

/**
 * Class Main
 *
 * @package ZiinaPayment\Integrations
 */
class Main {
	/**
	 * Main constructor.
	 */
	public function __construct() {
		new WooBlocksIntegration();
	}
}

