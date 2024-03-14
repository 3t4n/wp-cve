<?php
/**
 * Plugin Name: Simple Calendar - ACF field
 * Plugin URI:  https://wordpress.org/plugins/simple-calendar-acf/
 * Description: Use Simple Calendar with Advanced Custom Fields.
 *
 * Version:     1.0.2
 *
 * Author:      Simple Calendar
 * Author URI:  https://simplecalendar.io
 *
 * Text Domain: simple-calendar-acf
 * Domain Path: i18n/
 *
 * @copyright   2013-2023 Xtendify Technologies. All rights reserved.
 */

if (!defined("ABSPATH")) {
	exit();
} elseif (version_compare(PHP_VERSION, "5.3.0") !== -1) {
	if (!defined("SIMPLE_CALENDAR_ACF_MAIN_FILE")) {
		define("SIMPLE_CALENDAR_ACF_MAIN_FILE", __FILE__);
	}
	include_once "includes/add-on-acf.php";
}
