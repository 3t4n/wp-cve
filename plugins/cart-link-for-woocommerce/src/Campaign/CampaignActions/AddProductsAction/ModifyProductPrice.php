<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions\AddProductsAction;

use WC_Cart;

/**
 * Modify product price.
 */
class ModifyProductPrice {
	public const FIELD_CUSTOM_PRICE = 'custom_price';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'modify_product_price' ] );
	}

	/**
	 * @param WC_Cart $cart .
	 *
	 * @return void
	 */
	public function modify_product_price( WC_Cart $cart ) {
		foreach ( $this->get_cart_items( $cart ) as $cart_content ) {
			$cart_content['data']->set_price( $cart_content[ self::FIELD_CUSTOM_PRICE ] );
		}
	}

	/**
	 * @param WC_Cart $cart .
	 *
	 * @return array
	 */
	private function get_cart_items( WC_Cart $cart ): array {
		return array_filter(
			$cart->get_cart_contents(),
			function ( $cart_content ) {
				return isset( $cart_content[ self::FIELD_CUSTOM_PRICE ] );
			}
		);
	}
}
