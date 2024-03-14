<?php

namespace IC\Plugin\CartLinkWooCommerce\Order;

use WC_Order;

class OrderColumnInfo {
	public const POST_TYPE = 'shop_order';
	public const COLUMN_ID = 'campaigns';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', [ $this, 'add_column' ] );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', [ $this, 'display_content' ], 10, 2 );
	}

	/**
	 * @param string[] $columns .
	 *
	 * @return string[]
	 */
	public function add_column( $columns ): array {
		$new_columns = [];

		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;

			if ( $key === 'order_status' ) {
				$new_columns[ self::COLUMN_ID ] = __( 'Cart Link Campaign', 'cart-link-for-woocommerce' );
			}
		}

		return $new_columns;
	}

	/**
	 * @param string $column  .
	 * @param int    $post_id .
	 *
	 * @return void
	 */
	public function display_content( $column, int $post_id ): void {
		if ( self::COLUMN_ID !== $column ) {
			return;
		}

		$order = wc_get_order( $post_id );

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$campaigns = [];

		foreach ( $order->get_meta( SaveOrderCampaign::META_CAMPAIGN_ID, false, 'return' ) as $meta ) {
			$campaigns[] = sprintf( '<a target="_blank" href="%s">%s</a>', esc_url( get_edit_post_link( $meta->value ) ), get_the_title( $meta->value ) );
		}

		if ( $campaigns ) {
			echo wp_kses_post( implode( ', ', $campaigns ) );
		} else {
			echo '-';
		}
	}
}
