<?php
namespace Enteraddons\Widgets\Testimonial_Grid;
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

class Testimonial_Grid_Template {

	use Traits\Templates_Components;
	use Traits\Template_1;
	use Traits\Template_2;

	private static $settingsData;

	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}

	private static function getDisplaySettings() {
		return self::$settingsData;
	}

	public static function renderTemplate() {
		$settings = self::getDisplaySettings();
		if( $settings['testimonial_temp_layout'] == '1' ) {
			self::markup_style_1();
		} else {
			self::markup_style_2();
		}
	}

}
