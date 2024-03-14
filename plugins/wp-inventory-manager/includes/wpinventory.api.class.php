<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPIMAPI {

	private static $instance;

	private static $config;

	const API_URL = 'https://www.wpinventory.com/license_api/';

	public function __construct() {
		self::$config = WPIMConfig::getInstance();
	}

	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
