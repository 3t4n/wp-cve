<?php

// prevent direct access
if ( !defined( 'ABSPATH' ) ) {

	header( 'HTTP/1.0 404 Not Found', true, 404 );

	exit;
}

// register action hooks
add_action( 'woocommerce_settings_start', 'tx_woocompare_register_settings' );
add_action( 'woocommerce_settings_tx_woocompare_list', 'tx_woocompare_render_settings_page' );
add_action( 'woocommerce_update_options_tx_woocompare_list', 'tx_woocompare_update_options' );

// register filter hooks
add_filter( 'woocommerce_settings_tabs_array', 'tx_woocompare_register_settings_tab', PHP_INT_MAX );

/**
 * Returns array of the plugin settings, which will be rendered in the
 * WooCommerce settings tab.
 *
 * @since 1.0.0
 *
 * @return array The array of the plugin settings.
 */
function tx_woocompare_get_settings() {

	return array(
		array(
			'id'    => 'general-options',
			'type'  => 'title',
			'title' => __( 'General Options', 'tx' ),
		),
		array(
			'type'    => 'checkbox',
			'id'      => 'tx_woocompare_enable',
			'title'   => __( 'Enable compare', 'tx' ),
			'desc'    => __( 'Enable compare functionality.', 'tx' ),
			'default' => 'no',
		),
		array(
			'type'  => 'single_select_page',
			'id'    => 'tx_woocompare_page',
			'class' => 'chosen_select_nostd',
			'title' => __( 'Select compare page', 'tx' ),
			'desc'  => '<br>' . __( 'Select a page which will display compare list.', 'tx' ),
		),
		array(
			'type'    => 'checkbox',
			'id'      => 'tx_woocompare_show_in_catalog',
			'title'   => __( 'Show in catalog', 'tx' ),
			'desc'    => __( 'Enable compare functionality for catalog list.', 'tx' ),
			'default' => 'yes',
		),
		array(
			'type'    => 'checkbox',
			'id'      => 'tx_woocompare_show_in_single',
			'title'   => __( 'Show in products page', 'tx' ),
			'desc'    => __( 'Enable compare functionality for single product page.', 'tx' ),
			'default' => 'yes',
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_compare_text',
			'title'   => __( 'Compare button text', 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the add to compare button.', 'tx' ),
			'default' => __( 'Add to Compare', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_remove_text',
			'title'   => __( 'Remove button text', 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the remove from compare button.', 'tx' ),
			'default' => __( 'Remove from Compare', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_page_btn_text',
			'title'   => __( 'Page button text' , 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the compare page button.', 'tx' ),
			'default' => __( 'Compare products' , 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_empty_btn_text',
			'title'   => __( 'Empty button text' , 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the empty compare button.', 'tx' ),
			'default' => __( 'Empty compare' , 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_empty_text',
			'title'   => __( 'Empty compare list text', 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the compare page when is nothing to compare.', 'tx' ),
			'default' => __( 'No products found to compare.', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_page_template',
			'title'   => __( 'Page template', 'tx' ),
			'default' => __( 'page.tmpl', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woocompare_widget_template',
			'title'   => __( 'Widget template', 'tx' ),
			'default' => __( 'widget.tmpl', 'tx' ),
		),
		array( 'type' => 'sectionend', 'id' => 'general-options' ),
	);
}

/**
 * Registers plugin settings in the WooCommerce settings array.
 *
 * @since 1.0.0
 * @action woocommerce_settings_start
 *
 * @global array $woocommerce_settings WooCommerce settings array.
 */
function tx_woocompare_register_settings() {

	global $woocommerce_settings;

	$woocommerce_settings['tx_woocompare_list'] = tx_woocompare_get_settings();
}

/**
 * Registers WooCommerce settings tab which will display the plugin settings.
 *
 * @since 1.0.0
 * @filter woocommerce_settings_tabs_array PHP_INT_MAX
 *
 * @param array $tabs The array of already registered tabs.
 * @return array The extended array with the plugin tab.
 */
function tx_woocompare_register_settings_tab( $tabs ) {

	$tabs['tx_woocompare_list'] = esc_html__( 'TX Compare List', 'tx' );

	return $tabs;
}

/**
 * Renders plugin settings tab.
 *
 * @since 1.0.0
 * @action woocommerce_settings_tx_woocompare_list
 *
 * @global array $woocommerce_settings The aggregate array of WooCommerce settings.
 * @global string $current_tab The current WooCommerce settings tab.
 */
function tx_woocompare_render_settings_page() {

	global $woocommerce_settings, $current_tab;

	if ( function_exists( 'woocommerce_admin_fields' ) ) {

		woocommerce_admin_fields( $woocommerce_settings[$current_tab] );
	}
}

/**
 * Updates plugin settings after submission.
 *
 * @since 1.0.0
 * @action woocommerce_update_options_tx_woocompare_list
 */
function tx_woocompare_update_options() {

	if ( function_exists( 'woocommerce_update_options' ) ) {

		woocommerce_update_options( tx_woocompare_get_settings() );
	}
}