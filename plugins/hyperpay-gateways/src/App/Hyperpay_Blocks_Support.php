<?php

namespace Hyperpay\Gateways\App;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Hyperpay\Gateways\Main;
use WC_Order;

/**
 * Dummy Payments Blocks integration
 *
 * @since 1.0.3
 */
class Hyperpay_Blocks_Support extends AbstractPaymentMethodType
{
	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */

	public $gateways, $id = "hyperpay_blocks";

	protected $name = 'hyperpay_blocks';




	/**
	 * Initializes the payment method type.
	 */
	public function initialize()
	{
		$this->gateways = $this->get_gateways();

		foreach ($this->gateways as $gateway) {
			$gateway->initialize();
		}

		add_action('wc_ajax_hyperpay_can_make_payment', [$this, 'canMakePayment']);
	}


	public function get_gateways()
	{
		$gateways = WC()->payment_gateways->payment_gateways();
		$enabled_gateways = [];

		foreach ($gateways as $gateway) {
			if (str_starts_with($gateway->id, "hyperpay") && $gateway->enabled == "yes") {
				$enabled_gateways[$gateway->id] = $gateway;
			}
		}

		return $enabled_gateways;
	}

	public function canMakePayment()
	{

		if (!isset($_GET["payment_method"]) || !\array_key_exists($_GET["payment_method"], $this->gateways)) {
			return  wp_send_json(["canMakePayment" => false]);
		}

		$payment_method = sanitize_text_field($_GET["payment_method"]);
		return $this->gateways[$payment_method]->canMakePayment();
	}
	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active()
	{
		return true;
	}


	public function process_payment($order_id)
    {

        $order = new WC_Order($order_id);


        return [
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true)
        ];
    }

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles()
	{
		$script_path       = '/src/assets/js/blocks/blocks.js';
		$script_url = HYPERPAY_PLUGIN_DIR . $script_path;
		$script_asset_path = HYPERPAY_PLUGIN_DIR . '/src/assets/js/blocks/blocks.asset.php';

		$script_asset      = file_exists($script_asset_path)
			? require($script_asset_path)
			: array(
				'dependencies' => array(),
				'version'      => '2.1.2'
			);

		wp_register_script(
			$this->id,
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);


		return [$this->id];
	}


	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data()
	{
		$data = [];

		foreach ($this->gateways as $gateway) {
			$gateWayData = [
				'name' => $gateway->id,
				'title'       => $gateway->title,
				'supports'    => array_filter($gateway->supports, [$gateway, 'supports']),
				'icon' => $gateway->iconSrc(),
				'description' => $gateway->description,
				'gatewayMerchantId' => $gateway->entityId,
				'currencyCode' => $gateway->currency,
				'brands' => implode(' ', $gateway->brands),
				'nonce' => wp_create_nonce('woocommerce-process_checkout'),
				'isDynamicCheck' => $gateway->isDynamicCheck,
				'isExpress' => $gateway->isExpress,
				'action_button' => $gateway->action_button,
				'extraScriptData' => $gateway->extraScriptData(),
				'site_url' => site_url(),
			];

			$key = $gateway->isExpress ? "express" : "blocks";
			$data[$key][] = $gateWayData;
		}
		return $data;
	}
}
