<?php

class LitCommerce_GenerateWooCommerceKeys implements LitCommerce_Automation
{
	public function getName()
	{
		return __('Create WooCommerce API keys for the LitCommerce admin user', 'litcommerce');
	}

	public function runStep()
	{
		litCommerce_WC_Auth();

		if (!class_exists('Litcommerce_WC_Auth')) {
			return new LitCommerce_Result_Object(false, 'Could not find WooCommerce plugin. Please try again.');
		}

		$user = wp_get_current_user();

		if (!$user) {
			return new LitCommerce_Result_Object(false, 'LitCommerce Administrator user not found. Please try again.');
		}

		$apiKey = (new LitCommerce_WC_Auth())->createAPIKey($user->ID);

		// store the key and secret
		if (!empty($apiKey['consumer_key'])) {
			update_option('woocommerce_litcommerce_consumer_key', $apiKey['consumer_key']);
		}
		if (!empty($apiKey['consumer_secret'])) {
			update_option('woocommerce_litcommerce_consumer_secret', $apiKey['consumer_secret']);
		}

		return new LitCommerce_Result_Object(true, null, $apiKey);
	}
}

function litCommerce_WC_Auth()
{
	if (class_exists('WC_Auth')) {
		class LitCommerce_WC_Auth extends WC_Auth
		{
			public function createAPIKey($userId)
			{
				return $this->create_keys(
					'LitCommerce Integration',
					$userId,
					'read_write'
				);
			}
		}
	}
}
