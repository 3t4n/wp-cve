<?php
/**
 * @package     blazing-shipment-tracking/API
 * @category    API
 * @since       1.0
 *
 * Handles BST-Tracking API Authentication Class
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly


if (!function_exists('getallheaders')) {
	function getallheaders()
	{
		$headers = '';
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

