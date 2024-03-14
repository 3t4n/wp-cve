<?php

class LitCommerce_EnableWooCommerceAPI implements LitCommerce_Automation
{
	public function getName()
	{
		return __('Enable WooCommerce REST API', 'litcommerce');
	}

	public function runStep()
	{
		update_option('woocommerce_api_enabled', 'yes');
		return new LitCommerce_Result_Object(true);
	}
}
