<?php
namespace Enteraddons\Widgets\Nav_Menu_Offcanvas;
/**
 * Enteraddons widget template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Nav_Menu_Offcanvas_Template {

	use Traits\Templates_Components;
	use Traits\Template_1;

	private static $settingsData;
	private static $widgetObj;
	
	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}
	public static function setWidgetObject( $obj ) {
		self::$widgetObj = $obj;
	}
	private static function getDisplaySettings() {
		return self::$settingsData;
	}
	private static function getWidgetObject() {
		return self::$widgetObj;
	}
	public static function renderTemplate() {
		self::markup_style_1();
	}
}