<?php
namespace CatFolder_Document_Gallery\Utils;

trait SingletonTrait {
	private static $instance;

	protected function __construct() { }

	public static function get_instance( ...$args ) {
		$class = get_called_class();
		if ( ! $class::$instance ) {
			$class::$instance = new $class( ...$args );
		}

		return $class::$instance;
	}

	/** Singletons should not be cloneable. */
	protected function __clone() { }

	/** Singletons should not be restorable from strings. */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}
}
