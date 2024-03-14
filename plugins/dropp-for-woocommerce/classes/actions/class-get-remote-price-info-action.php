<?php

namespace Dropp\Actions;

use Dropp\API;
use Dropp\Exceptions\Request_Exception;
use Dropp\Exceptions\Response_Exception;

class Get_Remote_Price_Info_Action
{
	/**
	 * @throws Response_Exception
	 * @throws Request_Exception
	 */
	public function __invoke(): array
	{
		$api = new API();

		$response = $api->get("orders/store/priceinfo/", 'json');

		return array_filter($response, fn(mixed $value) => is_array($value));
	}

}
