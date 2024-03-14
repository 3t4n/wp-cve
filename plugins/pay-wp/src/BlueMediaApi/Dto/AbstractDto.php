<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Dto;

abstract class AbstractDto {
	public function toArray() : array
	{
		return (new \WPPayVendor\BlueMedia\Serializer\Serializer())->toArray($this);
	}
	public function capitalizedArray() : array
	{
		$result = $this->toArray();
		return \array_combine(\array_map('ucfirst', \array_keys($result)), \array_values($result));
	}
}