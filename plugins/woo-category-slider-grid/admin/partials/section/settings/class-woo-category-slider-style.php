<?php
/**
 * Style settings section in settings page.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Style Settings in Settings page.
 *
 * @since 1.1.0
 */
class SP_WCS_Style {
	/**
	 * Settings section.
	 *
	 * @param string $prefix Settings section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'id'     => 'custom_css_section',
				'title'  => __( 'Custom CSS', 'woo-category-slider-grid' ),
				'icon'   => 'fa fa-css3',
				'fields' => array(
					array(
						'id'       => 'wcsp_custom_css',
						'type'     => 'code_editor',
						'title'    => __( 'Custom CSS', 'woo-category-slider-grid' ),
						'settings' => array(
							'icon'  => 'fa fa-sliders',
							'theme' => 'mbo',
							'mode'  => 'css',
						),
					),
				),
			)
		);

	}
}
