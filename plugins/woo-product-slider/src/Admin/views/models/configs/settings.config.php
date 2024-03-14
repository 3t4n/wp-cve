<?php
/**
 * Settings page configuration.
 *
 * @since      2.2.0
 * @package    Woo_Product_Slider
 * @subpackage Woo_Product_Slider/Admin/view
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_woo_product_slider_options';

//
// Create a settings page.
//
SPF_WPSP::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Settings', 'woo-product-slider' ),
		'menu_slug'        => 'wps_settings',
		'menu_parent'      => 'edit.php?post_type=sp_wps_shortcodes',
		'menu_type'        => 'submenu',
		'theme'            => 'light',
		'class'            => 'wps-settings-page',
		'show_search'      => false,
		'show_all_options' => false,
		'show_reset_all'   => false,
		'show_bar_menu'    => false,
		'framework_title'  => __( 'Settings', 'woo-product-slider' ),
	)
);

//
// Advanced Settings section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'     => 'advanced_settings',
		'title'  => __( 'Advanced', 'woo-product-slider' ),
		'icon'   => '<i class="spwps-tab-icon fa fa-wrench"></i>',
		'fields' => array(
			array(
				'id'         => 'wpsp_delete_all_data',
				'type'       => 'checkbox',
				'class'      => 'wpsp_delete_all_data',
				'title'      => __( 'Clean-up Data on Deletion', 'woo-product-slider' ),
				'title_help' => __( 'Check to remove plugin\'s data when plugin is uninstalled or deleted.', 'woo-product-slider' ),
				'default'    => false,
			),
			array(
				'id'         => 'enqueue_font_awesome',
				'type'       => 'switcher',
				'title'      => __( 'FontAwesome CSS', 'woo-product-slider' ),
				'text_on'    => __( 'Enqueued', 'woo-product-slider' ),
				'text_off'   => __( 'Dequeued', 'woo-product-slider' ),
				'text_width' => 110,
				'default'    => true,
			),
			array(
				'id'         => 'enqueue_swiper_css',
				'type'       => 'switcher',
				'title'      => __( 'Swiper CSS', 'woo-product-slider' ),
				'text_on'    => __( 'Enqueued', 'woo-product-slider' ),
				'text_off'   => __( 'Dequeued', 'woo-product-slider' ),
				'text_width' => 110,
				'default'    => true,
			),
			array(
				'id'         => 'enqueue_swiper_js',
				'type'       => 'switcher',
				'title'      => __( 'Swiper JS', 'woo-product-slider' ),
				'text_on'    => __( 'Enqueued', 'woo-product-slider' ),
				'text_off'   => __( 'Dequeued', 'woo-product-slider' ),
				'text_width' => 110,
				'default'    => true,
			),

		),
	)
);
//
// Upsells Products section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'    => 'upsells_products_section',
		'title' => __( 'Upsells Products', 'woo-product-slider' ),
		'icon'  => '<i class="spwps-tab-icon fa fa-rocket"></i>',
		'class' => 'wps-pro-section',
	)
);
//
// Related Products section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'    => 'related_products_section',
		'title' => __( 'Related Products', 'woo-product-slider' ),
		'icon'  => '<i class="spwps-tab-icon fa fa-share-alt"></i>',
		'class' => 'wps-pro-section',
	)
);


//
// Responsive Settings section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'    => 'responsive_settings_section',
		'title' => __( 'Responsive Breakpoints', 'woo-product-slider' ),
		'icon'  => '<i class="spwps-tab-icon fa fa-tablet"></i>',
		'class' => 'wps-pro-section',
	)
);

//
// Custom CSS section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'     => 'custom_css_section',
		'title'  => __( 'Additional CSS', 'woo-product-slider' ),
		'icon'   => '<i class="spwps-tab-icon fa fa-file-code-o"></i>',
		'fields' => array(
			array(
				'id'       => 'custom_css',
				'type'     => 'code_editor',
				'title'    => __( 'Custom CSS', 'woo-product-slider' ),
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'theme' => 'dracula',
					'mode'  => 'css',
				),
			),

		),
	)
);
//
// License key section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'     => 'license_key_section',
		'title'  => __( 'License Key', 'woo-product-slider' ),
		'icon'   => '<i class="spwps-tab-icon fa fa-key"></i>',
		'fields' => array(
			array(
				'id'   => 'license_key',
				'type' => 'license',
			),

		),
	)
);
