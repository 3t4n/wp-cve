<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions;

use SplSubject;

class ClearCartAction extends AbstractAction {

	/**
	 * @param SplSubject $subject .
	 *
	 * @return void
	 */
	public function update( SplSubject $subject ) {
		if ( $this->campaign->clear_cart() ) {
			$this->cart->empty_cart();
		} else {
			$this->clear_current_campaigns_products();
		}
	}

	/**
	 * @return void
	 */
	private function clear_current_campaigns_products() {
		foreach ( $this->get_cart_items() as $cart_item_key => $cart_item ) {
			$this->cart->remove_cart_item( $cart_item_key );
		}
	}

	/**
	 * @return array
	 */
	private function get_cart_items(): array {
		return array_filter(
			$this->cart->get_cart_contents(),
			function ( $cart_item ) {
				return isset( $cart_item[ AddProductsAction::FIELD_CAMPAIGN_ID ] ) && (int) $cart_item[ AddProductsAction::FIELD_CAMPAIGN_ID ] === $this->campaign->get_id();
			}
		);
	}
}
