<?php
/**
 * WooCommerce All Currencies - Settings Functions
 *
 * @version 2.4.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! function_exists( 'alg_wcac_get_list_section_settings' ) ) {
	/**
	 * alg_wcac_get_list_section_settings.
	 *
	 * @version 2.4.0
	 * @since   2.1.1
	 */
	function alg_wcac_get_list_section_settings( $list ) {
		switch ( $list ) {
			case 'country':
				$title      = __( 'Country Currencies', 'woocommerce-all-currencies' );
				$currencies = alg_wcac_get_country_currencies_names();
				$symbols    = alg_wcac_get_country_currencies_symbols();
				break;
			case 'crypto':
				$title      = __( 'Crypto Currencies', 'woocommerce-all-currencies' );
				$currencies = alg_wcac_get_crypto_currencies_names();
				$symbols    = alg_wcac_get_crypto_currencies_symbols();
				break;
		}
		$settings = array(
			array(
				'title'    => $title,
				'type'     => 'title',
				'desc'     => apply_filters( 'alg_wc_all_currencies_filter',
					'<em>' . sprintf(
						__( 'You will need <a target="_blank" href="%s">All Currencies for WooCommerce Pro</a> plugin to change currency names and symbols.', 'woocommerce-all-currencies' ),
						'https://wpwham.com/products/all-currencies-for-woocommerce/?utm_source=settings_currencies&utm_campaign=free&utm_medium=all_currencies'
					) . '</em>', 'settings'
				),
				'id'       => 'alg_wc_all_currencies_list_' . $list . '_options',
			),
		);
		foreach( $currencies as $code => $name ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => '[' . $code . '] ' . $name,
					'id'       => "alg_wc_all_currencies_names[{$code}]",
					'desc'     => __( 'Name', 'woocommerce-all-currencies' ),
					'default'  => $name,
					'type'     => 'text',
					'custom_attributes' => apply_filters( 'alg_wc_all_currencies_filter', array( 'readonly' => 'readonly' ), 'settings' ),
				),
				array(
					'id'       => "alg_wc_all_currencies_symbols[{$code}]",
					'desc'     => __( 'Symbol', 'woocommerce-all-currencies' ),
					'default'  => alg_wc_all_currencies()->core->get_default_currency_symbol( $code, $symbols[ $code ] ),
					'type'     => 'text',
					'custom_attributes' => apply_filters( 'alg_wc_all_currencies_filter', array( 'readonly' => 'readonly' ), 'settings' ),
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_all_currencies_list_' . $list . '_options',
			),
		) );
		return $settings;
	}
}
