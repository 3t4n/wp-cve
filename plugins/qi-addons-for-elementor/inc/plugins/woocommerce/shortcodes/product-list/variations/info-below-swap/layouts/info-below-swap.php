<div <?php wc_product_class( $item_classes ); ?>>
	<div class="qodef-e-product-inner">
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="qodef-e-product-image">
				<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/mark', '', $params ); ?>
				<div class="qodef-e-product-image-holder">
					<a itemprop="url" href="<?php the_permalink(); ?>">
						<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/image', '', $params ); ?>
					</a>
				</div>
			</div>
		<?php } ?>
		<div class="qodef-e-product-content">
			<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/category', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/title', '', $params ); ?>
			<div class="qodef-e-swap-holder">
				<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/price', '', $params ); ?>
				<div class="qodef-e-to-swap">
					<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/add-to-cart', '', $params ); ?>
				</div>
			</div>
			<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/rating', '', $params ); ?>
			<?php
			// Hook to include additional content inside product list item content
			do_action( 'qi_addons_for_elementor_action_product_list_item_additional_content', $params );
			?>
		</div>
		<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/link' ); ?>
	</div>
</div>
