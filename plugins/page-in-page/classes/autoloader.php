<?php

class TWL_Page_In_Page_Autoloader {

	private static function classMap() {

		$map = array(
			'TWL_PIP_Config' => TWL_PIP_CLASSPATH . '/config.php',
			'TWL_Page_IN_Page_Page' => TWL_PIP_CLASSPATH . '/page.php',
			'TWL_Page_IN_Page_Widget' => TWL_PIP_CLASSPATH . '/widgets.php',
			'TWL_Page_In_Page_Vars' => TWL_PIP_CLASSPATH . '/vars.php',
		);

		return $map;
	}

	public static function loadClass($class) {
		if (class_exists($class)) {
			return true;
		}

		$map = self::classMap();
		if (!isset($map[$class])) {
			return false;
		}

		if (!file_exists($map[$class]) || !is_readable($map[$class])) {
			throw new RuntimeException("Class {$class} could not be loaded. {$map[$class]} not found");
		}

		include $map[$class];

		if (!class_exists($class)) {
			return false;
		}
	}

	public static function register() {
		if (!spl_autoload_register(array('TWL_Page_In_Page_Autoloader', 'loadClass'))) {
			throw new RuntimeException("Unable to register class autoloader in " . __CLASS__);
		}
		return true;
	}
}

return TWL_Page_In_Page_Autoloader::register();