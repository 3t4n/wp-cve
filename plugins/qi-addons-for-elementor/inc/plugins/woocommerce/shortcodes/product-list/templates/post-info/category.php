<?php
$categories = qi_addons_for_elementor_woo_get_product_categories();

if ( ! empty( $categories ) && 'no' !== $show_category ) { ?>
	<div class="qodef-e-product-categories"><?php echo wp_kses_post( $categories ); ?></div>
<?php } ?>
