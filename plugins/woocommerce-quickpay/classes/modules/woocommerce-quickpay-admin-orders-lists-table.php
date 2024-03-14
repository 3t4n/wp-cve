<?php

class WC_QuickPay_Admin_Orders_Lists_Table extends WC_QuickPay_Module {

	public function hooks() {
		add_action( 'admin_init', [ $this, 'setup' ] );
	}

	public function setup(): void {
		$HPOS = WC_QuickPay_Helper::is_HPOS_enabled();
		if ( WC_QuickPay_Helper::option_is_enabled( WC_QP()->s( 'quickpay_orders_transaction_info', 'yes' ) ) ) {

			// Add custom column data
			add_filter( $HPOS ? 'woocommerce_shop_order_list_table_columns' : 'manage_edit-shop_order_columns', [ $this, 'filter_shop_order_posts_columns' ] );
			add_filter( $HPOS ? 'manage_woocommerce_page_wc-orders_custom_column' : 'manage_shop_order_posts_custom_column', [ $this, 'custom_column_data' ], 10, 2 );
			add_filter( $HPOS ? 'manage_woocommerce_page_wc-orders--shop_subscription_custom_column' : 'manage_shop_subscription_posts_custom_column', [
				$this,
				'custom_column_data'
			], 10, 2 );
		}


		add_filter( $HPOS ? 'bulk_actions-woocommerce_page_wc-orders' : 'bulk_actions-edit-shop_order', [ $this, 'order_bulk_actions' ], 20 );
		add_filter( $HPOS ? 'handle_bulk_actions-woocommerce_page_wc-orders' : 'handle_bulk_actions-edit-shop_order', [ $this, 'handle_bulk_actions_orders' ], 10, 3 );
		add_filter( $HPOS ? 'handle_bulk_actions-woocommerce_page_wc-orders--shop_subscription' : 'handle_bulk_actions-edit-shop_subscription', [
			$this,
			'handle_bulk_actions_subscriptions'
		], 10, 3 );
		// Subscription actions
		add_filter( 'woocommerce_subscription_bulk_actions', [ $this, 'subscription_bulk_actions' ], 20 );
	}

	/**
	 * Adds a separate column for payment info
	 *
	 * @param array $show_columns
	 *
	 * @return array
	 */
	public function filter_shop_order_posts_columns( $show_columns ): array {
		$column_name   = 'quickpay_transaction_info';
		$column_header = __( 'Payment', 'woo-quickpay' );

		return WC_QuickPay_Helper::array_insert_after( 'shipping_address', $show_columns, $column_name, $column_header );
	}


	/**
	 * apply_custom_order_data function.
	 *
	 * Applies transaction ID and state to the order data overview
	 *
	 * @access public
	 *
	 * @param $column
	 * @param $post_id_or_order_object
	 *
	 * @return void
	 */
	public function custom_column_data( $column, $post_id_or_order_object ): void {
		$order      = woocommerce_quickpay_get_order( $post_id_or_order_object );
		$order_type = \Automattic\WooCommerce\Utilities\OrderUtil::get_order_type( $order );

		// Show transaction ID on the overview
		if ( $order && ( ( $order_type === 'shop_order' && $column === 'quickpay_transaction_info' ) || ( $order_type === 'shop_subscription' && $column === 'order_title' ) ) ) {

			// Insert transaction id and payment status if any
			$transaction_id = WC_QuickPay_Order_Utils::get_transaction_id( $order );

			try {
				if ( $transaction_id && WC_QuickPay_Order_Payments_Utils::is_order_using_quickpay( $order ) ) {
					$transaction = WC_QuickPay_Subscription::is_subscription( $order->get_id() ) ? new WC_QuickPay_API_Subscription() : new WC_QuickPay_API_Payment();
					$transaction->maybe_load_transaction_from_cache( $transaction_id );

					$brand = $transaction->get_brand();

					WC_QuickPay_Views::get_view( 'html-order-table-transaction-data.php', [
						'transaction_id'             => $transaction_id,
						'transaction_order_id'       => WC_QuickPay_Order_Payments_Utils::get_transaction_order_id( $order ),
						'transaction_brand'          => $brand,
						'transaction_brand_logo_url' => WC_QuickPay_Helper::get_payment_type_logo( $brand ?: $transaction->get_acquirer() ),
						'transaction_status'         => WC_QuickPay_Order_Utils::is_failed_renewal( $order ) ? __( 'Failed renewal', 'woo-quickpay' ) : $transaction->get_current_type(),
						'transaction_is_test'        => $transaction->is_test(),
						'is_cached'                  => $transaction->is_loaded_from_cached(),
					] );
				}
			} catch ( QuickPay_Exception|QuickPay_API_Exception $e ) {
				WC_QP()->log->add( sprintf( 'Order list: #%s - %s', $order->get_id(), $e->getMessage() ) );
			}
		}
	}

	/**
	 * @param array $actions
	 *
	 * @return array
	 */
	public function order_bulk_actions( array $actions ): array {
		if ( apply_filters( 'woocommerce_quickpay_allow_orders_bulk_actions', current_user_can( 'manage_woocommerce' ) ) ) {
			$actions['quickpay_capture_recurring']   = __( 'QuickPay: Capture payment and activate subscription', 'woo-quickpay' );
			$actions['quickpay_create_payment_link'] = __( 'QuickPay: Create payment link', 'woo-quickpay' );
		}

		return $actions;
	}

	/**
	 * @param array $actions
	 *
	 * @return array
	 */
	public function subscription_bulk_actions( array $actions ): array {
		if ( apply_filters( 'woocommerce_quickpay_allow_subscriptions_bulk_actions', current_user_can( 'manage_woocommerce' ) ) ) {
			$actions['quickpay_create_payment_link'] = __( 'QuickPay: Create payment link', 'woo-quickpay' );
		}

		return $actions;
	}

	/**
	 * Handle bulk actions.
	 *
	 * @param ?string $redirect_to URL to redirect to.
	 * @param string $action Action name.
	 * @param array $order_ids List of ids.
	 *
	 * @return string
	 */
	public function handle_bulk_actions_orders( ?string $redirect_to, string $action, array $order_ids ): string {
		if ( 'quickpay_capture_recurring' === $action && current_user_can( 'manage_woocommerce' ) ) {
			// Security check
			$this->bulk_action_quickpay_capture_recurring( $order_ids );

			// Redirect client
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}

		if ( 'quickpay_create_payment_link' === $action && current_user_can( 'manage_woocommerce' ) ) {
			$changed = 0;

			foreach ( $order_ids as $id ) {
				if ( ( $order = wc_get_order( $id ) ) && WC_QuickPay_Admin_Orders::get_instance()->order_action_quickpay_create_payment_link( $order ) ) {
					$changed ++;
				} else if ( ( $subscription = WC_QuickPay_Subscription::get_subscription_id( $id ) ) && WC_QuickPay_Admin_Orders::get_instance()->order_action_quickpay_create_payment_link( $subscription ) ) {
					$changed ++;
				}
			}

			if ( $changed ) {
				woocommerce_quickpay_add_admin_notice( sprintf( __( 'Payment links created for %d orders.', 'woo-quickpay' ), $changed ) );
			}

			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}

		return esc_url_raw( $redirect_to );
	}

	/**
	 * Handle bulk actions for subscriptions.
	 *
	 * @param ?string $redirect_to URL to redirect to.
	 * @param string $action Action name.
	 * @param array $order_ids List of ids.
	 *
	 * @return string
	 */
	public function handle_bulk_actions_subscriptions( ?string $redirect_to, string $action, array $order_ids ): string {

		if ( 'quickpay_create_payment_link' === $action && current_user_can( 'manage_woocommerce' ) ) {
			$changed = 0;

			foreach ( $order_ids as $id ) {
				if ( ( $subscription = WC_QuickPay_Subscription::get_subscription( $id ) ) && WC_QuickPay_Admin_Orders::get_instance()->order_action_quickpay_create_payment_link( $subscription ) ) {
					$changed ++;
				}
			}

			if ( $changed ) {
				woocommerce_quickpay_add_admin_notice( sprintf( __( 'Payment links created for %d subscriptions.', 'woo-quickpay' ), $changed ) );
			}

			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}

		return esc_url_raw( $redirect_to );
	}

	/**
	 * @param array $order_ids
	 */
	protected function bulk_action_quickpay_capture_recurring( array $order_ids = [] ): void {
		if ( ! empty( $order_ids ) ) {
			foreach ( $order_ids as $order_id ) {
				if ( $order = woocommerce_quickpay_get_order( $order_id ) ) {
					$payment_method = $order->get_payment_method();
					if ( $payment_method === WC_QP()->id && WC_QuickPay_Subscription::is_renewal( $order ) && $order->needs_payment() ) {
						WC_QP()->scheduled_subscription_payment( $order->get_total(), $order );
					}
				}
			}
		}
	}
}
