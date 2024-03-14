<?php

/**
 * Shipping Methods Display
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>



<?php if ( $available_methods ) : ?>
<ul id="shipping_method" class="woocommerce-shipping-methods list-style-type:none padding:1">
    <?php foreach ( $available_methods as $method ) : ?>
    <li>
        <?php
		if ( 1 < count( $available_methods ) ) {
			printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', esc_html( $index ), esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), esc_attr( checked( $method->id, $chosen_method, false ) ) ); // WPCS: XSS ok.
		} else {
			printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', esc_attr($index), esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
		}
				printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', esc_attr( $index ), esc_attr( sanitize_title( $method->id ) ), esc_html( wc_cart_totals_shipping_method_label( $method ) ) ); // WPCS: XSS ok.
				do_action( 'woocommerce_after_shipping_rate', $method, $index );
		?>
    </li>
    <?php endforeach; ?>
</ul>
<?php if ( is_cart() ) : ?>
<p class="woocommerce-shipping-destination">
    <?php
		if ( $formatted_destination ) {
			// Translators: $s shipping destination.
			printf( esc_html__( 'Shipping to %s.', 'shop-ready' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' );
			$calculator_text = esc_html__( 'Change address', 'shop-ready' );
		} else {
			echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'shop-ready' ) ) );
		}
		?>
</p>
<?php endif; ?>
<?php
elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
	if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
		echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'shopready-elementor-addon' ) ) );
	} else {
		echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'shopready-elementor-addon' ) ) );
	}
elseif ( ! is_cart() ) :
	echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'shop-ready' ) ) );
else :
	// Translators: $s shipping destination.
	echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'shopready-elementor-addon' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
	$calculator_text = esc_html__( 'Enter a different address', 'shop-ready' );
endif;
?>

<?php if ( $show_package_details ) : ?>
<?php echo wp_kses_post( '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>' ); ?>
<?php endif; ?>

<?php if ( $show_shipping_calculator ) : ?>
<?php woocommerce_shipping_calculator( $calculator_text ); ?>
<?php endif; ?>