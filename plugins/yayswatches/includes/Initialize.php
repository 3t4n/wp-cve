<?php
namespace Yay_Swatches;

use Yay_Swatches\Utils\SingletonTrait;

/**
 * Yay_Swatches Plugin Initializer
 */
class Initialize {

	use SingletonTrait;

	/**
	 * The Constructor that load the engine classes
	 */
	protected function __construct() {

		// Engine
		\Yay_Swatches\Engine\RestAPI::get_instance();
		\Yay_Swatches\Engine\Ajax::get_instance();
		\Yay_Swatches\Engine\Hooks::get_instance();

		// BEPages
		\Yay_Swatches\Engine\BEPages\Settings::get_instance();
		\Yay_Swatches\Engine\BEPages\ProductSwatches::get_instance();

		// FEPages
		\Yay_Swatches\Engine\FEPages\WooCommerceSwatches::get_instance();

		// Compatibles
		\Yay_Swatches\Engine\Compatibles\HelloElementorChild::get_instance();
		\Yay_Swatches\Engine\Compatibles\WooCommerceProductBundles::get_instance();
	}
}
