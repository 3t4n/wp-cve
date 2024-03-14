<?php
/**
 * WooCommerce All Currencies - Settings Section
 *
 * @version 2.2.0
 * @since   2.1.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_All_Currencies_Settings_Section' ) ) :

class Alg_WC_All_Currencies_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.2.0
	 * @since   2.1.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_alg_wc_all_currencies',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_wc_all_currencies_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

}

endif;
