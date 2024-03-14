<?php

class LitCommerce_EnsureWooCommerceActive implements LitCommerce_Automation
{
	public function getName()
	{
		return __('Check if the WooCommerce plugin is activated', 'litcommerce');
	}

	public function runStep()
	{
		if (is_plugin_active('woocommerce/woocommerce.php')) {
			return new LitCommerce_Result_Object(true);
		}

		activate_plugin('woocommerce/woocommerce.php');
		delete_transient('_wc_activation_redirect');

		if (is_plugin_active('woocommerce/woocommerce.php')) {
			return new LitCommerce_Result_Object(true);
		}

		return new LitCommerce_Result_Object(false, __('Failed to activate the WooCommerce plugin.', 'litcommerce'));
	}
}
