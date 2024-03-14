<?php
/**
 * WooCommerce All Currencies - Settings
 *
 * @version 2.2.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_All_Currencies' ) ) :

class Alg_WC_Settings_All_Currencies extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.2.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_all_currencies';
		$this->label = __( 'Currencies', 'woocommerce-all-currencies' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.2.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'    => __( 'Reset Settings', 'woocommerce-all-currencies' ),
				'type'     => 'title',
				'id'       => 'alg_wc_all_currencies' . '_' . $current_section . '_reset_options',
			),
			array(
				'title'    => __( 'Reset section settings', 'woocommerce-all-currencies' ),
				'desc'     => '<strong>' . __( 'Reset', 'woocommerce-all-currencies' ) . '</strong>',
				'id'       => 'alg_wc_all_currencies' . '_' . $current_section . '_reset',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_all_currencies' . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 2.2.0
	 * @since   2.1.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( 'alg_wc_all_currencies' . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					$id = $id[0];
					delete_option( $id );
				}
			}
		}
	}

	/**
	 * Save settings.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}
}

endif;

return new Alg_WC_Settings_All_Currencies();
