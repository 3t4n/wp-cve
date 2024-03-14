<?php
/**
 * Required simple plugin functions
 */

function twl_pip_activate() {
	// set default settings
	$settings = array(
		'alert_sdk_errors' => 1,
	);

	TWL_PIP_Config::add('cache_feeds', 30, true);
	TWL_PIP_Config::add('facebook-settings', $settings, true);
	TWL_PIP_Config::add('twitter-settings', $settings, true);
}

// check if all necessary functions and classes are present
// @todo list other functions and classes to check
function twl_pip_is_runnable() {
	$functions = array('ob_start', 'ob_get_contents');
	$classes = array('TWL_Page_IN_Page_Page', 'TWL_Page_IN_Page_Widget', 'TWL_Page_In_Page_Vars', 'TWL_PIP_Config');

	foreach ($functions as $function) {
		if (!function_exists($function)) {
			throw new Exception("Page-In-Page plugin: Required function '{$function}' not found.");
		}
	}

	foreach ($classes as $class) {
		if (!class_exists($class)) {
			throw new Exception("Page-In-Page plugin: Required class '{$class}' not found.");
		}
	}
	return true;
}
add_action('init', 'twl_pip_is_runnable');

// enqueue scripts
function twl_pip_enqueue_scripts() {
	$scripts = TWL_PIP_Config::get('plugin_js');
	if ($scripts) {
		foreach ($scripts as $index => $script) {
			wp_enqueue_script('page-in-page-js-' . $index, $script, array('jquery'));
		}
	}

	$styles = TWL_PIP_Config::get('plugin_css');
	if ($styles) {
		foreach ($styles as $index => $style) {
			wp_enqueue_style('page-in-page-css-' . $index, $style);
		}
	}
}
add_action('wp_enqueue_scripts', 'twl_pip_enqueue_scripts');
add_action('admin_init', 'twl_pip_enqueue_scripts');

// log a string or exception. Gives uniformity to plugin's error logs
function twl_pip_log($e, $type = '') {
	if ($e instanceof Exception) {
		error_log('TWL_PIP: '.$type . $e->getMessage());
		error_log('TWL_PIP: ' . $e->getTraceAsString());
		return;
	}

	error_log('TWL_PIP: ' . print_r($e, true));
}

// shortcut to extract facebook/twitter vars from REST API
function twl_pip_get_vars($feed, $page_vars, $media) {
	switch ($media) {
		case 'facebook':
			return TWL_Page_In_Page_Vars::facebookFeedItem($feed, $page_vars);
		case 'twitter':
			return TWL_Page_In_Page_Vars::twitterFeedItem($feed, $page_vars);
		default: 
			return;
	}	
}

// set a variable if key exists as an index of array
function twl_pip_set($key, $array, $type = false, $type_value = false) {
	if (empty($key) || empty($array)) return;
	if (!isset($array[$key])) return;

	$value = $array[$key];
	if (in_array($type, array('radio', 'checkbox')) && $type_value == $value) {
		$value = 'checked="checked"';
	}
	if ($type == 'option' && $type_value == $value) {
		$value = 'selected="selected"';
	}
	echo $value;
}