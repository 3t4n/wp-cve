<?php

$product = qi_addons_for_elementor_woo_get_global_product();

if ( ! empty( $product ) ) {
	$price = $product->get_price_html();

	if ( ! empty( $price ) ) { ?>
		<div class="qodef-woo-product-price price"><?php echo wp_kses_post( $price ); ?></div>
	<?php }
} ?>
