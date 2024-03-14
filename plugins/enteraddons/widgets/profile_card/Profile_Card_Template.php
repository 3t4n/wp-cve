<?php
namespace Enteraddons\Widgets\Profile_Card;
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

class Profile_Card_Template {

	use Traits\Templates_Components;
	use Traits\Template_1;
	use Traits\Template_2;

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

		$settings = self::getDisplaySettings();
		
		if( $settings['card_style'] == '1' ) {
			self::markup_style_1();
		} else {
			self::markup_style_2();
		}

	}

}
