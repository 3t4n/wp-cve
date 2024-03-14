<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use Exception;
use JsonSerializable;
use WC_Product;

class CampaignProduct implements JsonSerializable {
	public const FIELD_ID         = 'id';
	public const FIELD_PRICE      = 'price';
	public const FIELD_QUANTITY   = 'quantity';
	public const FIELD_PRODUCT_ID = 'product_id';

	public const PRICE_UNDEFINED = -1.0;

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @var array
	 */
	private $default = [
		self::FIELD_ID         => '',
		self::FIELD_PRODUCT_ID => 0,
		self::FIELD_PRICE      => self::PRICE_UNDEFINED,
		self::FIELD_QUANTITY   => 1,
	];

	/**
	 * @param array $args .
	 */
	public function __construct( array $args ) {
		if ( ( $args[ self::FIELD_PRICE ] ?? '' ) === '' ) {
			unset( $args[ self::FIELD_PRICE ] );
		}

		$this->args = wp_parse_args( $args, $this->default );
	}

	/**
	 * @return string
	 */
	public function get_id(): string {
		return (string) $this->args[ self::FIELD_ID ];
	}

	/**
	 * @return int
	 */
	public function get_product_id(): int {
		return (int) $this->args[ self::FIELD_PRODUCT_ID ];
	}

	/**
	 * @return float
	 */
	public function get_price(): float {
		return (float) str_replace( ',', '.', $this->args[ self::FIELD_PRICE ] );
	}

	/**
	 * @return float
	 */
	public function get_quantity(): float {
		return (float) str_replace( ',', '.', $this->args[ self::FIELD_QUANTITY ] );
	}

	/**
	 * @return WC_Product
	 * @throws Exception
	 */
	public function get_product(): WC_Product {
		$product = wc_get_product( $this->get_product_id() );

		if ( ! $product instanceof WC_Product ) {
			throw new Exception( __( 'Product doesn\'t exists' ) );
		}

		return $product;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return $this->args;
	}
}
