<?php
/**
 * WooCommerce All Currencies - General Section Settings
 *
 * @version 2.4.2
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_All_Currencies_Settings_General' ) ) :

class Alg_WC_All_Currencies_Settings_General extends Alg_WC_All_Currencies_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 2.2.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'woocommerce-all-currencies' );
		parent::__construct();
		if ( 'yes' === get_option( 'alg_wc_all_currencies_enabled', 'yes' ) ) {
			add_filter( 'woocommerce_general_settings', array( $this, 'add_edit_currency_symbol_field' ), PHP_INT_MAX );
		}
	}

	/**
	 * add_edit_currency_symbol_field.
	 *
	 * @version 2.4.0
	 */
	function add_edit_currency_symbol_field( $settings ) {
		$updated_settings = array();
		foreach ( $settings as $section ) {
			if ( isset( $section['id'] ) && 'woocommerce_currency_pos' == $section['id'] ) {
				$code = get_woocommerce_currency();
				$updated_settings[] = array(
					'name'     => __( 'Currency symbol', 'woocommerce-all-currencies' ),
					'desc_tip' => __( 'This sets the currency symbol.', 'woocommerce-all-currencies' ),
					'id'       => "alg_wc_all_currencies_symbols[{$code}]",
					'type'     => 'text',
					'default'  => alg_wc_all_currencies()->core->get_default_currency_symbol(),
					'desc'     => apply_filters( 'alg_wc_all_currencies_filter', sprintf( '<br>You will need <a target="_blank" href="%s">All Currencies for WooCommerce Pro</a> plugin to change currency symbol.',
						'https://wpwham.com/products/all-currencies-for-woocommerce/?utm_source=settings_general&utm_campaign=free&utm_medium=all_currencies' ), 'settings' ),
					'custom_attributes' => apply_filters( 'alg_wc_all_currencies_filter', array( 'readonly' => 'readonly' ), 'settings' ),
				);
			}
			$updated_settings[] = $section;
		}
		return $updated_settings;
	}

	/**
	 * get_settings.
	 *
	 * @version 2.4.0
	 */
	public static function get_settings() {
		$code = get_woocommerce_currency();
		$settings = array(
			array(
				'title'    => __( 'General Options', 'woocommerce-all-currencies' ),
				'type'     => 'title',
				'id'       => 'alg_wc_all_currencies_options',
			),
			array(
				'title'    => __( 'WooCommerce All Currencies', 'woocommerce-all-currencies' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'woocommerce-all-currencies' ) . '</strong>',
				'desc_tip' => __( 'Add all world currencies to your WooCommerce store.', 'woocommerce-all-currencies' )
					. '<br />'
					. sprintf(
						__( 'Currencies will be added to %s.', 'woocommerce-all-currencies' ),
						'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=general' ) . '">' . __( 'WooCommerce > Settings > General', 'woocommerce-all-currencies' ) . '</a>' 
					)
					. '<br />'
					. '<a href="https://wpwham.com/documentation/all-currencies-for-woocommerce/?utm_source=documentation_link&utm_campaign=free&utm_medium=all_currencies" target="_blank" class="button">'
					. __( 'Documentation', 'woocommerce-all-currencies' ) . '</a>',
				'id'       => 'alg_wc_all_currencies_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox'
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_all_currencies_options',
			),
			array(
				'title'    => __( 'Lists Options', 'woocommerce-all-currencies' ),
				'type'     => 'title',
				'id'       => 'alg_wc_all_currencies_lists_options',
			),
			array(
				'title'    => __( 'Add country currencies', 'woocommerce-all-currencies' ),
				'desc'     => __( 'Add', 'woocommerce-all-currencies' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_all_currencies_list_country_enabled',
				'default'  => 'yes',
			),
			array(
				'title'    => __( 'Add crypto currencies', 'woocommerce-all-currencies' ),
				'desc'     => __( 'Add', 'woocommerce-all-currencies' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_all_currencies_list_crypto_enabled',
				'default'  => 'yes',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_all_currencies_lists_options',
			),
			array(
				'title'    => __( 'Symbol Options', 'woocommerce-all-currencies' ),
				'type'     => 'title',
				'id'       => 'alg_wc_all_currencies_symbol_options',
			),
			array(
				'name'     => __( 'Current currency symbol', 'woocommerce-all-currencies' ) . ' (' . get_woocommerce_currency() . ')',
				'id'       => "alg_wc_all_currencies_symbols[{$code}]",
				'type'     => 'text',
				'default'  => alg_wc_all_currencies()->core->get_default_currency_symbol(),
				'desc'     => apply_filters( 'alg_wc_all_currencies_filter', sprintf( '<br>You will need <a target="_blank" href="%s">All Currencies for WooCommerce Pro</a> plugin to change currency symbol.',
					'https://wpwham.com/products/all-currencies-for-woocommerce/?utm_source=settings_general&utm_campaign=free&utm_medium=all_currencies' ), 'settings' ),
				'custom_attributes' => apply_filters( 'alg_wc_all_currencies_filter', array( 'readonly' => 'readonly' ), 'settings' ),
			),
			array(
				'title'    => __( 'Use currency code as symbol', 'woocommerce-all-currencies' ),
				'desc'     => __( 'Enable', 'woocommerce-all-currencies' ),
				'id'       => 'alg_wc_all_currencies_use_code_as_symbol',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Hide currency symbol on frontend', 'woocommerce-all-currencies' ),
				'desc'     => __( 'Hide', 'woocommerce-all-currencies' ),
				'id'       => 'alg_wc_all_currencies_hide_symbol',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_all_currencies_symbol_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_All_Currencies_Settings_General();
