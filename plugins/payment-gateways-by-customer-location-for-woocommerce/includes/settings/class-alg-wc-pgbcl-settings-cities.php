<?php
/**
 * Payment Gateways by Customer Location for WooCommerce - Cities Section Settings
 *
 * @version 1.5.0
 * @since   1.5.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Payment_Gateways_by_Customer_Location_Settings_Cities' ) ) :

class Alg_WC_Payment_Gateways_by_Customer_Location_Settings_Cities extends Alg_WC_Payment_Gateways_by_Customer_Location_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function __construct() {
		$this->id   = 'cities';
		$this->desc = __( 'Cities', 'payment-gateways-by-customer-location-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'    => $this->desc,
				'type'     => 'title',
				'id'       => 'alg_wc_gateways_by_location_city_section_options',
			),
			array(
				'title'    => __( 'Gateways by city', 'payment-gateways-by-customer-location-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'payment-gateways-by-customer-location-for-woocommerce' ) . '</strong>',
				'type'     => 'checkbox',
				'id'       => 'alg_wc_gateways_by_location_city_section_enabled',
				'default'  => 'yes',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_gateways_by_location_city_section_options',
			),
		);
		$gateways  = WC()->payment_gateways->payment_gateways();
		foreach ( $gateways as $key => $gateway ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => $gateway->method_title,
					'type'     => 'title',
					'id'       => "alg_wc_gateways_by_location_city_options[{$key}]",
					'desc'     => __( 'One city per line.', 'payment-gateways-by-customer-location-for-woocommerce' ) .
						( ! in_array( $key, array( 'bacs', 'cheque', 'paypal', 'cod' ) ) ? apply_filters( 'alg_wc_gateways_by_location_settings',
							'<br>' . sprintf( 'You will need %s plugin to set options for the "%s" gateway.',
								'<a target="_blank" href="https://wpfactory.com/item/payment-gateways-by-customer-location-for-woocommerce/">' .
									'Payment Gateways by Customer Location for WooCommerce Pro' . '</a>', $gateway->method_title ) ) : '' ),
				),
				array(
					'title'    => __( 'Include cities', 'payment-gateways-by-customer-location-for-woocommerce' ),
					'desc_tip' => __( 'Payment gateway will be available ONLY if customer is from selected cities.', 'payment-gateways-by-customer-location-for-woocommerce' ) . ' ' .
						__( 'If set empty - option is ignored.', 'payment-gateways-by-customer-location-for-woocommerce' ),
					'id'       => "alg_wc_gateways_by_location_city_include[{$key}]",
					'default'  => '',
					'type'     => 'textarea',
					'css'      => 'height:200px;',
					'custom_attributes' => ( ! in_array( $key, array( 'bacs', 'cheque', 'paypal', 'cod' ) ) ?
						apply_filters( 'alg_wc_gateways_by_location_settings', array( 'readonly' => 'readonly' ), 'array' ) : array() ),
				),
				array(
					'title'    => __( 'Exclude cities', 'payment-gateways-by-customer-location-for-woocommerce' ),
					'desc_tip' => __( 'Payment gateway will NOT be available if customer is from selected cities.', 'payment-gateways-by-customer-location-for-woocommerce' ) . ' ' .
						__( 'If set empty - option is ignored.', 'payment-gateways-by-customer-location-for-woocommerce' ),
					'id'       => "alg_wc_gateways_by_location_city_exclude[{$key}]",
					'default'  => '',
					'type'     => 'textarea',
					'css'      => 'height:200px;',
					'custom_attributes' => ( ! in_array( $key, array( 'bacs', 'cheque', 'paypal', 'cod' ) ) ?
						apply_filters( 'alg_wc_gateways_by_location_settings', array( 'readonly' => 'readonly' ), 'array' ) : array() ),
				),
				array(
					'type'     => 'sectionend',
					'id'       => "alg_wc_gateways_by_location_city_options[{$key}]",
				),
			) );
		}
		return $settings;
	}

}

endif;

return new Alg_WC_Payment_Gateways_by_Customer_Location_Settings_Cities();
