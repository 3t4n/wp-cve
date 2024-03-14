<?php

/*
	Plugin Name: Forex Live Prices
	Plugin URI: 
	Description: Best solution for websites where you need forex live currency rates widgets fast & free solution. Data provided by fxpricing.com and fcsapi.com, Terms and condition apply.
	Text Domain: fx-live-prices
	Version: 1.0
	Requires PHP: 7.0
	Requires at least: 4.9
	Author: FxPricing
	Author URI: https://fxpricing.com/
	License: GPLv3
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


if(!defined('WPINC')){
	die;
}


if(!defined('FXLIVE_PLUGIN_VERSION')){
	define('FXLIVE_PLUGIN_VERSION', '1.0.0');
}
if(!defined('FXLIVE_PLUGIN_DIR')){
	define('FXLIVE_PLUGIN_DIR', plugin_dir_url(__FILE__));
}


require_once(__DIR__.'/include/functions.php');
require_once(__DIR__.'/include/settings.php');
require_once(__DIR__.'/include/symbol_list.php');

