<?php

namespace ZPOS\Gateway\Stripe;
require_once __DIR__ . '/api/init.php';

class API
{
	private static $_instance = null;
	protected $stripe = null;

	private function __construct()
	{
		$stripe = new \WC_Gateway_Stripe();
		$settings = $stripe->settings;

		if ($settings['testmode'] === 'yes') {
			$settings['secret_key'] = $settings['test_secret_key'];
		}

		$this->stripe = new \Stripe\StripeClient($settings['secret_key']);
	}

	protected function __clone()
	{
	}

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function getStripe()
	{
		return $this->stripe;
	}

	public function createCharge($token, $amount, $currency, $description = null)
	{
		$amount = $this->getRequestAmount($amount, $currency);

		$charge = $this->stripe->charges->create([
			'amount' => $amount,
			'currency' => $currency,
			'description' => $description,
			'source' => $token,
		]);

		$transaction = $this->getBalanceTransactionData($charge->balance_transaction);

		$source = $charge->source->id;
		$captured = $charge->captured ? 'yes' : 'no';
		$transaction_id = $charge->id;

		$currency = $transaction->currency;
		$fee = $transaction->fee;
		$net = $transaction->net;

		return (object) compact('source', 'transaction_id', 'captured', 'fee', 'net', 'currency');
	}

	public function getBalanceTransactionData($balanceTransaction)
	{
		$transaction = $this->stripe->balanceTransactions->retrieve($balanceTransaction);
		$currency = $transaction->currency;
		$fee = $this->getResponseAmount($transaction->fee, $currency);
		$net = $this->getResponseAmount($transaction->net, $currency);

		return (object) compact('fee', 'net', 'currency');
	}

	public function refund($charge, $currency, $amount = null, $reason = '')
	{
		$amount = $amount ? $this->getRequestAmount($amount, $currency) : $amount;
		$refund = $this->stripe->refunds->create([
			'charge' => $charge,
			'amount' => $amount,
			'metadata' => ['reason' => $reason],
		]);
		$id = $refund->id;
		$currency = $refund->currency;
		$amount = $this->getResponseAmount($refund->amount, $currency);
		$status = $refund->status;
		return (object) compact('id', 'amount', 'currency', 'status');
	}

	private function getRequestAmount($amount, $currency)
	{
		return in_array($currency, \WC_Stripe_Helper::no_decimal_currencies())
			? $amount
			: $amount * 100;
	}

	private function getResponseAmount($amount, $currency)
	{
		return in_array($currency, \WC_Stripe_Helper::no_decimal_currencies())
			? $amount
			: $amount / 100;
	}
}
