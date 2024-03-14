<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Exception;

abstract class ApiException extends \RuntimeException {
	abstract public function create_wp_error(): \WP_Error;
}