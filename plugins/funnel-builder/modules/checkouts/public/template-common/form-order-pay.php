<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
wp_dequeue_script( 'wc-single-product' );
/**
 * @var $instance WFACP_Template_Common
 */
$checkout       = WC()->checkout();
$instance       = wfacp_template();
$totalStepCount = $instance->get_step_count();

$stepClassName  = 'wfacp_single_step_form';
$stepMultiClass = 'wfacp_single_step_form';
if ( $totalStepCount > 1 && $totalStepCount == 2 ) {
	$stepMultiClass = "wfacp_two_step";
} else {
	$stepMultiClass = "wfacp_three_step";
}
if ( $totalStepCount > 1 ) {
	$stepClassName = 'wfacp_single_multi_form';
}
do_action( 'wfacp_before_form' );

$is_global_checkout_f = WFACP_Core()->public->is_checkout_override();
$global_cls           = "";


$form_class = [];
if ( ! empty( $stepClassName ) ) {
	$form_class[] = $stepClassName;
}
if ( ! empty( $stepMultiClass ) ) {
	$form_class[] = $stepMultiClass;
}
if ( $is_global_checkout_f === true ) {
	$form_class[] = "wfacp_global_checkout_wrap";
}
$pay = WFACP_Core()->pay;
remove_filter( 'woocommerce_get_checkout_url', [ WFACP_Core()->public, 'woocommerce_get_checkout_url' ], 99999 );
?>
<div class="wfacp_main_form woocommerce <?php echo implode( ' ', $form_class ); ?>">
	<?php
	do_action( 'wfacp_outside_header' );
	$checkout               = WC()->checkout();
	$current_step           = $instance->get_current_step();
	$selected_template_slug = $instance->get_template_slug();
	$template_type          = $instance->get_template_type();
	include __DIR__ . '/form_internal_css.php';


	do_action( 'before_woocommerce_pay' );
	global $wp;
	$order_id = $pay->get_order_id();

	if ( isset( $_GET['pay_for_order'], $_GET['key'] ) && $order_id ) {
		try {
			$order_key          = $pay->get_order_key(); // WPCS: input var ok, CSRF ok.
			$order              = wc_get_order( $order_id );
			$hold_stock_minutes = (int) get_option( 'woocommerce_hold_stock_minutes', 0 );
			$print_form         = true;
			// Order or payment link is invalid.
			if ( ! $order || $order->get_id() !== $order_id || ! hash_equals( $order->get_order_key(), $order_key ) ) {
				throw new Exception( __( 'Sorry, this order is invalid and cannot be paid for.', 'woocommerce' ) );
			}

			// Logged out customer does not have permission to pay for this order.
			if ( ! current_user_can( 'pay_for_order', $order_id ) && ! is_user_logged_in() ) {
				echo '<div class="woocommerce-info">' . esc_html__( 'Please log in to your account below to continue to the payment form.', 'woocommerce' ) . '</div>';
				//	woocommerce_login_form( array( 'redirect' => $order->get_checkout_payment_url() ) );
				include WFACP_TEMPLATE_COMMON . '/checkout/form-login.php';
				$print_form = false;
			}

			if ( $print_form ) {
				// Add notice if logged in customer is trying to pay for guest order.
				if ( ! $order->get_user_id() && is_user_logged_in() ) {
					// If order has does not have same billing email then current logged in user then show warning.
					if ( $order->get_billing_email() !== wp_get_current_user()->user_email ) {
						wc_print_notice( __( 'You are paying for a guest order. Please continue with payment only if you recognize this order.', 'woocommerce' ), 'error' );
					}
				}

				// Logged in customer trying to pay for someone else's order.
				if ( ! current_user_can( 'pay_for_order', $order_id ) ) {
					throw new Exception( __( 'This order cannot be paid for. Please contact us if you need assistance.', 'woocommerce' ) );
				}

				// Does not need payment.
				if ( ! $order->needs_payment() ) {
					/* translators: %s: order status */
					throw new Exception( sprintf( __( 'This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.', 'woocommerce' ), wc_get_order_status_name( $order->get_status() ) ) );
				}

				// Ensure order items are still stocked if paying for a failed order. Pending orders do not need this check because stock is held.
				if ( ! $order->has_status( wc_get_is_pending_statuses() ) ) {
					$quantities = array();

					foreach ( $order->get_items() as $item_key => $item ) {
						if ( $item && is_callable( array( $item, 'get_product' ) ) ) {
							$product = $item->get_product();

							if ( ! $product ) {
								continue;
							}

							$quantities[ $product->get_stock_managed_by_id() ] = isset( $quantities[ $product->get_stock_managed_by_id() ] ) ? $quantities[ $product->get_stock_managed_by_id() ] + $item->get_quantity() : $item->get_quantity();
						}
					}

					foreach ( $order->get_items() as $item_key => $item ) {
						if ( $item && is_callable( array( $item, 'get_product' ) ) ) {
							$product = $item->get_product();

							if ( ! $product ) {
								continue;
							}

							if ( ! apply_filters( 'woocommerce_pay_order_product_in_stock', $product->is_in_stock(), $product, $order ) ) {
								/* translators: %s: product name */
								throw new Exception( sprintf( __( 'Sorry, "%s" is no longer in stock so this order cannot be paid for. We apologize for any inconvenience caused.', 'woocommerce' ), $product->get_name() ) );
							}

							// We only need to check products managing stock, with a limited stock qty.
							if ( ! $product->managing_stock() || $product->backorders_allowed() ) {
								continue;
							}

							// Check stock based on all items in the cart and consider any held stock within pending orders.
							$held_stock     = ( $hold_stock_minutes > 0 ) ? wc_get_held_stock_quantity( $product, $order->get_id() ) : 0;
							$required_stock = $quantities[ $product->get_stock_managed_by_id() ];

							if ( ! apply_filters( 'woocommerce_pay_order_product_has_enough_stock', ( $product->get_stock_quantity() >= ( $held_stock + $required_stock ) ), $product, $order ) ) {
								/* translators: 1: product name 2: quantity in stock */
								throw new Exception( sprintf( __( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'woocommerce' ), $product->get_name(), wc_format_stock_quantity_for_display( $product->get_stock_quantity() - $held_stock, $product ) ) );
							}
						}
					}
				}

				WC()->customer->set_props( array(
					'billing_country'  => $order->get_billing_country() ? $order->get_billing_country() : null,
					'billing_state'    => $order->get_billing_state() ? $order->get_billing_state() : null,
					'billing_postcode' => $order->get_billing_postcode() ? $order->get_billing_postcode() : null,
				) );
				WC()->customer->save();

				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

				if ( count( $available_gateways ) ) {
					current( $available_gateways )->set_current();
				}
				$order_button_text = apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) );


				$selected_template_slug      = $instance->get_template_slug();
				$payment_des                 = $instance->get_payment_desc();
				$border_cls                  = $instance->get_heading_title_class();
				$payment_methods_heading     = $instance->payment_heading();
				$payment_methods_sub_heading = $instance->payment_sub_heading();
				$current_step                = $instance->get_current_step();
				$current_open                = $instance->get_current_open_step();
				$hide_payment_cls            = '';

				$temp_open_checking = false;
				if ( 'single_step' !== $current_open ) {
					$temp_open_checking = true;
				}

				?>
                <form id="order_review" method="post">
                    <div class="checkout woocommerce-checkout">
                        <div class="wfacp-left-panel wfacp_page">
                            <div class="wfacp-section wfacp-hg-by-box" data-field-count="1">
                                <div class="wfacp_internal_form_wrap wfacp-comm-title <?php echo $instance->get_heading_title_class() ?>">
                                    <h2 class="wfacp_section_heading wfacp_section_title <?php echo $instance->get_heading_class(); ?>"><?php echo $instance->get_order_pay_summary_heading(); ?></h2>
                                </div>
                                <div class="wfacp-comm-form-detail clearfix">
                                    <div class="wfacp-row">
										<?php
										$instance->get_order_pay_summary( $order );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wfacp-section wfacp_payment form_section_your_order_0_<?php echo $selected_template_slug; ?> wfacp-section-titlex wfacp-hg-by-box">
                            <div style="clear: both;"></div>
                            <div class="wfacp-comm-title <?php echo $border_cls; ?>">
                                <h2 class="wfacp_section_heading wfacp_section_title <?php echo $instance->get_heading_class() ?> "><?php echo $payment_methods_heading; ?></h2>
                                <h4 class="<?php echo $instance->get_sub_heading_class(); ?>"><?php echo $payment_methods_sub_heading; ?></h4>
                            </div>
                            <div class="woocommerce-checkout-review-order wfacp-oder-detail clearfix">
                                <div id="payment">
									<?php if ( $order->needs_payment() ) : ?>
                                        <ul class="wc_payment_methods payment_methods methods">
											<?php
											if ( ! empty( $available_gateways ) ) {
												foreach ( $available_gateways as $gateway ) {
													wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
												}
											} else {
												echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
											}
											?>
                                        </ul>
									<?php endif; ?>
                                    <div class="form-row">
                                        <input type="hidden" name="woocommerce_pay" value="1"/>
										<input type="hidden" name="_wfacp_post_id" value="<?php echo WFACP_Common::get_id(); ?>"/>
										<?php wc_get_template( 'checkout/terms.php' ); ?>
										<?php do_action( 'woocommerce_pay_order_before_submit' ); ?>
										<?php echo apply_filters( 'woocommerce_pay_order_button_html', '<button type="submit" class="button alt" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine
										?>
										<?php do_action( 'woocommerce_pay_order_after_submit' ); ?>
										<?php wp_nonce_field( 'woocommerce-pay', 'woocommerce-pay-nonce' ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
				<?php
			}
		} catch ( Exception $e ) {
			wc_print_notice( $e->getMessage(), 'error' );
		}
	} elseif ( $order_id ) {

		// Pay for order after checkout step.
		$order_key = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$order     = wc_get_order( $order_id );

		if ( $order && $order->get_id() === $order_id && hash_equals( $order->get_order_key(), $order_key ) ) {

			if ( $order->needs_payment() ) {

				wc_get_template( 'checkout/order-receipt.php', array( 'order' => $order ) );

			} else {
				/* translators: %s: order status */
				wc_print_notice( sprintf( __( 'This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.', 'woocommerce' ), wc_get_order_status_name( $order->get_status() ) ), 'error' );
			}
		} else {
			wc_print_notice( __( 'Sorry, this order is invalid and cannot be paid for.', 'woocommerce' ), 'error' );
		}
	} else {
		wc_print_notice( __( 'Invalid order.', 'woocommerce' ), 'error' );
	}
	do_action( 'after_woocommerce_pay' );
	?>
</div>

<?php
do_action( 'wfacp_after_form' );
?>
