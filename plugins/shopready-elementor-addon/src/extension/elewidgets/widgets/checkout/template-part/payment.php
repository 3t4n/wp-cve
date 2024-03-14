<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="payment" class="woocommerce-checkout-payment woo-ready-payment-module-wrapper">
	<?php if ( WC()->cart->needs_payment() ) : ?>
	<ul class="wc_payment_methods payment_methods methods">
		<?php
		if ( ! empty( $available_gateways ) ) {
			foreach ( $available_gateways as $gateway ) {
				shop_ready_widget_template_part( 'checkout/template-part/payment-method.php', array( 'gateway' => $gateway ) );
			}
		} else {
			echo wp_kses_post('<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'shopready-elementor-addon' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'shopready-elementor-addon' ) ) . '</li>');
		}
		?>
	</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'shopready-elementor-addon' ), '<em>', '</em>' );
			?>
			<br /><button type="submit" class="button alt" name="woocommerce_checkout_update_totals"
				value="<?php esc_attr_e( 'Update totals', 'shopready-elementor-addon' ); ?>"><?php esc_html_e( 'Update totals', 'shopready-elementor-addon' ); ?></button>
		</noscript>

		<?php if ( isset( $show_terms ) ) : ?>
			<?php if ( $show_terms == 'yes' ) : ?>
				<?php wc_get_template( 'checkout/terms.php' ); ?>
		<?php endif; ?>
		<?php else : ?>
			<?php wc_get_template( 'checkout/terms.php' ); ?>
		<?php endif ?>

		<?php if ( shop_ready_gl_get_setting( 'wr_checkout_order_btn_sep_row' ) != 'yes' ) : ?>
			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
			<?php echo wp_kses_post( apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt woo-ready-chk-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ) ); ?>
			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
		<?php endif; ?>

	</div>

	<?php if ( shop_ready_gl_get_setting( 'wr_checkout_order_btn_sep_row' ) == 'yes' ) : ?>
	<div class="form-row">
		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
		<?php echo wp_kses_post( apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt woo-ready-chk-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ) ); ?>
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
	</div>
	<?php endif; ?>
	<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
</div>
