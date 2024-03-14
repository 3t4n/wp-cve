<?php
namespace WC_BPost_Shipping\Controller;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Controller_Base makes a contract for all controllers
 */
abstract class WC_BPost_Shipping_Controller_Base {
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	protected $adapter;

	/**
	 * WC_BPost_Shipping_Controller_Base constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter ) {
		$this->adapter = $adapter;
	}

	/**
	 * This function provides a contract to use to load a template using controller.
	 */
	public abstract function load_template();


	/**
	 * Provide a simple way to get all templates in one place
	 * @param string $template_path
	 * @param string[] $data_for_template
	 */
	protected function get_template( $template_path, array $data_for_template ) {
		$this->adapter->wc_get_template(
			$template_path,
			$data_for_template,
			'',
			BPOST_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR
		);
	}
}
