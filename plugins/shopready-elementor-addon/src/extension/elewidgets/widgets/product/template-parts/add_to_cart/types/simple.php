<?php

/**
 * Simple product add to cart
 *
 * @package Simple Product
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! $product->is_purchasable() ) {
	return;
}
$qty_label = $settings['simple_qty_label'] == 'yes' ? $settings['simple_qty_label_text'] : '';
if ( $product->is_in_stock() ) : ?>

<?php

	if ( $settings['show_stock'] == 'yes' ) {
		echo wp_kses_post( wc_get_stock_html( $product ) ); // WPCS: XSS ok.
	}

	?>

<form class="cart"
    action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
    method="post" enctype='multipart/form-data'>
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
    <div class="shop-ready-quantity-warapper display:flex flex-direction:column">

        <?php if ( $qty_label != '' ) : ?>

        <div class="shop-ready-product-qty-label"> <?php echo wp_kses_post( $qty_label ); ?> </div>

        <?php endif; ?>

        <?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>
        <div class="sr-cart-wrapper">
            <?php
			woocommerce_quantity_input(
				array(
					'min_value'   => $product->get_min_purchase_quantity(),
					'max_value'   => $product->get_max_purchase_quantity(),
					'input_value' => sanitize_text_field( isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( sanitize_text_field( $_POST['quantity'] ) ) ) : $product->get_min_purchase_quantity() ), // WPCS: CSRF ok, input var ok.
				),
				$product
			);
			do_action( 'woocommerce_after_add_to_cart_quantity' );

			?>
            <button type="submit" name="add-to-cart" value="<?php echo wp_kses_post( $product->get_id() ); ?>"
                class="single_add_to_cart_button button alt"><?php echo wp_kses_post( $product->single_add_to_cart_text() ); ?></button>
        </div>
        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </div>
</form>

<?php endif; ?>