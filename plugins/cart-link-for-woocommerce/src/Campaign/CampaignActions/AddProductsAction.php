<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions;

use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions\AddProductsAction\ModifyProductPrice;
use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignProduct;
use Exception;
use SplSubject;

/**
 * Action for "Adding products"
 */
class AddProductsAction extends AbstractAction {
	public const FIELD_CAMPAIGN_ID = '_campaign_id';

	/**
	 * @param SplSubject $subject .
	 *
	 * @return void
	 * @throws Exception
	 */
	public function update( SplSubject $subject ) {
		$this->add_products();
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	private function add_products() {
		foreach ( $this->campaign->get_products() as $product ) {
			if ( ! $product->get_product()->is_purchasable() ) {
				return;
			}

			$cart_item_data = [
				self::FIELD_CAMPAIGN_ID => $this->campaign->get_id(),
			];

			if ( $product->get_price() !== CampaignProduct::PRICE_UNDEFINED ) {
				$cart_item_data[ ModifyProductPrice::FIELD_CUSTOM_PRICE ] = $product->get_price();
			}

			$product_id = $product->get_product()->get_parent_id();
			if ( $product_id ) {
				$variation_id = $product->get_product()->get_id();
			} else {
				$product_id   = $product->get_product()->get_id();
				$variation_id = 0;
			}

			$this->cart->add_to_cart( $product_id, $product->get_quantity(), $variation_id, [], $cart_item_data );
		}
	}
}
