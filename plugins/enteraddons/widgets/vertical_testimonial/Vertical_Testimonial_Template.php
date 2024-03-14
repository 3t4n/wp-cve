<?php
namespace Enteraddons\Widgets\Vertical_Testimonial;
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

class Vertical_Testimonial_Template {

	use \Enteraddons\Widgets\Vertical_Testimonial\Traits\Templates_Components;
	use \Enteraddons\Widgets\Vertical_Testimonial\Traits\Template_1;

	private static $settingsData;
	private static $displayID;
	
	
	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}
	public static function setDisplayID( $id ) {
		self::$displayID = $id;
	}

	private static function getDisplaySettings() {
		return self::$settingsData;
	}

	private static function getDisplayID() {
		return self::$displayID;
	}

	public static function renderTemplate() {
		self::markup_style_1();
		
	}

}

