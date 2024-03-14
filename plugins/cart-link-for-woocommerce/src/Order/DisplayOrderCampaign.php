<?php

namespace IC\Plugin\CartLinkWooCommerce\Order;

use WC_Meta_Data;

class DisplayOrderCampaign {

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_filter( 'woocommerce_order_item_display_meta_key', [ $this, 'modify_meta_key' ], 10, 2 );
		add_filter( 'woocommerce_order_item_display_meta_value', [ $this, 'modify_meta_value' ], 10, 2 );
	}

	/**
	 * @param string $display_key .
	 * @param mixed  $meta        .
	 *
	 * @return mixed|string|void
	 */
	public function modify_meta_key( $display_key, $meta ) {
		if ( $meta instanceof WC_Meta_Data && $meta->key === SaveOrderCampaign::META_CAMPAIGN_ID ) {
			return __( 'Cart Link Campaign', 'cart-link-for-woocommerce' );
		}

		return $display_key;
	}

	/**
	 * @param string $display_value .
	 * @param mixed  $meta          .
	 *
	 * @return mixed|string|void
	 */
	public function modify_meta_value( $display_value, $meta ) {
		if ( $meta instanceof WC_Meta_Data && $meta->key === SaveOrderCampaign::META_CAMPAIGN_ID ) {
			return sprintf( '<a target="_blank" href="%s">%s</a>', esc_url( get_edit_post_link( $meta->value ) ), esc_html( get_the_title( $meta->value ) ) );
		}

		return $display_value;
	}
}
