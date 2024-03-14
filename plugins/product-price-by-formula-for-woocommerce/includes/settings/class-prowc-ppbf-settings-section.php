<?php
/**
 * Product Price by Formula for WooCommerce - Section Settings
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_PPBF_Settings_Section' ) ) :

class ProWC_PPBF_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	public $id;
	public function __construct() {
		add_filter( 'woocommerce_get_sections_prowc_ppbf',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_prowc_ppbf_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public $desc;
	public function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

}

endif;
