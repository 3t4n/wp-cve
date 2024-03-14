<?php
defined('ABSPATH') || exit;
?>
		<div class="woocommerce-checkout-review-order-table">
		<div class="cart-resume">
			<div class="detail">
				<div class="description"><?php echo __( 'Subtotal', 'checkout-mestres-wp' ); ?></div>
				<div class="value"><?php wc_cart_totals_subtotal_html(); ?></div>
			</div>
			<?php 
			if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ){
				$current_price = WC()->cart->get_shipping_total();
			?>
			<div class="detail">
				<div class="description"><?php echo __( 'Shipping', 'checkout-mestres-wp' ); ?></div>
				<div class="value">R$<?php echo number_format( $current_price, 2, ',', '.' ); ?></div>
			</div>
			<?php } ?>
			<?php foreach (WC()
    ->cart
    ->get_coupons() as $code => $coupon): ?>
			<div class="detail">
				<div class="description"><?php echo __( 'Discount', 'checkout-mestres-wp' ); ?></div>
				<div class="value"><?php wc_cart_totals_coupon_html($coupon); ?></div>
			</div>
			<?php
endforeach; ?>
			<?php if (wc_tax_enabled() && !WC()
    ->cart
    ->display_prices_including_tax()): ?>
			<?php if ('itemized' === get_option('woocommerce_tax_total_display')): ?>
			<?php foreach (WC()
            ->cart
            ->get_tax_totals() as $code => $tax): ?>
			<div class="detail">
				<div class="description"><?php echo esc_html($tax->label); ?></div>
				<div class="value"><?php echo wp_kses_post($tax->formatted_amount); ?></div>
			</div>
			<?php
        endforeach; ?>
			<?php
    else: ?>
			<div class="detail">
				<div class="description"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></div>
				<div class="value"><?php wc_cart_totals_taxes_total_html(); ?></div>
			</div>
			<?php endif; ?>
			<?php endif; ?>
		<?php
		foreach ( WC()->cart->get_fees() as $fee ) :
		if($fee->total!="0.00"){
		?>
			<div class="detail">
				<div class="description"><?php echo esc_html( $fee->name ); ?></div>
				<div class="value"><?php wc_cart_totals_fee_html( $fee ); ?></div>
			</div>
		<?php } endforeach; ?>
			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
			<div class="detail total">
				<div class="description"><?php echo __( 'Total', 'checkout-mestres-wp' ); ?></div>
				<div class="value"><?php wc_cart_totals_order_total_html(); ?></div>
			</div>
		</div>
		<?php
foreach (WC()
    ->cart
    ->get_cart() as $cart_item_key => $cart_item)
{
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key))
    {
?>
						<div class="cart-item">
						<div class="product-thumbnail">
						<?php if(get_option('cwmp_floatcart_text_link')=="1"){ ?><a href="<?php echo $_product->get_permalink(); ?>"><?php } ?>
						<?php echo $_product->get_image(); ?>
						<?php if(get_option('cwmp_floatcart_text_link')=="1"){ ?></a><?php } ?>
						</div>
						<div class="product-name">
							<div class="item-cart-title">
							<div>
								<a href="<?php echo $_product->get_permalink(); ?>">
									<h3><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name() , $cart_item, $cart_item_key)) . '&nbsp;'; ?></h3>
								</a>
							</div>
							<div>
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" style="display:block;">
											<svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M4.5 5.5C4.63261 5.5 4.75979 5.55268 4.85355 5.64645C4.94732 5.74021 5 5.86739 5 6V12C5 12.1326 4.94732 12.2598 4.85355 12.3536C4.75979 12.4473 4.63261 12.5 4.5 12.5C4.36739 12.5 4.24021 12.4473 4.14645 12.3536C4.05268 12.2598 4 12.1326 4 12V6C4 5.86739 4.05268 5.74021 4.14645 5.64645C4.24021 5.55268 4.36739 5.5 4.5 5.5ZM7 5.5C7.13261 5.5 7.25979 5.55268 7.35355 5.64645C7.44732 5.74021 7.5 5.86739 7.5 6V12C7.5 12.1326 7.44732 12.2598 7.35355 12.3536C7.25979 12.4473 7.13261 12.5 7 12.5C6.86739 12.5 6.74021 12.4473 6.64645 12.3536C6.55268 12.2598 6.5 12.1326 6.5 12V6C6.5 5.86739 6.55268 5.74021 6.64645 5.64645C6.74021 5.55268 6.86739 5.5 7 5.5ZM10 6C10 5.86739 9.94732 5.74021 9.85355 5.64645C9.75979 5.55268 9.63261 5.5 9.5 5.5C9.36739 5.5 9.24021 5.55268 9.14645 5.64645C9.05268 5.74021 9 5.86739 9 6V12C9 12.1326 9.05268 12.2598 9.14645 12.3536C9.24021 12.4473 9.36739 12.5 9.5 12.5C9.63261 12.5 9.75979 12.4473 9.85355 12.3536C9.94732 12.2598 10 12.1326 10 12V6Z" fill="black"/>
											<path d="M13.5 3C13.5 3.26522 13.3946 3.51957 13.2071 3.70711C13.0196 3.89464 12.7652 4 12.5 4H12V13C12 13.5304 11.7893 14.0391 11.4142 14.4142C11.0391 14.7893 10.5304 15 10 15H4C3.46957 15 2.96086 14.7893 2.58579 14.4142C2.21071 14.0391 2 13.5304 2 13V4H1.5C1.23478 4 0.98043 3.89464 0.792893 3.70711C0.605357 3.51957 0.5 3.26522 0.5 3V2C0.5 1.73478 0.605357 1.48043 0.792893 1.29289C0.98043 1.10536 1.23478 1 1.5 1H5C5 0.734784 5.10536 0.48043 5.29289 0.292893C5.48043 0.105357 5.73478 0 6 0L8 0C8.26522 0 8.51957 0.105357 8.70711 0.292893C8.89464 0.48043 9 0.734784 9 1H12.5C12.7652 1 13.0196 1.10536 13.2071 1.29289C13.3946 1.48043 13.5 1.73478 13.5 2V3ZM3.118 4L3 4.059V13C3 13.2652 3.10536 13.5196 3.29289 13.7071C3.48043 13.8946 3.73478 14 4 14H10C10.2652 14 10.5196 13.8946 10.7071 13.7071C10.8946 13.5196 11 13.2652 11 13V4.059L10.882 4H3.118ZM1.5 3H12.5V2H1.5V3Z" fill="black"/>
											</svg>
										</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr__( 'Remove this item', 'woocommerce' ),
										esc_attr( $_product->get_id() ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
							</div>
							</div>
							<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']) , $cart_item, $cart_item_key); ?>
							<?php if($_product->get_max_purchase_quantity()>>1){ ?>
							<div class="cwmp-quantity">
							<?php
							echo "<button id=\"cwmpminus\" class=\"minus cwmpminus\">-</button>";
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
									),
									$_product,
									false
								);
								
							}
							echo $product_quantity;
							echo "<button id=\"cwmpplus\" class=\"plus cwmpplus\">+</button>";
							?>
							</div>
							<?php } ?>
						</div>
					</div>
				<?php
    }
}
?>
		</div>