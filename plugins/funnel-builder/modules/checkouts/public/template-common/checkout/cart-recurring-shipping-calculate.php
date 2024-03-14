<?php
/**
 * Recurring Shipping Methods Display
 *
 * Based on the WooCommerce core template: /woocommerce/templates/cart/cart-shipping.php
 *
 * @author  Prospress
 * @package WooCommerce Subscriptions/Templates
 * @version 2.0.12
 */
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}

?>
<tr class="shipping recurring-total <?php echo esc_attr( $recurring_cart_key ); ?>">
    <td data-title="<?php echo esc_attr( $package_name ); ?>" colspan="2">
		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>
		<?php if ( WC_Subscriptions::is_woocommerce_pre( '2.6' ) && is_cart() ) : // WC < 2.6 did not allow string indexes for shipping methods on the cart page and there was no way to hook in ?>
			<?php echo wp_kses_post( wpautop( __( 'Recurring shipping options can be selected on checkout.', 'funnel-builder' ) ) ); ?>
		<?php elseif ( 1 < count( $available_methods ) ) : ?>
            <ul id="shipping_method_<?php echo esc_attr( $recurring_cart_key ); ?>">
				<?php

				foreach ( $available_methods as $method ) : ?>
                    <li class="wfacp_single_shipping_method">

                        <div class="wfacp_single_shipping">
                            <div class="wfacp_shipping_radio">
								<?php
								wcs_cart_print_shipping_input( $index, $method, $chosen_method, 'radio' );
								echo sprintf( '<label for="shipping_method_%s_%s">%s</label>', $index, esc_attr( sanitize_title( $method->id ) ), WFACP_Common::shipping_method_label($method ) );
								?>
                            </div>
                            <div class="wfacp_shipping_price">
								<?php
								echo wp_kses_post( wcs_cart_totals_shipping_method_price_label( $method, $recurring_cart ) );
								?>
                            </div>
                        </div>
                        <div class="wfacp_single_shipping">
							<?php
							do_action( 'woocommerce_after_shipping_rate', $method, $index );
							?>
                        </div>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php elseif ( ! WC()->customer->has_calculated_shipping() ) : ?>
            <div class="wfacp_subscription_count_wrap">
				<?php echo wp_kses_post( wpautop( __( 'Shipping costs will be calculated once you have provided your address. ', 'funnel-builder' ) ) ); ?>
            </div>
		<?php else : ?>
			<?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', wpautop( __( 'There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'funnel-builder' ) ) ) ); ?>
		<?php endif; ?>


    </td>
</tr>
