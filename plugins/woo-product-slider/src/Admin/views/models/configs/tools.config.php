<?php
/**
 * Tools page.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 * @package Woo_Product_Slider.
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_woo_product_slider_tools';

//
// Create options.
//
SPF_WPSP::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Tools', 'woo-product-slider' ),
		'menu_slug'        => 'wps_tools',
		'menu_parent'      => 'edit.php?post_type=sp_wps_shortcodes',
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
		'framework_title'  => __( 'Tools', 'woo-product-slider' ),
		'framework_class'  => 'wps-settings-page spwps_tools',
	)
);
SPF_WPSP::createSection(
	$prefix,
	array(
		'title'  => __( 'Export', 'woo-product-slider' ),
		'fields' => array(
			array(
				'id'       => 'wpsp_what_export',
				'type'     => 'radio',
				'class'    => 'wpsp_what_export',
				'title'    => __( 'Choose What To Export', 'woo-product-slider' ),
				'multiple' => false,
				'options'  => array(
					'all_shortcodes'      => __( 'All Sliders (Shortcodes)', 'woo-product-slider' ),
					'selected_shortcodes' => __( 'Selected Slider (Shortcode)', 'woo-product-slider' ),
				),
				'default'  => 'all_shortcodes',
			),
			array(
				'id'          => 'wpsp_post',
				'class'       => 'wpsp_post_ids',
				'type'        => 'select',
				'title'       => ' ',
				'options'     => 'post',
				'chosen'      => true,
				'sortable'    => false,
				'multiple'    => true,
				'query_args'  => array(
					'post_type'      => 'sp_wps_shortcodes',
					'posts_per_page' => -1,
				),
				'placeholder' => __( 'Choose slider(s)', 'woo-product-slider' ),
				'dependency'  => array( 'wpsp_what_export', '==', 'selected_shortcodes', true ),
			),
			array(
				'id'      => 'export',
				'class'   => 'wpsp_export',
				'type'    => 'button_set',
				'title'   => ' ',
				'options' => array(
					'' => array(
						'name' => __( 'Export', 'woo-product-slider' ),
					),
				),
			),
		),
	)
);
SPF_WPSP::createSection(
	$prefix,
	array(
		'title'  => __( 'Import', 'woo-product-slider' ),
		'fields' => array(
			array(
				'class' => 'wpsp_import',
				'type'  => 'custom_import',
				'title' => __( 'Import JSON File To Upload', 'woo-product-slider' ),
			),
		),
	)
);
