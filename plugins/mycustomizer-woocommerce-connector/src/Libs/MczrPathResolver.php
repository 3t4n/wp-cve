<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

use Symfony\Component\HttpFoundation\Session\Session;

class MczrPathResolver {

	public function __construct() {
		$this->session = new Session();
	}

	public function plugin() {
		$plugin = realpath( \plugin_dir_path( __FILE__ ) . '../../' );
		return $plugin;
	}

	public function theme() {
		return realpath( \get_template_directory() );
	}
}
