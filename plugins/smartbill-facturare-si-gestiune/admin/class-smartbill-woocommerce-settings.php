<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/admin
 */

/**
 * Smartbill Settings
 *
 * @copyright  Intelligent IT SRL 2018
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce_Settings {
	const SMARTBILL_DATABASE_INVOICE_STATUS_DRAFT = 0;
	const SMARTBILL_DATABASE_INVOICE_STATUS_FINAL = 1;
	const SMARTBILL_DOCUMENT_TYPE_INVOICE         = 0;
	const SMARTBILL_DOCUMENT_TYPE_ESTIMATE        = 1;
	const SMARTBILL_VAT_VALUE_FOR_PLATFORM        = 'WooCommerce';

	/**
	 * Function used to determine if company is VAT payable or not
	 *
	 * @return boolean $status_vat_payable
	 */
	public static function is_vat_payable() {
		$vat_rates = self::get_vat_rates();
		if ( ! is_array( $vat_rates ) ) {
			$status_vat_payable = false;
		} else {
			$status_vat_payable = true;
		}
		return $status_vat_payable;
	}

	/**
	 * Function used to get the VAT rates for a company
	 *
	 * @throws \Exception Invalid auth.
	 * @throws Exception Missing vat code.
	 *
	 * @return string|array $vat_rates
	 */
	public static function get_vat_rates() {
		$options = get_option( 'smartbill_plugin_options' );

			// Force VAT Code from DB.
		try {
			if ( empty( $options['username'] ) || empty( $options['password'] ) ) {
				throw new \Exception( __( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
			}
			if ( empty( $options['vat_code'] ) ) {
				throw new Exception( __( 'Va rugam sa completati toate datele din sectiunea de autentificare.', 'smartbill-woocommerce' ) );
			}

			$taxes  = get_option( 'smartbill_s_taxes' );

			if ( !empty( $taxes ) ) {
				if ( is_array( $taxes ) && isset( $taxes['taxes'] ) ) {
					return $taxes['taxes'];
				} else {
					update_option( 'smartbill_s_taxes', 'neplatitor' );
					return __( 'Firma este neplatitoare de TVA sau nu au fost setate valori de TVA in SmartBill Cloud', 'smartbill-woocommerce' );
				}
			} else {
				$client = new SmartBill_Cloud_REST_Client( $options['username'], $options['password'] );
				$taxes = $client->get_taxes( $options['vat_code'] );
				if ( is_array( $taxes ) && isset( $taxes['taxes'] ) ) {
					update_option( 'smartbill_s_taxes', $taxes );
					return $taxes['taxes'];
				} else {
					update_option( 'smartbill_s_taxes', 'neplatitor' );
					return __( 'Firma este neplatitoare de TVA sau nu au fost setate valori de TVA in SmartBill Cloud', 'smartbill-woocommerce' );
				}
			}
		} catch ( Exception $e ) {
			update_option( 'smartbill_s_taxes', 'neplatitor' );
			return $e->getMessage();
		}

	}

	/**
	 * Function used to get the measuring units for the company.
	 *
	 * @param string $vat_code vat code.
	 *
	 * @throws \Exception Invalid Auth.
	 *
	 * @return string|array $measuring_units
	 */
	public static function get_measuring_units( $vat_code = null ) {
		$options = get_option( 'smartbill_plugin_options' );
		// Force VAT Code from DB.
		if ( ! $vat_code ) {
			$vat_code = $options['vat_code'];
		}
		try {
			if ( empty( $options['username'] ) || empty( $options['password'] ) ) {
				throw new \Exception( __( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
			}
			$client = new SmartBill_Cloud_REST_Client( $options['username'], $options['password'] );
			$mu     = $client->get_measuring_units( $vat_code );
			if ( is_array( $mu ) && isset( $mu['mu'] ) ) {
				return $mu['mu'];
			} else {
				return __( 'Firma este neplatitoare de TVA sau nu au fost setate valori de TVA in SmartBill Cloud', 'smartbill-woocommerce' );
			}
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

	}

	/**
	 * Function used to get the measuring units for the company
	 *
	 * @return array $currencies
	 */
	public static function get_currencies() {
		$woo_currency = get_woocommerce_currency() . ' ';
		$currencies   = array(
			array(
				'value' => 'RON',
				'label' => __( 'RON - Leu', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'EUR',
				'label' => __( 'EUR - Euro', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'USD',
				'label' => __( 'USD - Dolar', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'GBP',
				'label' => __( 'GBP - Lira sterlina', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'CAD',
				'label' => __( 'CAD - Dolar canadian', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'AUD',
				'label' => __( 'AUD - Dolar australian', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'CHF',
				'label' => __( 'CHF - Franc elvetian', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'TRY',
				'label' => __( 'TRY - Lira turceasca', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'CZK',
				'label' => __( 'CZK - Coroana ceheasca', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'DKK',
				'label' => __( 'DKK - Coroana daneza', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'HUF',
				'label' => __( 'HUF - Forintul maghiar', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'MDL',
				'label' => __( 'MDL - Leu moldovenesc', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'SEK',
				'label' => __( 'SEK - Coroana suedeza', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'BGN',
				'label' => __( 'BGN - Leva bulgareasca', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'NOK',
				'label' => __( 'NOK - Coroana norvegiana', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'JPY',
				'label' => __( 'JPY - Yenul japonez', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'EGP',
				'label' => __( 'EGP - Lira egipteana', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'PLN',
				'label' => __( 'PLN - Zlotul polonez', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'RUB',
				'label' => __( 'RUB - Rubla', 'smartbill-woocommerce' ),
			),
			array(
				'value' => $woo_currency,
				'label' => __( 'Preluata din WooCommerce', 'smartbill-woocommerce' ),
			),
		);

		return $currencies;
	}

	/**
	 * Function used to get the languages smartbill documents.
	 *
	 * @return array $languages
	 */
	public static function get_languages() {
		$languages = array(
			array(
				'value' => 'RO',
				'label' => __( 'Romana', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'EN',
				'label' => __( 'Engleza', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'FR',
				'label' => __( 'Franceza', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'IT',
				'label' => __( 'Italiana', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'SP',
				'label' => __( 'Spaniola', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'HU',
				'label' => __( 'Maghiara', 'smartbill-woocommerce' ),
			),
			array(
				'value' => 'DE',
				'label' => __( 'Germana', 'smartbill-woocommerce' ),
			),
		);
		return $languages;
	}

	/**
	 * Function used to get the stocks for the company
	 *
	 * @param string $vat_code vat code.
	 *
	 * @throws \Exception Invalid response.
	 * @throws Exception Invalid response.
	 *
	 * @return string|array $stocks
	 */
	public static function get_stock( $vat_code = null ) {
		$options = get_option( 'smartbill_plugin_options' );
		// Force VAT Code from DB.
		if ( ! $vat_code ) {
			$vat_code = $options['vat_code'];
		}
		try {
			if ( empty( $options['username'] ) || empty( $options['password'] ) ) {
				throw new \Exception( __( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
			}
			$client       = new SmartBill_Cloud_REST_Client( $options['username'], $options['password'] );
			$data         = array(
				'cif'           => $vat_code,
				'date'          => gmdate( 'Y-m-d' ),
				'warehouseName' => '',
				'productName'   => '',
				'productCode'   => '',
			);
			$stocks       = $client->productsStock( $data );
			$final_values = array();
			if ( is_array( $stocks ) ) {
				update_option( 'smartbill_stocks', $stocks );
				foreach ( $stocks as $stock ) {
					$item['value']                  = $stock['warehouse']['warehouseName'];
					$item['label']                  = $stock['warehouse']['warehouseName'];
					$final_values[ $item['value'] ] = $item['label'];
				}
				return $final_values;
			} else {
				throw new Exception( __( 'Raspuns invalid primit de la SmartBill Cloud la primirea informatiilor despre stocuri.', 'smartbill-woocommerce' ) );
			}
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * This function returns the invoice + estimate series from the WP DB and the SmartBill Cloud
	 *
	 * @throws \Exception Invalid auth.
	 *
	 * @return array|false
	 */
	public static function get_series_settings() {
		try {

			$options       = get_option( 'smartbill_plugin_options_settings' );
			$login_options = get_option( 'smartbill_plugin_options' );

			if ( empty( $login_options['username'] ) || empty( $login_options['password'] ) ) {
				throw new \Exception( __( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
			}

			if ( ! empty( $options ) && is_array( $options ) && isset( $options['invoice_series'] ) ) {
				$smartbill_settings['selected_invoice_series'] = isset( $options['invoice_series'] ) ? $options['invoice_series'] : '';
			} else {
				$smartbill_settings['selected_invoice_series'] = null;
			}
			if ( ! empty( $options ) && is_array( $options ) && isset( $options['estimate_series'] ) ) {
				$smartbill_settings['selected_estimate_series'] = isset( $options['estimate_series'] ) ? $options['estimate_series'] : '';
			} else {
				$smartbill_settings['selected_estimate_series'] = null;
			}
			$invoice_series  = Smartbill_Woocommerce_Admin_Settings_Fields::get_series( 'f' );
			$estimate_series = array();
			if ( false !== $invoice_series ) {
				$estimate_series = Smartbill_Woocommerce_Admin_Settings_Fields::get_series( 'p' );
			}
			$smartbill_settings['invoice_series']  = $invoice_series;
			$smartbill_settings['estimate_series'] = $estimate_series;

			return $smartbill_settings;
		} catch ( Exception $e ) {
			$error = $e->getMessage();
			add_settings_error( 'smartbill_settings_company', '', $error, 'error' );
			return false;
		}
	}
}

//phpcs:ignore
