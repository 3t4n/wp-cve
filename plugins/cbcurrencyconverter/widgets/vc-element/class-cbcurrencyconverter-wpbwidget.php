<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Currency converted wpbakery widget class
 *
 */
class CBCurrencyConverter_WPBWidget extends WPBakeryShortCode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'bakery_shortcode_mapping' ] );
	}// /end of constructor

	/**
	 * Element Mapping
	 */
	public function bakery_shortcode_mapping() {
		// Map the block with vc_map()
		vc_map( [
			"name"        => esc_html__( "CBX Currency Converter", 'cbcurrencyconverter' ),
			"description" => esc_html__( "CBX Currency Converter Widget", 'cbcurrencyconverter' ),
			"base"        => "cbcurrencyconverter",
			"icon"        => CBCURRENCYCONVERTER_ROOT_URL . 'assets/images/icon.png',
			"category"    => esc_html__( 'CBX Widgets', 'cbcurrencyconverter' ),
			"params"      => apply_filters( 'cbcurrencyconverter_wpbakery_params', [
					[
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Layout', 'cbcurrencyconverter' ),
						'param_name'  => 'layout',
						'admin_label' => true,
						'value'       => CBCurrencyConverterHelper::get_layouts_r(),
						'std'         => 'cal',
					],
					[
						"type"        => "textfield",
						"class"       => "",
						'admin_label' => false,
						"heading"     => esc_html__( 'Decimal Point', 'cbcurrencyconverter' ),
						"param_name"  => "decimal_point",
						'std'         => 2
					],
					//Calculator Settings
					[
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Calculator Header', 'cbcurrencyconverter' ),
						'param_name'  => 'calc_title',
						'std'         => esc_html__( 'Currency Calculator', 'cbcurrencyconverter' ),
						'group'       => esc_html__( 'Calculator Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Default Amount for Calculator', 'cbcurrencyconverter' ),
						'param_name'  => 'calc_default_amount',
						'std'         => 1,
						'group'       => esc_html__( 'Calculator Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'cbccdropdownmulti',
						"class"       => "",
						'admin_label' => false, //it must be false
						'heading'     => esc_html__( 'From Enabled Currencies', 'cbcurrencyconverter' ),
						'param_name'  => 'calc_from_currencies',
						'value'       => CBCurrencyConverterHelper::getCurrencyList_r(),
						'std'         => [ 'USD' ],
						'group'       => esc_html__( 'Calculator Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'From Default Currency', 'cbcurrencyconverter' ),
						'param_name'  => 'calc_from_currency',
						'value'       => CBCurrencyConverterHelper::getCurrencyList_r(),
						'std'         => 'USD',
						'group'       => esc_html__( 'Calculator Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'cbccdropdownmulti',
						"class"       => "",
						'admin_label' => false, //it must be false
						'heading'     => esc_html__( 'To Enabled Currencies', 'cbcurrencyconverter' ),
						'param_name'  => 'calc_to_currencies',
						'value'       => CBCurrencyConverterHelper::getCurrencyList_r(),
						'std'         => [ 'GBP', 'CAD', 'AUD' ],
						'group'       => esc_html__( 'Calculator Settings', 'cbcurrencyconverter' ),
					],

					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'To Default Currency', 'cbcurrencyconverter' ),
						'param_name'  => 'calc_to_currency',
						'value'       => CBCurrencyConverterHelper::getCurrencyList_r(),
						'std'         => 'CAD',
						'group'       => esc_html__( 'Calculator Settings', 'cbcurrencyconverter' ),
					],

					//List Settings
					[
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'List Header', 'cbcurrencyconverter' ),
						'param_name'  => 'list_title',
						'std'         => esc_html__( 'Currency Latest Rates', 'cbcurrencyconverter' ),
						'group'       => esc_html__( 'List Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'textfield',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'Default Amount for list', 'cbcurrencyconverter' ),
						'param_name'  => 'list_default_amount',
						'std'         => 1,
						'group'       => esc_html__( 'List Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'cbccdropdownmulti',
						"class"       => "",
						'admin_label' => false,//it must be false
						'heading'     => esc_html__( 'list To Currency', 'cbcurrencyconverter' ),
						'param_name'  => 'list_to_currencies',
						'value'       => CBCurrencyConverterHelper::getCurrencyList_r(),
						'std'         => [ 'GBP', 'CAD', 'AUD' ],
						'group'       => esc_html__( 'List Settings', 'cbcurrencyconverter' ),
					],
					[
						'type'        => 'dropdown',
						"class"       => "",
						'admin_label' => false,
						'heading'     => esc_html__( 'list From Currency', 'cbcurrencyconverter' ),
						'param_name'  => 'list_from_currency',
						'value'       => CBCurrencyConverterHelper::getCurrencyList_r(),
						'std'         => 'USD',
						'group'       => esc_html__( 'List Settings', 'cbcurrencyconverter' ),
					],
				]
			)
		] );
	}//end bakery_shortcode_mapping
}//end class CBCurrencyConverter_WPBWidget