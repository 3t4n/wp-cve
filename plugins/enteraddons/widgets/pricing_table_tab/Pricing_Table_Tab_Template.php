<?php
namespace Enteraddons\Widgets\Pricing_Table_Tab;
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

class Pricing_Table_Tab_Template {

	use \Enteraddons\Widgets\Pricing_Table_Tab\Traits\Templates_Components;
	use \Enteraddons\Widgets\Pricing_Table_Tab\Traits\Template_1;

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