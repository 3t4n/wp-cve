<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}

$instance = wfacp_template();
if ( is_null( $instance ) ) {
	return;
}
$data = $instance->get_checkout_fields();

if ( ! isset( $data['advanced']['shipping_calculator'] ) ) {
	return;
}
$field = $data['advanced']['shipping_calculator'];

$placeholder = WFACP_Common::default_shipping_placeholder_text();
if ( isset( $field['default'] ) && '' !== $field['default'] ) {
	$placeholder = $field['default'];
}
$args    = WC()->session->get( 'shipping_calculator_' . WFACP_Common::get_id(), $field );
$classes = isset( $args['class'] ) ? implode( ' ', $args['class'] ) : '';

if ( WFACP_Common::is_theme_builder() ) {
	?>
    <div class="wfacp_anim wfacp_shipping_options <?php echo $classes; ?>" id="shipping_calculator_field" <?php echo WFACP_Common::get_fragments_attr() ?> >
        <ul id="shipping_method" class="wfacp_no_add_here">
            <li>
                <p><?php echo apply_filters( 'wfacp_default_shipping_message', $placeholder ); ?></p>
            </li>
        </ul>
    </div>
	<?php

	return;
}
$shipping_hidden_fields = apply_filters( 'wfacp_print_shipping_hidden_fields', true );
if ( $shipping_hidden_fields && ! wp_doing_ajax() ) {
	$shippingMethods = WC()->session->chosen_shipping_methods;
	if ( is_array( $shippingMethods ) && count( $shippingMethods ) > 0 && ! wp_doing_ajax() ) {
		foreach ( $shippingMethods as $key => $value ) {
			if ( $key === 'undefined' ) {
				continue;
			}
			echo '<input type="hidden" class="wfacp_hidden_shipping" data-index="' . $key . '" name="shipping_method[' . $key . ']"  value="' . $value . '" >';
		}
	}
}
?>
<div class="wfacp_anim wfacp_shipping_options <?php echo $classes; ?>" id="shipping_calculator_field" <?php echo WFACP_Common::get_fragments_attr() ?> >
	<?php
	$number_parents_fields = WC()->session->get( 'wfacp_shipping_method_parent_fields_count_' . WFACP_Common::get_id(), false );
	$is_cart_is_virtual    = WFACP_Common::is_cart_is_virtual();

	if ( wp_doing_ajax() || apply_filters( 'wfacp_show_shipping_options', true ) ) {
		if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() && false == $is_cart_is_virtual ) {
			unset( $data );
			$item_names_containing_subscriptions = [];
			$item_names                          = [];
			$label                               = isset( $field['label'] ) ? $field['label'] : __( 'Shipping', 'funnel-builder' );
			$cart_contents                       = WC()->cart->get_cart_contents();
			$cart_content_count                  = count( $cart_contents );
			/**
			 * If we have any subscription product in the cart that needs shipping
			 * Then get item names that demands recurring shipping & other items in different lists
			 * we will then use these names inside the label
			 */
			$temp_f1 = array(
				'WC_Subscriptions_Cart',
				'cart_contains_subscriptions_needing_shipping',
			);
			if ( is_callable( $temp_f1 ) && true === WC_Subscriptions_Cart::cart_contains_subscriptions_needing_shipping() ) {

				$cart = WC()->cart;

				foreach ( $cart->cart_contents as $cart_item_key => $values ) {
					$_product = $values['data'];
					if ( WC_Subscriptions_Product::is_subscription( $_product ) && $_product->needs_shipping() && false === WC_Subscriptions_Product::needs_one_time_shipping( $_product ) ) {

						$item_names_containing_subscriptions[] = $_product->get_name();
					} elseif ( $_product->needs_shipping() ) {
						$item_names[] = $_product->get_name();
					}
				}

				/**
				 * If have non-subscription products then add it to the label
				 */
				if ( count( $item_names ) > 0 ) {
					$label = $label . ' (' . implode( ',', $item_names ) . ')';

				} else {

					//empty the label as only subscription product is in the cart
					$label = '';
				}
			}

			$shipping_html = '';
			ob_start();
			wc_cart_totals_shipping_html();
			$shipping_html = ob_get_clean();
			if ( ! empty( $shipping_html ) ) {
				$shippingTitle  = esc_attr__( 'Shipping Method', 'funnel-builder' );
				$pageID         = WFACP_Common::get_id();
				$_wfacp_version = WFACP_Common::get_post_meta_data( $pageID, '_wfacp_version' );
				if ( $_wfacp_version == WFACP_VERSION ) {
					$shippingTitle = __( 'Select Shipping Method', 'funnel-builder' );
				}
				$shippingTitle = isset( $field['label'] ) ? $field['label'] : $shippingTitle;
				?>
                <div class="border">
                    <label class="wfacp_main_form label label_shiping"><?php echo $shippingTitle; ?></label>
                    <table class="wfacp_shipping_table ">
						<?php
						do_action( 'wfacp_woocommerce_review_order_before_shipping' );
						do_action( 'woocommerce_review_order_before_shipping' );
						?>
						<?php echo $shipping_html; ?>
						<?php do_action( 'woocommerce_review_order_after_shipping' );
						do_action( 'wfacp_woocommerce_review_order_after_shipping' ); ?>
                    </table>
                </div>
				<?php
			}
			/**
			 * Show the second shipping block for the recurring shipping
			 */
			if ( is_callable( array(
					'WC_Subscriptions_Cart',
					'cart_contains_subscriptions_needing_shipping',
				) ) && true === WC_Subscriptions_Cart::cart_contains_subscriptions_needing_shipping() && true === apply_filters( 'wfacp_show_recurring_methods', true, $cart_content_count, $shipping_html ) ) {
				global $have_multiple_subscription;
				$have_multiple_subscription = false;
				/**
				 * This hook insures that during the html generation subscription plugin called the specific template
				 * that means there are more than one shipping rates available for recurring cart.
				 * @see wcs_cart_totals_shipping_html()
				 */
				add_action( 'woocommerce_before_template_part', function ( $template_name, $template_path, $located, $args ) {
					global $have_multiple_subscription;
					if ( $template_name !== 'cart/cart-recurring-shipping.php' ) {
						return;
					}
					$have_multiple_subscription = true;
				}, 10, 4 );
				/**
				 * setting recurring total calculation type so that subscription plugin calculates the respective recurring cart shipping
				 */
				WC_Subscriptions_Cart::set_calculation_type( 'recurring_total' );
				ob_start();
				WFACP_Common::wcs_cart_totals_shipping_calculator_html();
				$shipping_recurring_html = ob_get_clean();
				WC_Subscriptions_Cart::set_calculation_type( 'none' );


				$multiple_class = '';
				if ( $have_multiple_subscription ) {
					$multiple_class = 'wfacp_multi_rec';
				}

				$label = isset( $field['label'] ) ? $field['label'] : __( 'Shipping Recurring', 'funnel-builder' );
				$label = $label . ' (' . implode( ',', $item_names_containing_subscriptions ) . ')';

				$recurring_label = __( 'Recurring Shipping Method', 'funnel-builder' );
				?>
                <div class="border">
                    <label class="wfacp_main_form label label_shiping wfacp_recurring_shipping_label">
						<?php echo apply_filters( 'wfacp_recurring_shipping_label', $recurring_label ); ?></label>
                    <table class="wfacp_shipping_table wfacp_shipping_recurring <?php echo $multiple_class; ?>">
						<?php

						do_action( 'wfacp_woocommerce_review_order_before_shipping' );
						do_action( 'woocommerce_review_order_before_shipping' );
						echo $shipping_recurring_html;
						?>
						<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
						<?php do_action( 'wfacp_woocommerce_review_order_after_shipping' ); ?>
                    </table>
                </div>
				<?php
			}

		} else {

			if ( apply_filters( 'wfacp_no_available_shipping_method_found', true, $instance, $field ) ) {
				?>
                <style>
                    .wfacp_shipping_options {
                        display: none
                    }
                </style>
				<?php
				if ( ( is_array( $number_parents_fields ) && 1 == $number_parents_fields['count'] ) ) {
					$parent_step_number = $number_parents_fields['index'];
					$step               = $number_parents_fields['step'];
					?>
                    <style>
                        <?php printf(".wfacp_page.%s .wfacp-section.step_%s{ display: none;}",$step,$parent_step_number);?>
                    </style>
					<?php
				}
			}
			do_action( 'wfacp_no_shipping_method_founds', $instance, $field );
		}
	} else {
		if ( true != $is_cart_is_virtual ) {
			?>

            <ul id="shipping_method" class="wfacp_no_add_here">
                <li class="wfacp_no_shipping wfacp_clearfix">
                    <label><?php _e( 'Shipping method', 'funnel-builder' ); ?></label>
                </li>
            </ul>
			<?php
		}
	}
	if ( ( is_array( $number_parents_fields ) && 1 == $number_parents_fields['count'] ) && ( ( true == $is_cart_is_virtual || false == WC()->cart->show_shipping() ) ) ) {
		$parent_step_number = $number_parents_fields['index'];
		$step               = $number_parents_fields['step'];
		?>
        <style>
            <?php printf(".wfacp_page.%s .wfacp-section.step_%s{ display: none;}",$step,$parent_step_number);?>
        </style>
		<?php
	}
	?>
</div>