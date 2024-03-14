<?php

/**
 * Main export
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 */

namespace AppBuilder\Lms\MasterStudy;

defined( 'ABSPATH' ) || exit;

class Main {
	public function __construct() {
		$api = new Api();
		$api->register_routes();
	}
}
