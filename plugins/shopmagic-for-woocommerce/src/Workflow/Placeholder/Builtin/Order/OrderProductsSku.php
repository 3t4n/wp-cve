<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderProductsSku extends WooCommerceOrderBasedPlaceholder {

	/**
	 * @var string
	 */
	public const RETURN_FIRST = 'first';

	public function get_slug(): string {
		return 'products_sku';
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return [
			( new SelectField() )
				->set_label( esc_html__( "Return only first product's SKU?", 'shopmagic-for-woocommerce' ) )
				->set_name( self::RETURN_FIRST )
				->set_options(
					[
						'no'  => __( 'No', 'shopmagic-for-woocommerce' ),
						'yes' => __( 'Yes', 'shopmagic-for-woocommerce' ),
					]
				),
		];
	}

	public function get_description(): string {
		return esc_html__(
			'Displays comma separated list of SKU from each product in order. You can also specify to return only SKU of first product from order.',
			'shopmagic-for-woocommerce'
		);
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( \WC_Order::class ) ) {
			return '';
		}

		$skus = [];
		foreach ( $this->resources->get( \WC_Order::class )->get_items() as $item ) {
			if ( $item instanceof \WC_Order_Item_Product ) {
				$product = $item->get_product();
				if ( $product instanceof \WC_Product ) {
					$skus[] = $product->get_sku();
				}
			}
		}

		if ( isset( $parameters[ self::RETURN_FIRST ] ) && $parameters[ self::RETURN_FIRST ] === 'yes' ) {
			return $skus[0];
		}

		return implode( ', ', $skus );
	}
}
