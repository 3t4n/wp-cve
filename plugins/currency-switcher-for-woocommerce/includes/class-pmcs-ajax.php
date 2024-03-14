<?php

class PMCS_Ajax {
	public function __construct() {
		add_action( 'wp_ajax_woocommerce_remove_order_coupon', array( $this, 'setup_admin_switcher' ), 0 );
		add_action( 'wp_ajax_woocommerce_add_coupon_discount', array( $this, 'setup_admin_switcher' ), 0 );

		add_action( 'wp_ajax_woocommerce_add_order_item', array( $this, 'setup_admin_order_switcher' ), 0 );
		add_action( 'wp_ajax_woocommerce_remove_order_item', array( $this, 'setup_admin_order_switcher' ), 0 );
		add_action( 'wp_ajax_woocommerce_save_order_items', array( $this, 'setup_admin_order_switcher' ), 0 );

		add_action( 'wp_ajax_nopriv_woocommerce_apply_coupon', array( $this, 'front_end_setup_switcher' ), 0 );
		add_action( 'wp_ajax_woocommerce_apply_coupon', array( $this, 'front_end_setup_switcher' ), 0 );
	}

	public function change_order_currency( $currency ) {
		if ( ! isset( $_GET['currency'] ) ) {
			return $currency;
		}
		$adding_currency = sanitize_text_field( wp_unslash( $_GET['currency'] ) );
		$currencies = pmcs()->switcher->get_currencies();
		if ( isset( $currencies[ $adding_currency ] ) ) {
			return $adding_currency;
		}
		return $currency;
	}

	public function setup_admin_order_switcher() {
		if ( ! isset( $_GET['currency'] ) ) {
			return $currency;
		}
		pmcs()->switcher->doing;
		$adding_currency = sanitize_text_field( wp_unslash( $_GET['currency'] ) );
		if ( empty( $adding_currency ) ) {
			return;
		}
		$order           = wc_get_order( absint( $_POST['order_id'] ) );
		$currency        = $order->get_currency();
		$rate            = pmcs()->switcher->get_rate( $adding_currency );
		$base_currency   = $order->get_meta( '_base_currency' );

		$hooked = false;
		if ( empty( $base_currency ) && pmcs()->switcher->get_woocommerce_currency() != $adding_currency ) {
			add_filter( 'woocommerce_order_get_currency', array( $this, 'change_order_currency' ), 95 );
			pmcs()->switcher->set_currency( $adding_currency );
			pmcs()->switcher->set_change( true );
			pmcs()->switcher->set_rate( $rate );
			pmcs()->switcher->remove_order_item_hooks();
			$hooked = true;
		} elseif ( pmcs()->switcher->get_woocommerce_currency() != $currency ) {
			$rate = $order->get_meta( '_currency_rate' );
			pmcs()->switcher->set_change( true );
			pmcs()->switcher->set_rate( $rate );
		}

		// wp_send_json(
		// 	array(
		// 		'rate' => $rate,
		// 		'hooked' => $hooked,
		// 		'adding_currency' => $adding_currency,
		// 		'base_currency' => $base_currency,
		// 		'currency' => $order->get_currency(),
		// 		'currencies' => pmcs()->switcher->get_currencies(),
		// 		'get_woocommerce_currency' => pmcs()->switcher->get_woocommerce_currency(),
		// 	)
		// );

	}

	/**
	 * Setup admin switcher in admin.
	 *
	 * @todo convert rate when edit order.
	 *
	 * @return void
	 */
	public function setup_admin_switcher() {
		$order         = wc_get_order( absint( $_POST['order_id'] ) );
		$currency      = $order->get_currency();
		$rate          = $order->get_meta( '_currency_rate' );
		$base_currency = $order->get_meta( '_base_currency' );
		if ( pmcs()->switcher->get_woocommerce_currency() != $currency ) {
			pmcs()->switcher->set_change( true );
			pmcs()->switcher->set_rate( $rate );
			add_filter( 'woocommerce_coupon_get_amount', array( pmcs()->switcher, 'coupon_get_amount' ), 95 );
		}
	}

	/**
	 * Setup swicther on front-end ajax
	 *
	 * @todo Convert coupon amount rate.
	 *
	 * @return void
	 */
	public function front_end_setup_switcher() {
		if ( pmcs()->switcher->will_change() ) {
			pmcs()->switcher->set_change( true );
			pmcs()->switcher->set_rate( $rate );
			add_filter( 'woocommerce_coupon_get_amount', array( pmcs()->switcher, 'coupon_get_amount' ), 95 );
		}
	}

}
