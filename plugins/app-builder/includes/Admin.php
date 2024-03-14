<?php

/**
 * Admin
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 *
 */

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

class Admin {
	public function __construct() {
		new Admin\Menu();
	}
}
