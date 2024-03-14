<?php

namespace Dropp\Actions;

use Dropp\Shipping_Method\Dropp;

class Create_Dropp_Location_Script_Url_Action
{
	public function __invoke(): string
	{
		$shipping_method = Dropp::get_instance();
		$query_args      = [];
		$url             = 'https://app.dropp.is/dropp-locations.min.js';

		if ($shipping_method->store_id) {
			$query_args['data-store-id'] = $shipping_method->store_id;
		}
		if ($shipping_method->test_mode) {
			$query_args['data-env'] = 'stage';
		}
		if (empty($query_args)) {
			return $url;
		}

		return $url.'?'.build_query($query_args);
	}
}
