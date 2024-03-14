<?php
/**
 * WC Orders Home Template
 */
?>
<div class="youzify-wc-main-content youzify-wc-orders-content">

	<?php do_action( 'youzify_wc_before_orders_content' ); ?>

	<?php echo do_shortcode( '[youzify_woocommerce_orders]' ); ?>

	<?php do_action( 'youzify_wc_after_orders_content' ); ?>

</div>