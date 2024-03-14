<?php
$get_afterpay_assets = function($country)
{
	// These are assets values in the Afterpay - WooCommerce plugin
	$global_assets = array(
		"cart_page_express_button"					=>	'<tr><td colspan="2" class="btn-afterpay_express_td"><button id="afterpay_express_button" class="btn-afterpay_express btn-afterpay_express_cart" type="button" disabled><img src="https://static.afterpay.com/button/checkout-with-afterpay/[THEME].svg" alt="Checkout with Afterpay" /></button></td></tr>',
	);

	$assets = array(
		"US" => array(
			"help_center_url" => 'https://help.afterpay.com/hc/en-us/requests/new',
			"retailer_url"	=>	'https://www.afterpay.com/for-retailers',
		),
	    "CA" => array(
			"help_center_url" => 'https://help.afterpay.com/hc/en-ca/requests/new',
			"retailer_url"	=>	'https://www.afterpay.com/en-CA/for-retailers',
		),
		"AU" => array(
			"help_center_url" => 'https://help.afterpay.com/hc/en-au/requests/new',
			"retailer_url"	=>	'https://www.afterpay.com/en-AU/business',
		),
		"NZ" => array(
			"help_center_url" => 'https://help.afterpay.com/hc/en-nz/requests/new',
			"retailer_url"	=>	'https://www.afterpay.com/en-NZ/business',
		),
	);

	$region_assets = array_key_exists($country, $assets) ? $assets[$country] : $assets['AU'];

	return array_merge($global_assets, $region_assets);
};

return $get_afterpay_assets($this->get_country_code());
