<?php

$product = qi_addons_for_elementor_woo_get_global_product();

if ( ! empty( $product ) && 'no' !== get_option( 'woocommerce_enable_review_rating' ) ) {
	$rating = $product->get_average_rating();

	if ( ! empty( $rating ) && 'yes' === $show_rating ) { ?>
		<div class="qodef-e-ratings qodef-m"><?php echo qi_addons_for_elementor_woo_product_get_rating_html( '', $rating, 0 ); ?></div>
	<?php }
} ?>
