<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

?>
<div class="uws-order-tracking <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[woocommerce_order_tracking]' ); ?>
</div>