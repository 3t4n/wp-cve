<?php remove_action( 'woocommerce_checkout_order_review','woocommerce_checkout_payment',20 ); ?>	
<?php do_action( 'woocommerce_checkout_order_review' );?>