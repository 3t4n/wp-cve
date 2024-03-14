<?php
/**
 * WC Checkout Template
 */
?>
<div class="youzify-wc-main-content youzify-wc-checkout-content">

	<?php do_action( 'youzify_wc_before_checkout_content' ); ?>

	<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>

	<?php do_action( 'youzify_wc_after_checkout_content' ); ?>

</div>