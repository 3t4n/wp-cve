<?php

namespace WilokeEmailCreator\Shared;

trait TraitGetCurrency
{
	public function getCurrency(): string
	{
		return !function_exists('get_woocommerce_currency') ? 'USD' : get_woocommerce_currency();
	}

	public function getCurrencyPosition()
	{
		return str_replace('_space', '', get_option('woocommerce_currency_pos'));
	}

	public function getCurrencySymbol(): string
	{
		return !function_exists('get_woocommerce_currency_symbol') ? '$' : get_woocommerce_currency_symbol();
	}
}
