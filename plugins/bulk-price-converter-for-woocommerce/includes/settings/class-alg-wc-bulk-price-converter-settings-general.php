<?php
/**
 * Bulk Price Converter - General Section Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Bulk_Price_Converter_Settings_General' ) ) :

class Alg_WC_Bulk_Price_Converter_Settings_General extends Alg_WC_Bulk_Price_Converter_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'bulk-price-converter-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'Bulk Price Converter Options', 'bulk-price-converter-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_wc_bulk_price_converter_options',
			),
			array(
				'title'     => __( 'Tool', 'bulk-price-converter-for-woocommerce' ),
				'id'        => 'alg_wc_bulk_price_converter_tool',
				'type'      => 'bulk_price_converter_custom_link',
				'link'      => '<a class="button-primary"' .
					' style="background: green; border-color: green; box-shadow: 0 1px 0 green; "' .
					' href="' . admin_url( 'admin.php?page=alg-wc-bulk-price-converter-tool' ) . '">' . __( 'Bulk price converter tool', 'bulk-price-converter-for-woocommerce' ) . '</a>',
			),
			array(
				'title'     => __( 'Number of decimals', 'bulk-price-converter-for-woocommerce' ),
				'desc_tip'  => __( 'Number of decimals (i.e. step) in tool\'s "number" type fields.', 'bulk-price-converter-for-woocommerce' ),
				'id'        => 'alg_wc_bulk_price_converter_step',
				'default'   => 6,
				'type'      => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'title'     => __( 'Block size', 'bulk-price-converter-for-woocommerce' ),
				'desc_tip'  => __( 'Number of products processed in a single products query.', 'bulk-price-converter-for-woocommerce' ),
				'id'        => 'alg_wc_bulk_price_converter_block_size',
				'default'   => 1024,
				'type'      => 'number',
				'custom_attributes' => array( 'min' => 1 ),
			),
			array(
				'title'     => __( 'Time limit', 'bulk-price-converter-for-woocommerce' ),
				'desc_tip'  => __( 'The maximum execution time for a a single products query, in seconds. If set to zero, no time limit is imposed. If set to -1, default server time limit is used.', 'bulk-price-converter-for-woocommerce' ),
				'id'        => 'alg_wc_bulk_price_converter_time_limit',
				'default'   => -1,
				'type'      => 'number',
				'custom_attributes' => array( 'min' => -1 ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_bulk_price_converter_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Bulk_Price_Converter_Settings_General();
