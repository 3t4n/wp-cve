<?php
/**
 * Ensure that gw.freepay.dk is a trusted redirect host
 */
add_filter( 'allowed_redirect_hosts', static function ( array $hosts ): array {
	$hosts[] = 'gw.freepay.dk';

	return $hosts;
} );
