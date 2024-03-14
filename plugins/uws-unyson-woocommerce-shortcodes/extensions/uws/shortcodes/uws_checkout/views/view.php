<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

?>
<div class="uws-checkout <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
</div>