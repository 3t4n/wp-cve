<?php
namespace webaware\gf_dpspxpay;

if (!defined('ABSPATH')) {
	exit;
}

/**
* kick start the plugin
*/
add_action('plugins_loaded', function() {
	require GFDPSPXPAY_PLUGIN_ROOT . 'includes/functions.php';
	require GFDPSPXPAY_PLUGIN_ROOT . 'includes/class.GFDpsPxPayPlugin.php';
	Plugin::getInstance()->pluginStart();
}, 5);

/**
* autoload classes as/when needed
* @param string $class_name name of class to attempt to load
*/
spl_autoload_register(function($class_name) {
	static $classMap = [
		'GFDpsPxPayAPI'							=> 'includes/class.GFDpsPxPayAPI.php',
		'GFDpsPxPayCredentials'					=> 'includes/class.GFDpsPxPayCredentials.php',
		'GFDpsPxPayResponse'					=> 'includes/class.GFDpsPxPayResponse.php',
		'GFDpsPxPayResponseRequest'				=> 'includes/class.GFDpsPxPayResponseRequest.php',
		'GFDpsPxPayResponseResult'				=> 'includes/class.GFDpsPxPayResponseResult.php',
		'GFDpsPxPayUpdateV1'					=> 'includes/class.GFDpsPxPayUpdateV1.php',
	];

	if (strpos($class_name, __NAMESPACE__) === 0) {
		$class_name = substr($class_name, strlen(__NAMESPACE__) + 1);

		if (isset($classMap[$class_name])) {
			require GFDPSPXPAY_PLUGIN_ROOT . $classMap[$class_name];
		}
	}
});
