<?php

namespace Faire\Wc\Api\Client;

use Exception;
use Faire\Wc\Api\Utils\Args_Parser;

/**
 * A class that represents a Faire Product, which corresponds to a WooCommerce product.
 *
 * @since [*next-version*]
 */
class Faire_Product_Variant_Info {
	/**
	 * The array of variant information.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public $variant;

	/**
	 * Constructor.
	 *
	 * @since [*next-version*]
	 *
	 * @param array $args The arguments to parse.
	 */
	public function __construct( $args ) {
		$this->parse_args( $args );
	}

	/**
	 * Parses the arguments and sets the instance's properties.
	 *
	 * @since [*next-version*]
	 *
	 * @param array $args The arguments to parse.
	 *
	 * @throws Exception If some data in $args did not pass validation.
	 */
	protected function parse_args( $args ) {

		$variant = Args_Parser::parse_args( $args, $this->get_variant_schema() );

		// Remove id if empty.
		if ( isset( $variant['id'] ) && empty( $variant['id'] ) ) {
			unset( $variant['id'] );
		}

		$this->variant = $variant;
	}

	/**
	 * Retrieves the args scheme to use with {@link Args_Parser} for parsing product info.
	 *
	 * @since [*next-version*]
	 *
	 * @return array
	 */
	protected function get_variant_schema() {
		$self = $this;

		return array(
			'id'                    => array(
				'required' => false,
				'default'  => '',
			),
			'idempotence_token'     => array(
				// 'required' => true,
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Variant Idempotence token is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'lifecycle_state'       => array(
				'required' => false,
				'default'  => '',
			),
			'sku'                   => array(
				'required' => false,
				'default'  => '',
			),
			'tariff_code'           => array(
				'required' => false,
				'default'  => '',
			),
			'available_quantity'    => array(
				'required' => false,
				'default'  => '',
				// 'validate' => function( $amount ) {
				// if ( $amount !== ''&& $amount !== null && ! is_numeric( $amount ) ) {
				// throw new Exception( __( 'Variant Available Quantiy must be numeric', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'options'               => array(
				'required' => false,
				'default'  => array(),
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) { //validate if not empty
				// if ( !is_array($item) ) {
				// throw new Exception( __( 'Variant Options contains no options!', 'faire-for-woocommerce' ) );
				// } else {
				// foreach ( $item as $k => $v ) {
				// if ( !isset($v['name']) || empty($v['name']) ) {
				// throw new Exception( __( 'Variant Options contains invalid options!', 'faire-for-woocommerce' ) );
				// }
				// if ( !isset($v['value']) || empty($v['value']) ) {
				// throw new Exception( __( 'Variant Options contains invalid options!', 'faire-for-woocommerce' ) );
				// }
				// }
				// }
				// }
				// },
			),
			'images'                => array(
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) { //validate if not empty
				// if ( !is_array($item) ) {
				// throw new Exception( __( 'Variant Images contains no valid urls!', 'faire-for-woocommerce' ) );
				// } else {
				// foreach ( $item as $k => $v ) {
				// if ( !isset($v['url']) || empty($v['url']) ) {
				// throw new Exception( __( 'Variant Images contains invalid urls!', 'faire-for-woocommerce' ) );
				// }
				// }
				// }
				// }
				// },
			),
			'measurements'          => array(
				'required' => false,
			),
			'backordered_until'     => array(
				'required' => false,
				'default'  => '',
			),
			'wholesale_price_cents' => array(
				'required' => false,
				'default'  => '',
				// 'validate' => function( $amount ) {
				// if ( $amount !== '' && ! is_numeric( $amount ) ) {
				// throw new Exception( __( 'Variant Wholesale Price must be numeric', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'retail_price_cents'    => array(
				'required' => false,
				'default'  => '',
				// 'validate' => function( $amount ) {
				// if ( $amount !== '' && ! is_numeric( $amount ) ) {
				// throw new Exception( __( 'Variant Retail Price must be numeric', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'prices'                => array(
				'required' => false,
				'default'  => array(),
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) { //validate if not empty
				// if ( !is_array($item) ) {
				// throw new Exception( __( 'Variant Prices contains no values!', 'faire-for-woocommerce' ) );
				// } else {
				// foreach ( $item as $k => $v ) {
				// if ( !isset($v) || empty($v) ) {
				// throw new Exception( __( 'Variant Prices item contains invalid values!', 'faire-for-woocommerce' ) );
				// }
				// }
				// }
				// }
				// },
			),
		);
	}

	/**
	 * Retrieves the args scheme to use with {@link Args_Parser} for parsing product info.
	 *
	 * @since [*next-version*]
	 *
	 * @return array
	 */
	protected function get_variant_option_set_schema() {
		$self = $this;

		return array(
			'name'   => array(
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Variant Option Set Name is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'values' => array(
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) { //validate if not empty
				// if ( !is_array($item) ) {
				// throw new Exception( __( 'Variant Options Set contains no values!', 'faire-for-woocommerce' ) );
				// } else {
				// foreach ( $item as $k => $v ) {
				// if ( !isset($v) || empty($v) ) {
				// throw new Exception( __( 'Variant Options Set contains invalid values!', 'faire-for-woocommerce' ) );
				// }
				// }
				// }
				// }
				// },
			),
		);
	}

	/**
	 * Retrieves the args scheme to use with {@link Args_Parser} for parsing product info.
	 *
	 * @since [*next-version*]
	 *
	 * @return array
	 */
	protected function get_preorder_details_schema() {
		$self = $this;

		return array(
			'order_by_date'                  => array(
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Preorder Order By Date is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'keep_active_past_order_by_date' => array(
				// 'validate' => function( $item ) {
				// if ( $item != '' ) { // validate if not empty
				// if ( !is_bool($item) ) {
				// throw new Exception( __( 'Preorder Keep Active Past Order By Date is invalid!', 'faire-for-woocommerce' ) );
				// }
				// }
				// },
			),
			'expected_ship_date'             => array(
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Preorder Expected Ship Date is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'expected_ship_window_end_date'  => array(
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Preorder Expected Window End Date is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
		);
	}
}
