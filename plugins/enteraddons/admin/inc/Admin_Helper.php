<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons Admin Helper
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Admin_Helper {

/**
 *
 * Set enteraddons  statistic
 *
 * @return array
 *
 */
public static function enteraddons_statistic() {

	return array(
		array(
			'title'		 => 'Free Advance Widgets',
			'number' 	 => '60+',
			'color_code' => '#56a15a'
		),
		array(
			'title'		 => 'Free Ready Blocks',
			'number' 	 => '100+',
			'color_code' => '#9333ff'
		),
		array(
			'title'		 => 'Free Pages',
			'number' 	 => '20+',
			'color_code' => '#56a15a'
		),
	);

}
public static function enteraddons_pro_features_statistic() {

	$txt = esc_html__( 'View All', 'enteraddons' );

	return array(
		array(
			'title'		 => 'Advance Widgets',
			'number' 	 => '100+',
			'color_code' => '#56a15a',
			'link'		 => 'https://enteraddons.com/widgets/',
			'text'		 => esc_html( $txt )
		),
		array(
			'title'		 => 'Modules',
			'number' 	 => '8+',
			'color_code' => '#9333ff',
			'link'		 => 'https://enteraddons.com/extensions/',
			'text'		 => esc_html( $txt )
		),
		array(
			'title'		 => 'Ready Templates',
			'number' 	 => '120+',
			'color_code' => '#e82a5c',
			'link'		 => 'https://enteraddons.com/templates/',
			'text'		 => esc_html( $txt )
		),
		array(
			'title'		 => 'Section Blocks',
			'number' 	 => '500+',
			'color_code' => '#913660',
			'link'		 => 'https://enteraddons.com/blocks/',
			'text'		 => esc_html( $txt )
		),
		// array(
		// 	'title'		 => '',
		// 	'number' 	 => 'Section Nesting',
		// 	'color_code' => '#9333ff'
		// ),
		// array(
		// 	'title'		 => '',
		// 	'number' 	 => 'Cross Domain Copy Paste and more',
		// 	'color_code' => '#56a15a'
		// ),
	);

}


}