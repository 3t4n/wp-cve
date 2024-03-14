<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$uws_product_id = 0;
if ( ! empty( $atts['id'] ) && isset( $atts['id']['0'] ) ) {
	$uws_product_id = (int) $atts['id']['0'];
}
?>
<div class="uws-add-to-cart <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[add_to_cart id="' . $uws_product_id . '"]' ); ?>
</div>