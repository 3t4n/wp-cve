<?php

namespace ZPOS\Model;

use ZPOS\API;

class Gateway
{
	public function __construct()
	{
		add_action('woocommerce_payment_gateways', [$this, 'paymentGateways'], 10000);
		add_action('woocommerce_thankyou', [$this, 'add_payment_complete_by']);
	}

	public static function registerPOSGateway($classGateway)
	{
		add_filter('zpos_support_gateways', function ($pos_gateways) use ($classGateway) {
			return array_merge($pos_gateways, [$classGateway]);
		});
	}

	public static function posGateways()
	{
		$pos_gateways = [
			\ZPOS\Gateway\Cash::class,
			\ZPOS\Gateway\Smart::class,
			\ZPOS\Gateway\EPD::class,
			\ZPOS\Gateway\Check::class,
			\ZPOS\Gateway\ChipPin::class,
			\ZPOS\Gateway\BankTransfer::class,
			\ZPOS\Gateway\CashDelivery::class,
			\ZPOS\Gateway\GiftCard::class,
			\ZPOS\Gateway\QRCode::class,
			\ZPOS\Gateway\SplitPayment::class,
		];

		if (class_exists(\WC_Gateway_Stripe::class)) {
			$pos_gateways[] = \ZPOS\Gateway\Stripe::class;
		}

		return apply_filters('zpos_support_gateways', $pos_gateways);
	}

	public function paymentGateways(array $gateways)
	{
		global $plugin_page;
		if ((is_admin() && $plugin_page == 'wc-settings') || (!is_admin() && !API::is_pos())) {
			return $gateways;
		}

		$pos_support_gateways = self::posGateways();

		$gateways = array_merge($gateways, $pos_support_gateways);

		if (API::is_pos()) {
			return array_filter($gateways, function ($gateway_class) use ($pos_support_gateways) {
				return in_array($gateway_class, $pos_support_gateways);
			});
		}

		return $gateways;
	}

	public static function isGatewayEnabled($id)
	{
		$gateways_data = get_option('pos_gateways');
		return isset($gateways_data[$id]) &&
			isset($gateways_data[$id]['pos']) &&
			$gateways_data[$id]['pos'];
	}

	public static function getGatewayOrderStatus($id)
	{
		if ($id === 'pos_stripe' || $id === 'pos_stripe_terminal') {
			return 'completed';
		}
		$gateways_data = get_option('pos_gateways');
		return isset($gateways_data[$id]) && isset($gateways_data[$id]['order_status'])
			? $gateways_data[$id]['order_status']
			: 'processing';
	}

	public function add_payment_complete_by($order_id)
	{
		$order = wc_get_order($order_id);

		if (empty($order->get_meta('_pos_by'))) {
			return;
		}

		$order->update_meta_data('pos_payment_complete_by', $order->get_payment_method());
		$order->save();
	}

	public static function get_enabled_ids(): array
	{
		return array_map(
			function (\WC_Payment_Gateway $gateway): string {
				return $gateway->id;
			},
			array_filter(
				WC()
					->payment_gateways()
					->get_available_payment_gateways(),
				function (\WC_Payment_Gateway $gateway): bool {
					return 'yes' === $gateway->enabled;
				}
			)
		);
	}
}
