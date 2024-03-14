<?php
/**
 * The settings page of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

/**
 * The settings page main class.
 */
class SP_WCS_Settings {
	/**
	 * Settings page main function.
	 *
	 * @param string $prefix metabox prefix.
	 * @return void
	 */
	public static function settings( $prefix ) {

		SP_WCS::createOptions(
			$prefix,
			array(
				'menu_title'      => __( 'Settings', 'woo-category-slider-grid' ),
				'menu_slug'       => 'wcsp_settings',
				'menu_parent'     => 'edit.php?post_type=sp_wcslider',
				'menu_type'       => 'submenu',
				'ajax_save'       => true,
				'save_defaults'   => true,
				'show_reset_all'  => false,
				'framework_title' => __( 'Settings', 'woo-category-slider-grid' ),
				'framework_class' => 'sp-wcsp-options',
				'theme'           => 'light',
				'show_bar_menu'   => false,
			)
		);
		SP_WCS_Advanced::section( $prefix );
		SP_WCS_Style::section( $prefix );
	}
}
