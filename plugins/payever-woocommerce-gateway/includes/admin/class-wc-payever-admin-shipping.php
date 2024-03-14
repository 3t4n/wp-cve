<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class WC_Payever_Admin_Shipping {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Add metaboxes.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		global $post;

		$screen     = get_current_screen();
		$post_types = array( 'shop_order', 'shop_subscription' );

		if ( in_array( $screen->id, $post_types ) && in_array( $post->post_type, $post_types ) ) {
			$order_id = $post->ID;
			$order    = wc_get_order( $order_id );

			if ( $order ) {
				$payment_method = WC_Payever_Helper::instance()->get_payment_method( $order );

				if ( WC_Payever_Helper::instance()->is_payever_method( $payment_method ) ) {
					$provider = get_post_meta( $order_id, '_payever_shipping_provider', true );

					if ( ! empty( $provider ) ) {
						add_meta_box(
							'payever-shipping-tracking',
							__( 'payever Shipping Tracking', 'payever-woocommerce-gateway' ),
							array(
								$this,
								'meta_box_shipping',
							),
							'shop_order',
							'side',
							'high'
						);
					}
				}
			}
		}
	}

	/**
	 * Show metabox.
	 *
	 * @return void
	 */
	public function meta_box_shipping() {
		global $post;

		$order_id = $post->ID;
		$order    = wc_get_order( $order_id );

		if ( $order ) {
			$tracking_number   = get_post_meta( $order_id, '_payever_tracking_number', true );
			$tracking_url      = get_post_meta( $order_id, '_payever_tracking_url', true );
			$shipping_provider = get_post_meta( $order_id, '_payever_shipping_provider', true );
			$shipping_date     = get_post_meta( $order_id, '_payever_shipping_date', true );

			wc_get_template(
				'admin/metabox-shipping.php',
				array(
					'tracking_number'   => $tracking_number,
					'tracking_url'      => $tracking_url,
					'shipping_provider' => $shipping_provider,
					'shipping_date'     => $shipping_date,
				),
				'',
				__DIR__ . '/../../templates/'
			);
		}
	}
}
