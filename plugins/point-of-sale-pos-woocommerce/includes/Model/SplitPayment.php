<?php

namespace ZPOS\Model;

class SplitPayment
{
	private $method_id;
	private $amount;

	public function __construct(string $method_id, int $amount)
	{
		if (!in_array($method_id, Gateway::get_enabled_ids())) {
			throw new \Exception("Payment method \"$method_id\" is not available");
		}

		$this->method_id = $method_id;
		$this->amount = $amount;
	}

	public function get_method_id(): string
	{
		return $this->method_id;
	}

	public function get_amount(): int
	{
		return $this->amount;
	}
}
