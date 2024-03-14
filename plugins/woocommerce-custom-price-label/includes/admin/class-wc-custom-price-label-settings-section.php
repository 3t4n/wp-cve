<?php
/**
 * Custom Price Labels for WooCommerce - Section Settings
 *
 * @version 2.4.1
 * @since   2.3.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Price_Labels_Settings_Section' ) ) :

class Alg_WC_Custom_Price_Labels_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_custom_price_label',                   array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_custom_price_label' . '_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_settings.
	 *
	 * @version 2.4.1
	 * @since   2.3.0
	 */
	function get_settings() {
		return array_merge( $this->get_section_settings(), array(
			array(
				'title'     => __( 'Reset Settings', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'id'        => 'custom_price_label' . '_' . $this->id . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'woocommerce-custom-price-label' ),
				'desc'      => '<strong>' . __( 'Reset', 'woocommerce-custom-price-label' ) . '</strong>',
				'id'        => 'custom_price_label' . '_' . $this->id . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'custom_price_label' . '_' . $this->id . '_reset_options',
			),
		) );
	}

}

endif;
