<?php

/*
 * Read config
 */

class TWL_PIP_Config {

	private static $config = null;
	
	private static $prefix = '_twl_pip_';

	public static function init() {
		if (self::$config === null) {
			self::$config = twl_pip_config();
		}
	}

	public static function get($key, $subkey = false) {
		if (isset(self::$config[$key])) {
			if ($subkey) {
				return isset(self::$config[$key][$subkey]) ? self::$config[$key][$subkey] : null;
			}
			return self::$config[$key];
		}
		return null;
	}

	public static function option($key, $default = false) {
		$key = self::$prefix . $key;
		return get_option($key, $default);
	}

	public static function add($key, $value = null, $init_setting = false) {
		$key = self::$prefix . $key;
		if ($init_setting && get_option($key) == false) {
			add_option($key, $value);
			return $value;
		}
		add_option($key, $value) or update_option($key, $value);
		return $value;
	}

	public static function getConfig() {
		return self::$config;
	}
}
