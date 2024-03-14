<?php

namespace ZPOS\Gateway;

use WC_Order;
use ZPOS\API;

class Smart extends Base
{
	public $id = 'pos_card_smart';
	public $method_title = 'Smart Card';
	public $method_description = 'Card gateway for iOS and Android devices';
	public $has_fields = true;
	public $supports = ['products', 'pos', 'kiosk'];

	public static function getID(): string
	{
		return 'pos_card_smart';
	}

	public function __construct()
	{
		parent::__construct();

		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');

		add_action('woocommerce_pos_update_options_payment_gateways_' . $this->id, [
			$this,
			'process_admin_options',
		]);
	}

	public function process_payment(/* int|\WC_Order */ $order_id): array
	{
		if ($order_id instanceof WC_Order) {
			$order = $order_id;
		} else {
			$order = new WC_Order($order_id);
		}

		$data = API::get_raw_data();
		$is_split_payment = SplitPayment::is_split_payment($data);
		$paid = $is_split_payment
			? $data['payment_details']['splitPayments'][$this->id]['paid'] ?? false
			: $data['payment_details']['paid'] ?? false;

		if ($paid) {
			if (!$is_split_payment) {
				$order->update_status('processing');
			}

			return [
				'result' => 'success',
			];
		} else {
			return [
				'result' => 'failed',
			];
		}
	}
}
