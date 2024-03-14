<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Compatibility with woocommerce-checkout-manager-pro 6.x
 */
function WOOCCM() {
	return Quadlayers\WOOCCM\WOOCCM();
}
