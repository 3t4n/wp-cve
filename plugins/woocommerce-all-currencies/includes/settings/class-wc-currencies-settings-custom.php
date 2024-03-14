<?php
/**
 * WooCommerce All Currencies - Custom Currencies Section Settings
 *
 * @version 2.4.2
 * @since   2.2.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_All_Currencies_Settings_Custom_Currencies' ) ) :

class Alg_WC_All_Currencies_Settings_Custom_Currencies extends Alg_WC_All_Currencies_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function __construct() {
		$this->id   = 'custom_currencies';
		$this->desc = __( 'Custom Currencies', 'woocommerce-all-currencies' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.4.0
	 * @since   2.2.0
	 * @todo    [now] fix symbol bug
	 * @todo    [now] maybe better solution?!?
	 */
	public static function get_settings() {
		$settings = array(
			array(
				'title'    => __( 'Custom Currencies', 'woocommerce-all-currencies' ),
				'type'     => 'title',
				'id'       => 'alg_wc_all_currencies_custom_currencies_options',
			),
			array(
				'title'    => __( 'Enable/Disable', 'woocommerce-all-currencies' ),
				'desc'     => '<strong>' . __( 'Enable section', 'woocommerce-all-currencies' ) . '</strong>',
				'id'       => 'alg_wc_all_currencies_custom_currencies_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'desc_tip' => apply_filters( 'alg_wc_all_currencies_filter',
					sprintf(
						__( 'You will need <a target="_blank" href="%s">All Currencies for WooCommerce Pro</a> plugin to enable this section.', 'woocommerce-all-currencies' ),
						'https://wpwham.com/products/all-currencies-for-woocommerce/?utm_source=settings_custom_currencies&utm_campaign=free&utm_medium=all_currencies'
					), 'settings'
				),
				'custom_attributes' => apply_filters( 'alg_wc_all_currencies_filter', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Total custom currencies', 'woocommerce-all-currencies' ),
				'desc'     => '<button name="save" class="button-primary woocommerce-save-button" type="submit" value="' . __( 'Save changes', 'woocommerce-all-currencies' ) . '">' .
					__( 'Save changes', 'woocommerce-all-currencies' ) . '</button>',
				'desc_tip' => __( 'Save settings to see new options fields.', 'woocommerce-all-currencies' ),
				'type'     => 'number',
				'id'       => 'alg_wc_all_currencies_custom_currencies_total',
				'default'  => 1,
				'custom_attributes' => array( 'min' => 0 ),
			),
		);
		for ( $i = 1; $i <= get_option( 'alg_wc_all_currencies_custom_currencies_total', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => sprintf( __( 'Custom currency #%d', 'woocommerce-all-currencies' ), $i ),
					'desc'     => __( 'Code', 'woocommerce-all-currencies' ) . ' (' . __( 'required', 'woocommerce-all-currencies' ) . ')',
					'desc_tip' => sprintf( __( 'E.g.: %s', 'woocommerce-all-currencies' ), 'BTC' ),
					'type'     => 'text',
					'id'       => "alg_wc_all_currencies_custom_currencies_codes[{$i}]",
					'default'  => '',
				),
				array(
					'desc'     => __( 'Name', 'woocommerce-all-currencies' ) . ' (' . __( 'optional', 'woocommerce-all-currencies' ) . ')',
					'desc_tip' => sprintf( __( 'E.g.: %s', 'woocommerce-all-currencies' ), __( 'Bitcoin', 'woocommerce-all-currencies' ) ),
					'type'     => 'text',
					'id'       => "alg_wc_all_currencies_custom_currencies_names[{$i}]",
					'default'  => '',
				),
				array(
					'desc'     => __( 'Symbol', 'woocommerce-all-currencies' ) . ' (' . __( 'optional', 'woocommerce-all-currencies' ) . ')',
					'desc_tip' => sprintf( __( 'E.g.: %s', 'woocommerce-all-currencies' ), '&#3647;' ),
					'type'     => 'text',
					'id'       => "alg_wc_all_currencies_custom_currencies_symbols[{$i}]",
					'default'  => '',
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_all_currencies_custom_currencies_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_All_Currencies_Settings_Custom_Currencies();
