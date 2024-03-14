<?php

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

class WC_QuickPay_Admin_Orders_Meta extends WC_QuickPay_Module {

	/**
	 * @return mixed|void
	 */
	public function hooks() {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10, 2 );
	}

	/**
	 * add_meta_boxes function.
	 *
	 * Adds the action meta box inside the single order view.
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_boxes( $unused, $post_or_order ): void {
		$hpos_enabled = WC_QuickPay_Helper::is_HPOS_enabled();

		$screen_orders = WC_QuickPay_Requests_Utils::get_edit_order_screen_id();
		$screen_subs   = WC_QuickPay_Requests_Utils::get_edit_subscription_screen_id();

		if ( WC_QuickPay_Requests_Utils::is_current_admin_screen( $screen_orders, $screen_subs ) ) {
			if ( ( $order = woocommerce_quickpay_get_order( $post_or_order ) ) && WC_QuickPay_Order_Payments_Utils::is_order_using_quickpay( $order ) ) {
				add_meta_box( 'quickpay-payment-actions', __( 'QuickPay Payment', 'woo-quickpay' ), [ $this, 'meta_box_payment', ], $screen_orders, 'side', 'high' );
				add_meta_box( 'quickpay-payment-actions', __( 'QuickPay Subscription', 'woo-quickpay' ), [ $this, 'meta_box_subscription', ], $screen_subs, 'side', 'high' );
			}
		}
	}

	/**
	 * Inserts the content of the API actions meta box - Payments
	 *
	 * @param $post_or_order_object
	 *
	 * @return void
	 */
	public function meta_box_payment( $post_or_order_object ): void {
		if ( ! $order = woocommerce_quickpay_get_order( $post_or_order_object ) ) {
			return;
		}

		$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

		$template_data = [
			'transaction_id' => $transaction_id
		];

		if ( $transaction_id ) {
			$state = null;
			try {
				( $transaction = new WC_QuickPay_API_Payment() )->get( $transaction_id );
				$transaction->cache_transaction();

				$state = $transaction->get_state();

				try {
					$status = $transaction->get_current_type();
				} catch ( QuickPay_API_Exception $e ) {
					if ( $state !== 'initial' ) {
						throw new QuickPay_API_Exception( $e->getMessage() );
					}

					$status = $state;
				}

				$template_data = array_merge( $template_data, [
					'transaction'          => $transaction,
					'transaction_brand'    => $transaction->get_brand(),
					'transaction_status'   => $status,
					'transaction_order_id' => WC_QuickPay_Order_Payments_Utils::get_transaction_order_id( $order ),
				] );
			} catch ( QuickPay_Exception|QuickPay_API_Exception $e ) {
				$e->write_to_logs();
				if ( $state !== 'initial' ) {
					$e->write_standard_warning();
				}
			}
		}

		// Show payment ID and payment link for orders that have not yet
		// been paid. Show this information even if the transaction ID is missing.
		$template_data['payment_id']   = WC_QuickPay_Order_Payments_Utils::get_payment_id( $order );
		$template_data['payment_link'] = WC_QuickPay_Order_Payments_Utils::get_payment_link( $order );

		$template_data = apply_filters( 'woocommerce_quickpay_payment_meta_box_template_data', $template_data, $order );

		do_action( 'woocommerce_quickpay_meta_box_payment_before_content', $order, $template_data );

		woocommerce_quickpay_get_template( 'admin/meta-box-order.php', $template_data );

		do_action( 'woocommerce_quickpay_meta_box_payment_after_content', $order, $template_data );
	}


	/**
	 * Inserts the content of the API actions meta box - Subscriptions
	 *
	 * @param $post_or_subscription_object
	 *
	 * @return void
	 */
	public function meta_box_subscription( $post_or_subscription_object ): void {
		if ( ! $subscription = woocommerce_quickpay_get_subscription( $post_or_subscription_object ) ) {
			return;
		}

		$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $subscription );

		$template_data = [
			'transaction_id' => $transaction_id
		];

		if ( $transaction_id && WC_QuickPay_Order_Payments_Utils::is_order_using_quickpay( $subscription ) ) {
			$state = null;
			try {

				$transaction = new WC_QuickPay_API_Subscription();
				$transaction->get( $transaction_id );
				$state = $transaction->get_state();
				try {
					$status = $transaction->get_current_type() . ' (' . __( 'subscription', 'woo-quickpay' ) . ')';
				} catch ( QuickPay_API_Exception $e ) {
					if ( 'initial' !== $state ) {
						throw new QuickPay_API_Exception( $e->getMessage() );
					}
					$status = $state;
				}

				$template_data = array_merge( $template_data, [
					'transaction'          => $transaction,
					'transaction_brand'    => $transaction->get_brand(),
					'transaction_status'   => $status,
					'transaction_order_id' => WC_QuickPay_Order_Payments_Utils::get_transaction_order_id( $subscription ),
				] );

			} catch ( QuickPay_API_Exception $e ) {
				$e->write_to_logs();
				if ( 'initial' !== $state ) {
					$e->write_standard_warning();
				}
			}
		}

		$template_data = apply_filters( 'woocommerce_quickpay_payment_meta_box_template_data', $template_data, $subscription );

		do_action( 'woocommerce_quickpay_meta_box_subscription_before_content', $subscription, $template_data );

		woocommerce_quickpay_get_template( 'admin/meta-box-subscription.php', $template_data );

		do_action( 'woocommerce_quickpay_meta_box_subscription_after_content', $subscription, $template_data );
	}
}
