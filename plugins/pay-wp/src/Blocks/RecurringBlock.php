<?php

namespace WPDesk\GatewayWPPay\Blocks;

class RecurringBlock extends PaymentBlock{

	/**
	 * @var string
	 */
	protected $name = 'autopay_recurring';

	public function is_active(): bool {
		return true;
	}
}
