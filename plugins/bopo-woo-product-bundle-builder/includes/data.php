<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOPO_BUNDLE_DATA {
	private $params;
	private $default;
	protected static $instance = null;

	public function __construct() {
		global $bopobb_settings;
		if ( ! $bopobb_settings ) {
			$bopobb_settings = get_option( 'woo_bopo_bundle_params', array() );
		}
		$this->default = array(
			//Bundle
			'bopobb_item_view'        => array(
				array(
					'product_quantity',
					1,
				),
				array(
					'product_stock',
					1,
				),
				array(
					'product_ratting',
					1,
				),
				array(
					'product_description',
					0,
				),
			),
			'bopobb-first-product'    => 0,
			'bopobb_view_quantity'    => 1,
			'bopobb_view_stock'       => 1,
			'bopobb_view_ratting'     => 0,
			'bopobb_view_description' => 0,
			'bopobb_link_individual'  => 0,
			'bopobb_price_by'         => 'subtotal',
			'bopobb_price_saved'      => 'saved',
			'bopobb_coupon_res'       => 'bundled',
			'bopobb_custom_css'       => '',

			//swap button
			'bopobb_swap_text'        => 'Change',
			'bopobb_swap_pos'         => 1,
			'bopobb_swap_background'  => '#446084',
			'bopobb_swap_color'       => '#ffffff',

			//popup
			'bopobb_popup_title'      => 'Please select your product',
			'bopobb_popup_background' => '#ffffff',
			'bopobb_popup_color'      => '#000000',
			'bopobb_popup_fontsize'   => '',
			'bopobb_popup_page_items' => 32,

			'bopobb_swap_icon' => array(
				"bopobb_swap_icon-share-files",
				"bopobb_swap_icon-ab-testing",
				"bopobb_swap_icon-ab-testing-1",
				"bopobb_swap_icon-compare",
				"bopobb_swap_icon-compare-1",
				"bopobb_swap_icon-compare-2",
				"bopobb_swap_icon-comparative",
				"bopobb_swap_icon-risk",
				"bopobb_swap_icon-compare-3",
				"bopobb_swap_icon-compare-4",
				"bopobb_swap_icon-compare-5",
				"bopobb_swap_icon-decision",
				"bopobb_swap_icon-advantages",
				"bopobb_swap_icon-computer",
				"bopobb_swap_icon-diagram",
				"bopobb_swap_icon-balance",
				"bopobb_swap_icon-file",
				"bopobb_swap_icon-lists",
				"bopobb_swap_icon-website",
				"bopobb_swap_icon-skill",
				"bopobb_swap_icon-file-1",
				"bopobb_swap_icon-phone",
				"bopobb_swap_icon-compare-6",
				"bopobb_swap_icon-compare-7"
			),

			'bopobb_limit_item'      => 2,
			'bopobb_single_template' => 1,
			'bopobb_template_title'  => array(
				'vertical-bundle',
				'horizontal-bundle'
			),
			'bopobb_type_include'    => array(
				'simple',
				'variable'
			),
			'bopobb_type_exclude'    => array(
				'bundle',
				'bopobb',
				'variable',
				'composite',
				'grouped',
				'external'
			),
		);
		$this->params  = apply_filters( 'woo_bopo_bundle_params', wp_parse_args( $bopobb_settings, $this->default ) );
	}

	/**
	 * Get params of setting
	 * @return mixed
	 */
	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'woo_bopo_bundle_params' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	/**
	 * Get default param setting
	 * @return mixed
	 */
	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'woo_bopo_bundle_params' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}