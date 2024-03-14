<?php
namespace Enteraddons\Widgets\Image_Zoom_Magnifier;
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

class Image_Zoom_Magnifier_Template {

	use Traits\Templates_Components;
	use Traits\Template_1;

	private static $settingsData;

	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}
	
	private static function getDisplaySettings() {
		return self::$settingsData;
	}

	public static function renderTemplate() {
		self::markup_style_1();
	}
}