<?php
	/*
	Plugin Name: All in One Visual Composer Addons
	Description: Contains all addons for WPBakery Page Builder plugin that are required to build an interactive pages for a website.
	Plugin URI: http://webdevocean.com
	Author: Labib Ahmed
	Author URI: http://webdevocean.com/about
	Version: 1.2
	License: GPL2
	Text Domain: wdo-ultimate-addons
	*/


include 'plugin.class.php';
if (class_exists('WDO_Addons_VC')) {
    $obj_init = new WDO_Addons_VC;
}
?>