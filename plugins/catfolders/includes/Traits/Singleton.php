<?php

namespace CatFolders\Traits;

trait Singleton {
	protected static $instance = null;
	protected function __construct() {}
	final public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
			static::$instance->doHooks();
		}
		return static::$instance;
	}

	public function doHooks() {
	}
}
