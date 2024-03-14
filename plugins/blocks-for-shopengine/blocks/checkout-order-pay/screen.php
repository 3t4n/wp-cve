<?php

defined( 'ABSPATH' ) || exit;

global $wp;

if ( \ShopEngine\Core\Template_Cpt::TYPE && $block->is_editor) {
	include_once __DIR__ . '/dummy-checkout-order.php';
} elseif ( !empty( $wp->query_vars['order-pay'] ) ) {
    echo "<div class='shopengine-checkout-order-pay'>";
	WC_Shortcode_Checkout::output( [] );
	echo "</div>";
}