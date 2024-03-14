<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Admin_Order_Edit' ) ) {
	return;
}

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Admin_Order_Edit {
	use WC_Payever_WP_Wrapper_Trait;
	use WC_Payever_Action_Decider_Wrapper_Trait;
	use WC_Payever_Api_Wrapper_Trait;

	/** @var WC_Payever_Order_Total */
	private $order_total_model;

	/** @var WC_Payever_Order_Wrapper */
	private $order_wrapper;

	/** @var array */
	private $is_shipping_allowed_for_order_id = array();

	/** @var array */
	private $is_cancel_allowed_for_order_id = array();

	/**
	 * Add actions.
	 *
	 * @param WC_Payever_WP_Wrapper|null $wp_wrapper
	 */
	public function __construct( $wp_wrapper = null ) {
		if ( null !== $wp_wrapper ) {
			$this->set_wp_wrapper( $wp_wrapper );
		}

		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_order_item_headers',
			array( $this, 'payever_order_item_headers' ),
			10,
			1
		);

		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_order_item_values',
			array( $this, 'payever_order_item_values' ),
			10,
			3
		);

		$this->get_wp_wrapper()->add_action(
			'woocommerce_order_item_add_action_buttons',
			array( $this, 'add_buttons' )
		);

		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_order_totals_after_tax',
			array( $this, 'add_totals' )
		);

		$this->get_wp_wrapper()->add_filter(
			'woocommerce_admin_order_should_render_refunds',
			array(
				$this,
				'should_render_refunds',
			),
			20,
			3
		);
	}

	/**
	 * @param $order
	 *
	 * @return void
	 * @throws Exception
	 */
	public function payever_order_item_headers( $order ) {
		$order_id = $this->get_order_id( $order );
		if ( $order_id && WC_Payever_Helper::instance()->validate_order_payment_method( $order ) ) {
			?>
			<th class="payever-item-head-cancel">
				<?php _e( 'Cancelled', 'payever-woocommerce-gateway' ); ?>
			</th>
			<th class="payever-item-head-cancel-qty">
				<?php _e( 'Qty to Cancel', 'payever-woocommerce-gateway' ); ?>
			</th>
			<th class="payever-item-head-capture">
				<?php _e( 'Captured', 'payever-woocommerce-gateway' ); ?>
			</th>
			<th class="payever-item-head-capture-qty">
				<?php _e( 'Qty to Capture', 'payever-woocommerce-gateway' ); ?>
			</th>
			<?php
		}
	}

	/**
	 * @param $item
	 * @return array|mixed
	 */
	public function get_order_item_as_array( $item ) {
		if ( is_object( $item ) && version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return array(
				'order_id' => $item->get_order_id(),
				'quantity' => $item->get_quantity(),
			);
		}

		return $item;
	}

	/**
	 * @param $product
	 * @param $item
	 * @param $item_id
	 *
	 * @return void
	 * @throws Exception
	 */
	public function payever_order_item_values( $product, $item, $item_id ) {
		$is_shipping   = is_a( $item, 'WC_Order_Item_Shipping' );
		$is_paymentfee = is_a( $item, 'WC_Order_Item_Fee' );

		if ( $product || $is_shipping || $is_paymentfee ) {
			$item_array    = $this->get_order_item_as_array( $item );
			$order_id      = $item_array['order_id'];
			$item_quantity = $item_array['quantity'];

			$order = $this->get_order_wrapper()->get_wc_order( $order_id );
			if ( ! WC_Payever_Helper::instance()->validate_order_payment_method( $order ) ) {
				return;
			}

			// Get order item
			try {
				$order_item = $this->get_order_total_model()->get_order_items( $order_id, $item_id );
			} catch ( Exception $exception ) {
				WC_Payever_Api::get_instance()->get_logger()->error( $exception->getMessage() );
				return;
			}

			$item_cost = $this->get_order_item_cost( $order, $item, $is_shipping, $is_paymentfee );
			$disabled = ! WC_Payever_Helper::instance()->is_allow_order_capture_by_qty( $order_id );
			$this->generate_payever_order_item_html( $order_item, $item_quantity, $item_cost, $item_id, $disabled, $is_paymentfee );
		}
	}

	/**
	 * @param $order_item
	 * @param $item_quantity
	 * @param $item_cost
	 * @param $item_id
	 * @param $disabled
	 * @param $is_paymentfee
	 * @return void
	 */
	private function generate_payever_order_item_html( $order_item, $item_quantity, $item_cost, $item_id, $disabled, $is_paymentfee ) {
		?>
		<!-- Cancel -->
		<td class="payever-partial-item-icon payever-cancel-action" width="1%">
			<?php esc_html_e( $order_item['cancelled_qty'] . ' (' . $item_quantity . ')' ); ?>
		</td>

		<td class="payever-partial-item-qty payever-cancel-action" width="1%">
			<input class="qty-input" type="number" step="1" data-item-id="<?php esc_attr_e( $item_id ); ?>"
				data-item-cost="<?php esc_attr_e( $item_cost ); ?>"
				name="wc-payever-cancel[<?php esc_attr_e( $item_id ); ?>]"
				autocomplete="off"
				min="0"
				<?php if ( $disabled || $is_paymentfee ) : ?>
					disabled="disabled"
				<?php endif; ?>
				value="0"
				max="<?php esc_attr_e( absint( $item_quantity ) ); ?>"
				style="display: none; width: 50px; margin-top: -9px"
			>
		</td>

		<!-- Capture -->
		<td class="payever-partial-item-icon payever-capture-action" width="1%">
			<?php esc_html_e( $order_item['captured_qty'] . ' (' . $item_quantity . ')' ); ?>
		</td>

		<td class="payever-partial-item-qty payever-capture-action" width="1%">
			<input class="qty-input" type="number" step="1" data-item-id="<?php esc_attr_e( $item_id ); ?>"
				data-item-cost="<?php esc_attr_e( $item_cost ); ?>"
				name="wc-payever-capture[<?php esc_attr_e( $item_id ); ?>]"
				autocomplete="off"
				<?php if ( $disabled || $is_paymentfee ) : ?>
					disabled="disabled"
				<?php endif; ?>
				min="0"
				value="<?php esc_attr_e( (int) $is_paymentfee && ! $order_item['captured_qty'] ); ?>"
				max="<?php esc_attr_e( absint( $order_item['captured_qty'] - $item_quantity ) ); ?>"
				style="display: none; width: 50px; margin-top: -9px"
			>
		</td>
		<?php
	}

	private function get_order_item_cost( $order, $item, $is_shipping, $is_paymentfee ) {
		return $is_shipping || $is_paymentfee
			? $order->get_item_total( $item, true, true )
			: $order->get_item_subtotal( $item, true, true );
	}

	/**
	 * @param $order
	 *
	 * @return void
	 * @throws Exception
	 */
	public function add_buttons( $order ) {
		$order_id = $this->get_order_id( $order );
		if ( ! $order_id || ! WC_Payever_Helper::instance()->validate_order_payment_method( $order ) ) {
			return;
		}

		$order_total = is_object( $order ) ? $order->get_remaining_refund_amount() : $order['remaining_refund_amount'];

		$totals = $this->get_order_total_model()->get_totals( $order_id );
		?>
		<?php if ( $this->is_shipping_action_allowed( $order_id ) ) : ?>
			<button type="button" class="button wc-payever-capture-button" title="<?php _e( 'Capture', 'payever-woocommerce-gateway' ); ?>">
				<?php _e( 'Capture', 'payever-woocommerce-gateway' ); ?>
			</button>
			<?php
			wc_get_template(
				'admin/html-order-capture.php',
				array(
					'order'           => $order,
					'order_id'        => $order_id,
					'total_captured'  => $totals['captured'],
					'remaining_total' => $order_total - $totals['captured'],
					'providers_list'  => $this->get_shipping_providers(),
				),
				'',
				__DIR__ . '/../../templates/'
			);
			?>
		<?php endif; ?>
		<?php if ( $this->is_cancel_action_allowed( $order_id ) ) : ?>
			<button type="button" class="button wc-payever-cancel-button" title="<?php _e( 'Cancel', 'payever-woocommerce-gateway' ); ?>">
				<?php _e( 'Cancel', 'payever-woocommerce-gateway' ); ?>
			</button>
			<?php
			wc_get_template(
				'admin/html-order-cancel.php',
				array(
					'order'           => $order,
					'order_id'        => $order_id,
					'total_cancelled' => $totals['cancelled'],
				),
				'',
				__DIR__ . '/../../templates/'
			);
			?>
		<?php endif; ?>
		<?php
	}

	/**
	 * @param $order_id
	 *
	 * @return void
	 * @throws Exception
	 */
	public function add_totals( $order_id ) {
		$order = $this->get_order_wrapper()->get_wc_order( $order_id );
		if ( ! WC_Payever_Helper::instance()->validate_order_payment_method( $order ) ) {
			return;
		}

		// Get totals
		$totals = $this->get_order_total_model()->get_totals( $order_id );
		if ( $totals['captured'] > 0.0 ) {
			?>
			<tr>
				<td class="label"><?php esc_html_e( 'Total Captured:', 'payever-woocommerce-gateway' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wc_price( $totals['captured'], array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php
		}

		if ( $totals['cancelled'] > 0.0 ) {
			?>
			<tr>
				<td class="label"><?php esc_html_e( 'Total Cancelled:', 'payever-woocommerce-gateway' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<?php echo wc_price( $totals['cancelled'], array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Should render refunds.
	 * Uses `woocommerce_admin_order_should_render_refunds` filter.
	 *
	 * @param bool $render_refunds
	 * @param mixed $order_id
	 * @param WC_Order $order
	 *
	 * @return false|mixed
	 * @throws Exception
	 */
	public function should_render_refunds( $render_refunds, $order_id, $order ) {
		if ( ! $render_refunds ) {
			return false;
		}

		$payment_method = WC_Payever_Helper::instance()->get_payment_method( $order );
		if ( ! WC_Payever_Helper::instance()->is_payever_method( $payment_method ) ) {
			return $render_refunds;
		}

		$payment_id = $this->get_order_wrapper()->get_payment_id( $order_id );
		if ( empty( $payment_id ) ) {
			return false;
		}

		$api            = $this->get_api_wrapper()->get_payments_api_client();
		$action_decider = $this->get_action_decider_wrapper()->get_action_decider( $api );

		try {
			if ( ! $action_decider->isRefundAllowed( $payment_id, false ) &&
				 ! $action_decider->isPartialRefundAllowed( $payment_id, false ) //phpcs:ignore
			) {
				return false;
			}
		} catch ( \Exception $exception ) {
			return false;
		}

		return $render_refunds;
	}

	/**
	 * Get Shipping providers.
	 *
	 * @return array
	 */
	private function get_shipping_providers() {
		$providers = array();
		if ( class_exists( 'WC_Shipment_Tracking_Actions' ) ) {
			$providers = WC_Shipment_Tracking_Actions::get_instance()->get_providers();
		}

		return $providers;
	}

	/**
	 * @param $order_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function is_shipping_action_allowed( $order_id ) {
		if ( ! isset( $this->is_shipping_allowed_for_order_id[ $order_id ] ) ) {
			$api            = new WC_Payever_API_Wrapper();
			$api            = $api->get_payments_api_client();
			$action_decider = $this->get_action_decider_wrapper()->get_action_decider( $api );
			$payment_id     = $this->get_order_wrapper()->get_payment_id( $order_id );
			if ( empty( $payment_id ) ) {
				$this->is_shipping_allowed_for_order_id[ $order_id ] = false;

				return $this->is_shipping_allowed_for_order_id[ $order_id ];
			}

			try {
				$this->is_shipping_allowed_for_order_id[ $order_id ] =
					$action_decider->isPartialShippingAllowed( $payment_id, false );
			} catch ( \Exception $e ) {
				$this->is_shipping_allowed_for_order_id[ $order_id ] = false;
			}
		}

		return $this->is_shipping_allowed_for_order_id[ $order_id ];
	}

	/**
	 * @param $order_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function is_cancel_action_allowed( $order_id ) {
		if ( ! isset( $this->is_cancel_allowed_for_order_id[ $order_id ] ) ) {
			$api            = new WC_Payever_API_Wrapper();
			$api            = $api->get_payments_api_client();
			$action_decider = $this->get_action_decider_wrapper()->get_action_decider( $api );
			$payment_id     = $this->get_order_wrapper()->get_payment_id( $order_id );
			if ( empty( $payment_id ) ) {
				$this->is_cancel_allowed_for_order_id[ $order_id ] = false;

				return $this->is_cancel_allowed_for_order_id[ $order_id ];
			}

			try {
				$this->is_cancel_allowed_for_order_id[ $order_id ] =
					$action_decider->isPartialCancelAllowed( $payment_id, false );
			} catch ( \Exception $e ) {
				$this->is_cancel_allowed_for_order_id[ $order_id ] = false;
			}
		}

		return $this->is_cancel_allowed_for_order_id[ $order_id ];
	}

	/**
	 * @param $order
	 *
	 * @return string
	 */
	private function get_order_id( $order ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $order->get_id();
		}

		return $order->id;
	}

	/**
	 * @return WC_Payever_Order_Wrapper
	 * @codeCoverageIgnore
	 */
	private function get_order_wrapper() {
		return null === $this->order_wrapper
			? $this->order_wrapper = new WC_Payever_Order_Wrapper()
			: $this->order_wrapper;
	}

	/**
	 * @return WC_Payever_Order_Total
	 * @codeCoverageIgnore
	 */
	private function get_order_total_model() {
		return null === $this->order_total_model
			? $this->order_total_model = new WC_Payever_Order_Total()
			: $this->order_total_model;
	}
}
