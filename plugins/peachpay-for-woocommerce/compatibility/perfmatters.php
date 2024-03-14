<?php
/**
 * PeachPay Perfmatters compatibility.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Rest API exception.
 *
 * Docs: https://perfmatters.io/docs/filters/#perfmatters_rest_api_exceptions
 */
add_filter(
	'perfmatters_rest_api_exceptions',
	function ( $exceptions ) {
		$exceptions[] = PEACHPAY_ROUTE_BASE;
		return $exceptions;
	}
);
