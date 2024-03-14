<?php
/**
 * WC Cart Template
 */
?>
<div class="youzify-wc-main-content youzify-wc-cart-content">

	<?php do_action( 'youzify_wc_before_cart_content' ); ?>

	<?php echo do_shortcode( '[woocommerce_cart]' ); ?>

	<?php do_action( 'youzify_wc_after_cart_content' ); ?>

</div>