<?php
namespace Thim_EL_Kit;

trait SingletonTrait {
	protected static $instance = null;

	protected function __construct() {}

	final public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'thim-elementor-kit' ), '1.0' );
		die();
	}
}
