<div <?php wc_product_cat_class( $item_classes ); ?>>
	<a href="<?php echo get_term_link( $category_slug, 'product_cat' ); ?>">
		<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-category-list', 'templates/post-info/image', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-category-list', 'templates/post-info/title', '', $params ); ?>
	</a>
</div>
