<?php

namespace Faire\Wc\Api\Client;

use Exception;
use Faire\Wc\Api\Utils\Args_Parser;
use Faire\Wc\Api\Client\Faire_Product_Variant_Info;


/**
 * A class that represents a Faire Product, which corresponds to a WooCommerce product.
 *
 * @since [*next-version*]
 */
class Faire_Product_Info {
	/**
	 * The array of product information.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public $product;

	/**
	 * The array of product variants information.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public $variants;

	/**
	 * The array of product variant option sets information.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public $variant_option_sets;

	/**
	 * The array of product preorder details information.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public $preorder_details;

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

		$this->product = Args_Parser::parse_args( $args, $this->get_product_schema() );

		if ( isset( $args['variants'] ) && $args['variants'] ) {
			foreach ( $args['variants'] as $variant_args ) {
				$variant_info     = new Faire_Product_Variant_Info( $variant_args );
				$this->variants[] = $variant_info->variant;
			}
		}

		if ( isset( $args['variant_option_sets'] ) && $args['variant_option_sets'] ) {
			foreach ( $args['variant_option_sets'] as $var_opt_args ) {
				$this->variant_option_sets[] = Args_Parser::parse_args( $var_opt_args, $this->get_variant_option_set_schema() );
			}
		}

		if ( isset( $args['preorderable'] ) && $args['preorderable'] && isset( $args['preorder_details'] ) && $args['preorder_details'] ) {
			$this->preorder_details = Args_Parser::parse_args( $args['preorder_details'], $this->get_preorder_details_schema() );
		}

	}

	/**
	 * Retrieves the args scheme to use with {@link Args_Parser} for parsing product info.
	 *
	 * @since [*next-version*]
	 *
	 * @return array
	 */
	protected function get_product_schema() {
		$self = $this;

		return array(
			'idempotence_token'                => array(
				// 'required' => true,
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'idempotence token is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'name'                             => array(
				'required' => false,
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Name is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'short_description'                => array(
				'required' => false,
				'default'  => '',
			// 'validate' => function( $item ) {
			// if ( !empty( $item ) ) {
			// if ( strlen($item) > 75 ) {
			// throw new Exception( __( 'Short Description cannot be longer than 75 characters!', 'faire-for-woocommerce' ) );
			// }
			// }
			// },
			),
			'description'                      => array(
				'default' => '',
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) {
				// if ( strlen($item) > 1000 ) {
				// throw new Exception( __( 'Description cannot be longer than 1000 characters!', 'faire-for-woocommerce' ) );
				// }
				// }
				// },
			),
			'lifecycle_state'                  => array(
				'default' => '',
			),
			'unit_multiplier'                  => array(
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Unit Multiplier is empty!', 'faire-for-woocommerce' ) );
				// } elseif ( !is_numeric( $item ) ) {
				// throw new Exception( __( 'Unit Multiplier is not a valid number!', 'faire-for-woocommerce' ) );
				// }
				// },
				'sanitize' => function( $item ) use ( $self ) {
					return (int) $item;
				},
			),
			'minimum_order_quantity'           => array(
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Minimum Order Quantity is empty!', 'faire-for-woocommerce' ) );
				// } elseif ( !is_numeric( $item ) ) {
				// throw new Exception( __( 'Minimum Order Quantity not a valid number!', 'faire-for-woocommerce' ) );
				// }
				// },
				'sanitize' => function( $item ) use ( $self ) {
					return (int) $item;
				},
			),
			'per_style_minimum_order_quantity' => array(
				// 'validate' => function( $item ) {
				// if ( $item != '' ) { // validate if not empty
				// if ( !is_numeric( $item ) ) {
				// throw new Exception( __( 'Per Style Minimum Order Quantity not a valid number!', 'faire-for-woocommerce' ) );
				// }
				// }
				// },
				'sanitize' => function( $item ) use ( $self ) {
					if ( '' !== $item ) { // sanitize if not empty.
						return (int) $item;
					}
					return null;
				},
			),
			'images'                           => array(
				'required' => false,
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) { //validate if not empty
				// if ( !is_array($item) ) {
				// throw new Exception( __( 'Images contains no valid urls!', 'faire-for-woocommerce' ) );
				// } else {
				// foreach ( $item as $k => $v ) {
				// if ( !isset($v['url']) || empty($v['url']) ) {
				// throw new Exception( __( 'Images contains invalid urls!', 'faire-for-woocommerce' ) );
				// }
				// }
				// }
				// }
				// },
			),
			'taxonomy_type'                    => array(
				'required' => false,
				'default'  => '',
				// 'validate' => function( $item ) {
				// if ( !empty( $item ) ) { // validate if not empty
				// if ( !is_array($item) ) {
				// throw new Exception( __( 'Taxonomy type is invalid!', 'faire-for-woocommerce' ) );
				// } else {
				// if ( !isset($item['id']) || empty($item['id']) ) {
				// throw new Exception( __( 'Taxonomy type has invalid ids!', 'faire-for-woocommerce' ) );
				// }
				// }
				// }
				// },
			),
			'allow_sales_when_out_of_stock'    => array(
				'required' => false,
				'default'  => '',
				// 'validate' => function( $item ) {
				// if ( $item != '' ) { // validate if not empty
				// if ( !is_bool($item) ) {
				// throw new Exception( __( 'Allow Sales When Out of Stock is invalid!', 'faire-for-woocommerce' ) );
				// }
				// }
				// },
			),
			'preorderable'                     => array(
				'required' => false,
				// 'validate' => function( $item ) {
				// if ( $item != '' ) { // validate if not empty
				// if ( !is_bool($item) ) {
				// throw new Exception( __( 'Preorderable is invalid!', 'faire-for-woocommerce' ) );
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
				// 'required' => true,
				// 'validate' => function( $item ) {
				// if ( empty( $item ) ) {
				// throw new Exception( __( 'Variant Option Set Name is empty!', 'faire-for-woocommerce' ) );
				// }
				// },
			),
			'values' => array(
				// 'required' => true,
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
