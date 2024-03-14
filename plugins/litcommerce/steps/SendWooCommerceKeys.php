<?php

class LitCommerce_SendWooCommerceKeysStep implements LitCommerce_Automation
{
	const URL_APP = 'https://app.litcommerce.com';
	public function getName()
	{
		return __('Send WooCommerce API keys to LitCommerce', 'litcommerce');
	}

	public function runStep()
	{
		$consumerKey    = get_option('woocommerce_litcommerce_consumer_key');
		$consumerSecret = get_option('woocommerce_litcommerce_consumer_secret');

		if (empty($consumerKey) || empty($consumerSecret)) {
			return new LitCommerce_Result_Object(false, 'Could not find WooCommerce API key. Please try again.');
		}

		return new LitCommerce_Result_Object(true, 'Redirecting to LitCommerce...', ['consumer_key' => $consumerKey, 'consumer_secret' => $consumerSecret, 'url' => $this->getRedirectUrl($consumerKey, $consumerSecret)]);
	}

	function getRedirectUrl( $consumerKey, $consumerSecret ) {

		$url     = self::URL_APP . '/merchants/woocommerce?consumer_key=' . $consumerKey;
		$url     .= '&consumer_secret=' . $consumerSecret;
		$url     .= '&channel_url=' . urlencode( site_url() );
		if(@$_GET['reconnect'] == 1){
			$url .= '&reconnect=1';
		}
		return $url;
	}
}
