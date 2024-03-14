<?php

namespace WPDesk\GatewayWPPay\Blocks;

use \Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;


abstract class PaymentBlock extends AbstractPaymentMethodType {

	/**
	 * @var string
	 */
	protected $script_url;

	/**
	 * @var \WC_Payment_Gateway
	 */
	protected $gateway;

	/**
	 * @var \WC_Payment_Gateway
	 */
	protected $icon_url;

	protected const JAVASCRIPT_FILE_SUFFIX = '.js';
	protected const ASSET_FILE_SUFFIX      = '.asset.php';
	protected const SCRIPT_HANDLE_SUFFIX   = '_handle';
	protected const SCRIPT_VERSION         = '2.1.0';

	public function __construct( \WC_Payment_Gateway $gateway, string $script_url, string $icon_url = '' ) {
		$this->script_url = $script_url;
		$this->gateway    = $gateway;
		$this->icon_url   = $icon_url;
	}

	public function initialize() {
		// TODO: Implement initialize() method.
	}

	public function is_active(): bool {
		return $this->gateway->is_available();
	}

	public function get_payment_method_script_handles() {
		$script_handle = $this->name . self::SCRIPT_HANDLE_SUFFIX;

		$javascript_block_path = $this->script_url . self::JAVASCRIPT_FILE_SUFFIX;
		$block_assets_path     = $this->script_url . self::ASSET_FILE_SUFFIX;

		$script_asset            = file_exists( $block_assets_path )
			? require( $block_assets_path )
			: [
				'dependencies' => [],
				'version'      => self::SCRIPT_VERSION,
			];
		$script_asset['version'] = self::SCRIPT_VERSION . '.' . $script_asset['version'];

		wp_register_script(
			$script_handle,
			$javascript_block_path,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		return [ $script_handle ];
	}

	public function get_payment_method_data() {
		return [
			'label'       => $this->gateway->get_title(),
			'description' => $this->gateway->get_description(),
			'supports'    => $this->gateway->supports,
			'icon_url'    => $this->icon_url,
		];
	}

}
