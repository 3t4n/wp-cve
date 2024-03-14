<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

?>
<div class="uws-cart <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[woocommerce_cart]' ); ?>
</div>