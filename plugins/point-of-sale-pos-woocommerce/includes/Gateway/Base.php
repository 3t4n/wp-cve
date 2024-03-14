<?php

namespace ZPOS\Gateway;

abstract class Base extends \WC_Payment_Gateway
{
	public function __construct()
	{
		add_filter('default_option_' . $this->get_option_key(), function () {
			return [
				'title' => $this->method_title,
				'description' => $this->method_description,
				'enabled' => 'yes',
			];
		});
	}

	abstract public static function getID(): string;

	public static function getInfo(): array
	{
		$id = static::getID();
		$option = 'woocommerce_' . $id . '_settings';

		return [$id, $option];
	}

	public function get_option_key(): string
	{
		$id = static::getID();
		return 'woocommerce_' . $id . '_settings';
	}
}
