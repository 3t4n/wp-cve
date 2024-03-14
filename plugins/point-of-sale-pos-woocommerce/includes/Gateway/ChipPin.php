<?php

namespace ZPOS\Gateway;

use WC_Order;
use ZPOS\Model\Gateway;

class ChipPin extends Base
{
	public $id = 'pos_chip_pin';
	public $method_title = 'Chip and Pin';
	public $has_fields = true;
	public $supports = ['products', 'pos'];

	public static function getID(): string
	{
		return 'pos_chip_pin';
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

		if (!SplitPayment::is_split_payment()) {
			$order->update_status(Gateway::getGatewayOrderStatus($this->id));
		}

		return [
			'result' => 'success',
		];
	}
}
