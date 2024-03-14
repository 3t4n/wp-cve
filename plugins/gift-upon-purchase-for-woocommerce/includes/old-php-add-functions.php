<?php if (!defined('ABSPATH')) {exit;}
/*
Version: 1.0.2
Date: 05-01-2022
Author: Maxim Glazunov
Author URI: https://icopydoc.ru 
License: GPLv2
Description: This code adds several features to older PHP versions.
*/

/*
* @since 1.0.0
* @source https://www.php.net/manual/ru/function.array-key-first.php
*
* @param array $arr (require)
*
* @return string/null
*
*/ 
if (version_compare(PHP_VERSION, '7.3.0') <= 0) {
	if (!function_exists('array_key_first')) {
		function array_key_first(array $arr) {
			foreach($arr as $key => $unused) {
				return $key;
			}
			return null;
		}
	}
}
/*
* @since 1.0.1
*
* @return last key of array or null
*/
if (version_compare(PHP_VERSION, '7.3.0') <= 0) {
	if (!function_exists("array_key_last")) {
		function array_key_last($array) {
			if (!is_array($array) || empty($array)) {
				return null;
			}
			return array_keys($array)[count($array)-1];
		}
	}
}
?>