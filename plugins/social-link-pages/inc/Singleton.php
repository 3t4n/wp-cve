<?php

namespace SocialLinkPages;

abstract class Singleton {

	private static $_instances = [];

	private function __construct() {
	}

	final public static function instance() {
		$class = get_called_class();
		if ( ! isset( self::$_instances[ $class ] ) ) {
			self::$_instances[ $class ] = new $class();
			self::$_instances[ $class ]->setup();
		}

		return self::$_instances[ $class ];
	}

	private function __clone() {
	}

	abstract protected function setup();
}
