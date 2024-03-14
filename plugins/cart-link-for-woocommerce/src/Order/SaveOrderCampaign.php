<?php

namespace IC\Plugin\CartLinkWooCommerce\Order;

use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignActions\AddProductsAction;
use WC_Order;
use WC_Order_Item_Product;

class SaveOrderCampaign {
	public const META_CAMPAIGN_ID = '_campaign_id';

	/**
	 * @var int[]
	 */
	private $campaigns = [];

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'woocommerce_checkout_order_created', [ $this, 'save_campaign_to_order' ] );
		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'save_line_item' ], 10, 3 );
	}

	/**
	 * @param WC_Order $order .
	 *
	 * @return void
	 */
	public function save_campaign_to_order( WC_Order $order ) {
		update_option( 'campaign_used', true );

		foreach ( $this->get_campaigns() as $campaign_id ) {
			$order->add_meta_data( self::META_CAMPAIGN_ID, $campaign_id );
		}

		$order->save();
	}

	/**
	 * @param WC_Order_Item_Product $item          .
	 * @param string                $cart_item_key .
	 * @param array                 $values        .
	 *
	 * @return void
	 */
	public function save_line_item( WC_Order_Item_Product $item, string $cart_item_key, array $values ) {
		if ( isset( $values[ AddProductsAction::FIELD_CAMPAIGN_ID ] ) ) {
			$item->update_meta_data( self::META_CAMPAIGN_ID, (int) $values[ AddProductsAction::FIELD_CAMPAIGN_ID ] );

			$this->campaigns[] = (int) $values[ AddProductsAction::FIELD_CAMPAIGN_ID ];
		}
	}

	/**
	 * @return int[]
	 */
	private function get_campaigns(): array {
		return array_unique( array_filter( $this->campaigns ) );
	}
}
