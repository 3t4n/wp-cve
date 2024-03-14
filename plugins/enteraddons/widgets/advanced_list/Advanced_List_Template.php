<?php
namespace Enteraddons\Widgets\Advanced_List;
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

class Advanced_List_Template {

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
		// Call markup style 1
		self::markup_style_1();		
	}

}