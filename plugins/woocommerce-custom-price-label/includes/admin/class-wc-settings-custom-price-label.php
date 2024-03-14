<?php
/**
 * WooCommerce Custom Price Label - Settings
 *
 * @version 2.3.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Settings_Custom_Price_Label' ) ) :

class WC_Settings_Custom_Price_Label extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.2.0
	 */
	function __construct() {
		$this->id    = 'custom_price_label';
		$this->label = __( 'Custom Price Labels', 'woocommerce-custom-price-label' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.2.0
	 */
	function get_settings() {
		global $current_section;
		return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					delete_option( $value['id'] );
					$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}
	}

	/**
	 * Save settings.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}
}

endif;

return new WC_Settings_Custom_Price_Label();
