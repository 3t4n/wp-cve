<?php

namespace ZPOS\Gateway;

use WC_Order;
use ZPOS\API;

class Cash extends Base
{
	public $id = 'pos_cash';
	public $method_title = 'Cash';
	public $method_description = '';
	public $has_fields = true;
	public $supports = ['products', 'pos'];

	public static function getID(): string
	{
		return 'pos_cash';
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
		add_action('woocommerce_thankyou_pos_cash', [$this, 'calculate_change']);
	}

	public function payment_fields(): void
	{
		if ($this->description) {
			echo '<p>' . wp_kses_post($this->description) . '</p>';
		}

		$currency_pos = get_option('woocommerce_currency_pos');

		if ($currency_pos == 'left' || 'left_space') {
			$left_addon =
				'<span class="input-group-addon">' .
				get_woocommerce_currency_symbol(get_woocommerce_currency()) .
				'</span>';
			$right_addon = '';
		} else {
			$left_addon = '';
			$right_addon =
				'<span class="input-group-addon">' .
				get_woocommerce_currency_symbol(get_woocommerce_currency()) .
				'</span>';
		}

		echo '
      <div class="form-row" id="pos-cash-tendered_field">
        <label for="pos-cash-tendered" class="">' .
			__('Amount Tendered', 'zpos-wp-api') .
			'</label>
        <div class="input-group">
        ' .
			$left_addon .
			'
          <input type="text" class="form-control" name="pos-cash-tendered" id="pos-cash-tendered" maxlength="20" data-numpad="cash" data-label="' .
			__('Amount Tendered', 'zpos-wp-api') .
			'" data-placement="bottom" data-value="{{total}}">
        ' .
			$right_addon .
			'
        </div>
      </div>
    ';
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

		if ($is_split_payment) {
			$tendered = isset($data['payment_details']['splitPayments'][$this->id]['pos-cash-tendered'])
				? wc_format_decimal(
					$data['payment_details']['splitPayments'][$this->id]['pos-cash-tendered']
				)
				: 0;
			$change = isset($data['payment_details']['splitPayments'][$this->id]['pos-cash-change'])
				? wc_format_decimal($data['payment_details']['splitPayments'][$this->id]['pos-cash-change'])
				: 0;
			$paid = 0 !== $tendered;
		} else {
			$tendered = isset($data['payment_details']['pos-cash-tendered'])
				? wc_format_decimal($data['payment_details']['pos-cash-tendered'])
				: 0;
			$change = isset($data['payment_details']['pos-cash-change'])
				? wc_format_decimal($data['payment_details']['pos-cash-change'])
				: 0;
			$paid = isset($data['payment_details']['paid']) && $data['payment_details']['paid'];
		}

		$order->update_meta_data('_pos_cash_amount_tendered', $tendered);
		$order->update_meta_data('_pos_cash_change', $change);
		$order->save();

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

	public function calculate_change(int $order_id): void
	{
		$order = new WC_Order($order_id);

		$message = '';
		$tendered = $order->get_meta('_pos_cash_amount_tendered');
		$change = $order->get_meta('_pos_cash_change');

		// construct message
		if ($tendered && $change) {
			$message = __('Amount Tendered', 'zpos-wp-api') . ': ';
			$message .= wc_price($tendered) . '<br>';
			$message .= _x('Change', 'Money returned from cash sale', 'zpos-wp-api') . ': ';
			$message .= wc_price($change);
		}

		echo $message;
	}

	public static function payment_details(/* int|\WC_Order */ $order_id): array
	{
		if ($order_id instanceof WC_Order) {
			$order = $order_id;
		} else {
			$order = new WC_Order($order_id);
		}

		return [
			'tendered' => $order->get_meta('_pos_cash_amount_tendered'),
			'change' => $order->get_meta('_pos_cash_change'),
		];
	}
}
