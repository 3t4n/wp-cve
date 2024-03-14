<?php

namespace GT3\PhotoVideoGallery;


class Single {
	private static $instance = null;

	private static $classes = array();

	public static function instance(){
		if(!self::$instance instanceof self) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct(){
	}

	public static function get($class) {
		return (key_exists($class, self::$classes))?self::$classes[$class]:false;
	}

	public static function set($obj) {
		$class = get_class($obj);

		if (false === self::get($class)) {
			self::$classes[$class] = $obj;
		}
	}
}
