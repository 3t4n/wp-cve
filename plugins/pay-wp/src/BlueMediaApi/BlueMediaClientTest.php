<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi;


class BlueMediaClientTest extends BlueMediaClient {
	protected function get_api_address(): string {
		return 'https://pay-accept.bm.pl';
	}
}