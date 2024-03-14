<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

?>
<div class="uws-my-account <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
</div>