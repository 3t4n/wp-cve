<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $checkout_options = get_option( 'custom_checkout_settings' );?>
<div class="shop_table woocommerce-checkout-review-order-table">
   <?php
	if(isset($checkout_options['cclw_checkout_ordertable']) && $checkout_options['cclw_checkout_ordertable'] != '')
	{
		include_once CCLW_PLUGIN_DIR . 'WooCommerce/checkout/order-table-layouts/'.$checkout_options['cclw_checkout_ordertable'].'.php';
	}
	else
	{
	    include_once CCLW_PLUGIN_DIR . 'WooCommerce/checkout/order-table-layouts/style-1.php';	
	}
		
	?>

    <div class="cart_totals cclw_cart_totals">
   
		<div class="cart-subtotal">
			<p class="left-corner"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></p>
			<span class="right-corner"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>
		 <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
        <div class="shipping-total">
		    <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<p class="left-corner"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></p>
			<span class="right-corner">	<?php wc_cart_totals_shipping_html(); ?></span>
           
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		</div>

	<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<p class="left-corner"><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
				<span class="right-corner"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>
		
       
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<p class="left-corner"><?php echo esc_html( $fee->name ); ?></p>
				<span class="right-corner"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<div class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<p class="left-corner"><?php echo esc_html( $tax->label ); ?></p>
						<span class="right-corner"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="tax-total">
					<p class="left-corner"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></p>
					<span class="right-corner"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		<div class="order-total">
			<p class="left-corner"><?php esc_html_e( 'Total', 'woocommerce' ); ?></p>
			<span class="right-corner"><?php wc_cart_totals_order_total_html(); ?></span>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>
</div>
