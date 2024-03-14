<div <?php wc_product_class( $item_classes ); ?>>
	<div class="qodef-e-product-inner">
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="qodef-e-product-image">
				<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/mark', '', $params ); ?>
				<div class="qodef-e-product-image-holder">
					<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/image', '', $params ); ?>
				</div>
				<div class="qodef-e-product-image-inner">
					<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/link' ); ?>
					<?php
					qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/add-to-cart', '', $params );

					// Hook to include additional content inside product list item image
					do_action( 'qi_addons_for_elementor_action_product_slider_item_additional_image_content', $params );
					?>
				</div>
			</div>
		<?php } ?>
		<div class="qodef-e-product-content">
			<div class="qodef-e-product-heading">
				<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/title', '', $params ); ?>
				<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/price', '', $params ); ?>
			</div>
			<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/category', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/post-info/rating', '', $params ); ?>
			<?php
			// Hook to include additional content inside product list item content
			do_action( 'qi_addons_for_elementor_action_product_slider_item_additional_content', $params );
			?>
		</div>
	</div>
</div>
