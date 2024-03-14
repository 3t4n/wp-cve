<?php

namespace QuadLayers\QLWAPP\Controllers;

class Display_Services {

	protected static $instance;

	public function __construct() {
	}

	public function is_show_view() {
		return false;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
