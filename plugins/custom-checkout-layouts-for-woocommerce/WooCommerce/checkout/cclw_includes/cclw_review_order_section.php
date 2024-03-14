<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
<div id="cclw_order_details_table">
    <h3 class="border_html"><?php esc_html_e('Review your orders', 'woocommerce' ); ?></h3>
	<div id="order_review_table" class="cclw_order_review_table">
	   	<?php do_action( 'cclw_review_order_section' ); ?>
	</div>
</div>


