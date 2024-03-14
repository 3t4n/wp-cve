<?php
namespace Enteraddons\Widgets\Advanced_Animation_Title;
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

class Advanced_Animation_Title_Template {

	use Traits\Templates_Components;
	use Traits\Template_1;

	private static $settingsData;
	private static $setDisplayID;

	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}
	
	private static function getDisplaySettings() {
		return self::$settingsData;

	}
	
	public static function setDisplayID( $id ) {
		self::$setDisplayID = $id;
	}

	public static function getDisplayID( ) {
		return self::$setDisplayID;
	}

	public static function renderTemplate() {
		self::markup_style_1();
	}
}