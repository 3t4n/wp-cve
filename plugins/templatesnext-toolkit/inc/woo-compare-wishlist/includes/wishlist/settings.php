<?php

// prevent direct access
if ( !defined( 'ABSPATH' ) ) {

	header( 'HTTP/1.0 404 Not Found', true, 404 );

	exit;
}

// register action hooks
add_action( 'woocommerce_settings_start', 'tx_woowishlist_register_settings' );
add_action( 'woocommerce_settings_tx_woowishlist', 'tx_woowishlist_render_settings_page' );
add_action( 'woocommerce_update_options_tx_woowishlist', 'tx_woowishlist_update_options' );

// register filter hooks
add_filter( 'woocommerce_settings_tabs_array', 'tx_woowishlist_register_settings_tab', PHP_INT_MAX );

/**
 * Returns array of the plugin settings, which will be rendered in the
 * WooCommerce settings tab.
 *
 * @since 1.0.0
 *
 * @return array The array of the plugin settings.
 */
function tx_woowishlist_get_settings() {

	return array(
		array(
			'id'      => 'general-options',
			'type'    => 'title',
			'title'   => __( 'General Options', 'tx' ),
		),
		array(
			'type'    => 'checkbox',
			'id'      => 'tx_woowishlist_enable',
			'title'   => __( 'Enable wishlist', 'tx' ),
			'desc'    => __( 'Enable wishlist functionality.', 'tx' ),
			'default' => 'yes',
		),
		array(
			'type'    => 'single_select_page',
			'id'      => 'tx_woowishlist_page',
			'class'   => 'chosen_select_nostd',
			'title'   => __( 'Select wishlist page', 'tx' ),
			'desc'    => '<br>' . __( 'Select a page which will display wishlist.', 'tx' ),
		),
		array(
			'type'    => 'checkbox',
			'id'      => 'tx_woowishlist_show_in_catalog',
			'title'   => __( 'Show in catalog', 'tx' ),
			'desc'    => __( 'Enable wishlist button for catalog list.', 'tx' ),
			'default' => 'yes',
		),
		array(
			'type'    => 'checkbox',
			'id'      => 'tx_woowishlist_show_in_single',
			'title'   => __( 'Show in products page', 'tx' ),
			'desc'    => __( 'Enable wishlist button for single product page.', 'tx' ),
			'default' => 'yes',
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woowishlist_add_text',
			'title'   => __( 'Add to wishlist button text', 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the add to wishlist button.', 'tx' ),
			'default' => __( 'Add to Wishlist', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woowishlist_added_text',
			'title'   => __( 'Added to wishlist button text', 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the add to wishlist button, when product is added.', 'tx' ),
			'default' => __( 'Added to Wishlist', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woowishlist_page_btn_text',
			'title'   => __( 'Wishlist page button text' , 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the wishlist page button.', 'tx' ),
			'default' => __( 'Go to my wishlist', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woowishlist_empty_text',
			'title'   => __( 'Empty wishlist text', 'tx' ),
			'desc'    => '<br>' . __( 'Enter text which will be displayed on the wishlist page when is no products.', 'tx' ),
			'default' => __( 'No products added to wishlist.', 'tx' ),
		),
		array(
			'type'    => 'select',
			'id'      => 'tx_woowishlist_cols',
			'title'   => __( 'Wishlist columns', 'tx' ),
			'desc'    => '<br>' . __( 'Choose a number of columns.', 'tx' ),
			'default' => '1',
			'options' => array(
				'1'   => '1',
				'2'   => '2',
				'3'   => '3',
				'4'   => '4',
			)
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woowishlist_page_template',
			'title'   => __( 'Page template', 'tx' ),
			'default' => __( 'page.tmpl', 'tx' ),
		),
		array(
			'type'    => 'text',
			'id'      => 'tx_woowishlist_widget_template',
			'title'   => __( 'Widget template', 'tx' ),
			'default' => __( 'widget.tmpl', 'tx' ),
		),
		array(
			'type'    => 'sectionend',
			'id'      => 'general-options'
		),
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
function tx_woowishlist_register_settings() {

	global $woocommerce_settings;

	$woocommerce_settings['tx_woowishlist'] = tx_woowishlist_get_settings();
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
function tx_woowishlist_register_settings_tab( $tabs ) {

	$tabs['tx_woowishlist'] = esc_html__( 'TX Wishlist', 'tx' );

	return $tabs;
}

/**
 * Renders plugin settings tab.
 *
 * @since 1.0.0
 * @action woocommerce_settings_tx_woowishlist
 *
 * @global array $woocommerce_settings The aggregate array of WooCommerce settings.
 * @global string $current_tab The current WooCommerce settings tab.
 */
function tx_woowishlist_render_settings_page() {

	global $woocommerce_settings, $current_tab;

	if ( function_exists( 'woocommerce_admin_fields' ) ) {

		woocommerce_admin_fields( $woocommerce_settings[$current_tab] );
	}
}

/**
 * Updates plugin settings after submission.
 *
 * @since 1.0.0
 * @action woocommerce_update_options_tx_woowishlist
 */
function tx_woowishlist_update_options() {

	if ( function_exists( 'woocommerce_update_options' ) ) {

		woocommerce_update_options( tx_woowishlist_get_settings() );
	}
}