<?php
use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_wps_replace_layout';

//
// Create options.
//
SPF_WPSP::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Replace Layout', 'woo-product-slider' ),
		'menu_slug'        => 'wps_replace_layout',
		'menu_parent'      => 'edit.php?post_type=sp_wps_shortcodes',
		'menu_type'        => 'submenu',
		'show_bar_menu'    => false,
		'save_defaults'    => false,
		'show_reset_all'   => false,
		'show_all_options' => false,
		'show_search'      => false,
		'show_footer'      => false,
		'ajax_save'        => true,
		'show_buttons'     => true,
		'theme'            => 'light',
		'framework_title'  => __( 'Replace Layout', 'woo-product-slider' ),
		'framework_class'  => 'wps-settings-page wpsp_replace-layout',
	)
);

//
// Replace Layout Section.
//
SPF_WPSP::createSection(
	$prefix,
	array(
		'id'     => 'replace_layout_section',
		'title'  => __( 'Replace Layout', 'woo-product-slider' ),
		'icon'   => '<span class="spwps-tab-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" xmlns:v="https://vecta.io/nano"><path d="M0 69.3V0h69.4v18.6H18.6v50.7H0h0zM100 100H30.6V30.7H100V100z" fill="#444"/></svg></span>',
		'fields' => array(
			array(
				'type'    => 'notice',
				'class'   => 'replace-layout-pro-notice',
				'content' => __( 'Want to Redesign or Replace the existing WooCommerce Shop/Archive, Category, Tag, and Search Pages layout with an attractive Woo Product Slider Layout? <a  href="https://wooproductslider.io/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-product-slider' ),
			),
			array(
				'type'           => 'subheading',
				'class'          => 'replace-layout-for-pro',
				'content'        => __( 'Quick View Button', 'woo-product-slider' ),
				'replace_layout' => true,
			),
		),
	)
);
