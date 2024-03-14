<?php
/**
 * The Tools page of the plugin.
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
 * The tools page main class.
 */
class SP_WCS_Tools {
	/**
	 * Tools page main function.
	 *
	 * @param string $prefix metabox prefix.
	 * @return void
	 */
	public static function tools( $prefix ) {

		SP_WCS::createOptions(
			$prefix,
			array(
				'menu_title'       => __( 'Tools', 'woo-category-slider-grid' ),
				'menu_slug'        => 'wcsp_tools',
				'menu_parent'      => 'edit.php?post_type=sp_wcslider',
				'menu_type'        => 'submenu',
				'ajax_save'        => false,
				'show_bar_menu'    => false,
				'save_defaults'    => false,
				'show_reset_all'   => false,
				'show_all_options' => false,
				'show_search'      => false,
				'show_footer'      => false,
				'show_buttons'     => false, // Custom show button option added for hide save button in tools page.
				'theme'            => 'light',
				'framework_title'  => __( 'Tools', 'woo-category-slider-grid' ),
				'framework_class'  => 'sp-wcsp-options wcsp_tools',
			)
		);

		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Export', 'woo-category-slider-grid' ),
				'fields' => array(
					array(
						'id'       => 'wcsp_what_export',
						'type'     => 'radio',
						'class'    => 'wcsp_what_export',
						'title'    => __( 'Choose What To Export', 'woo-category-slider-grid' ),
						'multiple' => false,
						'options'  => array(
							'all_shortcodes'      => __( 'All Sliders (Shortcodes)', 'woo-category-slider-grid' ),
							'selected_shortcodes' => __( 'Selected Slider (Shortcode)', 'woo-category-slider-grid' ),
						),
						'default'  => 'all_shortcodes',
					),
					array(
						'id'          => 'wcsp_post',
						'class'       => 'wcsp_post_ids',
						'type'        => 'select',
						'title'       => ' ',
						'options'     => 'sp_wcslider',
						'chosen'      => true,
						'sortable'    => false,
						'multiple'    => true,
						'placeholder' => __( 'Choose slider(s)', 'woo-category-slider-grid' ),
						'query_args'  => array(
							'posts_per_page' => -1,
						),
						'dependency'  => array( 'wcsp_what_export', '==', 'selected_shortcodes', true ),

					),
					array(
						'id'      => 'export',
						'class'   => 'wcsp_export',
						'type'    => 'button_set',
						'title'   => ' ',
						'options' => array(
							'' => __( 'Export', 'woo-category-slider-grid' ),
						),
					),
				),
			)
		);
		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Import', 'woo-category-slider-grid' ),
				'fields' => array(
					array(
						'class' => 'wcsp_import',
						'type'  => 'custom_import',
						'title' => __( 'Import JSON File To Upload', 'woo-category-slider-grid' ),
					),
				),
			)
		);
	}
}
