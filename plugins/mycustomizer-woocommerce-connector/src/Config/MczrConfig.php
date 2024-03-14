<?php

namespace MyCustomizer\WooCommerce\Connector\Config;

use Symfony\Component\Yaml\Yaml;

final class MczrConfig {

	protected static $instance = null;

	protected function __construct() {
		// Singleton nothing available here
	}

	protected function __clone() {
		// Singleton nothing available here
	}

	public static function getInstance() {
		if ( ! isset( static::$instance ) ) {
			$path = \realpath( ( __DIR__ . '/../../config/config.yml' ) );
			if ( ! \is_file( $path ) ) {
				throw new \Exception( 'No config file found' );
			}
			$parse            = \Symfony\Component\Yaml\Yaml::parse( file_get_contents( $path ) );
			static::$instance = $parse;
		}
		return static::$instance;
	}
}
