<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-grid-inner">
		<?php
		// Include masonry template
		qi_addons_for_elementor_template_part( 'masonry', 'templates/sizer-gutter', '', $params['behavior'] );

		// Include items
		qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-category-list', 'templates/loop', '', $params );
		?>
	</div>
</div>
