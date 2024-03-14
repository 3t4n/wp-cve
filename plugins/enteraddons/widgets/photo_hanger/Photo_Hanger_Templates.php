<?php
namespace Enteraddons\Widgets\Photo_Hanger;
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

class Photo_Hanger_Templates {

	use Traits\Templates_Components;
	use Traits\Template_1;

	private static $settingsData;
	private static $attributes;

	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}
	public static function setAttributeString( $attributes ) {
		self::$attributes = $attributes;
	}

	private static function getDisplaySettings() {
		return self::$settingsData;
	}

	private static function getAttributeString() {
		return self::$attributes;
	}
	
	public static function renderTemplate() {
		self::markup_style_1();
	}

}
