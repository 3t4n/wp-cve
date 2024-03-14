<?php
/**
 * WC Orders Template
 */
if ( youzify_is_woocommerce_tab( 'orders', 'view-order' ) ) {
	bp_get_template_part( 'members/single/woocommerce/orders/view-order' );
} else {
	bp_get_template_part( 'members/single/woocommerce/orders/home' );
}