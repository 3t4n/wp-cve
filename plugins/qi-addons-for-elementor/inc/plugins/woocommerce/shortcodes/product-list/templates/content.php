<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $data_attr ); ?>>
	<div class="qodef-grid-inner">
		<?php
		// Include masonry template
		qi_addons_for_elementor_template_part( 'masonry', 'templates/sizer-gutter', '', $params['behavior'] );

		// Include items
		qi_addons_for_elementor_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/loop', '', $params );
		?>
	</div>
	<?php
	// Include global pagination from theme
	echo apply_filters( 'qi_addons_for_elementor_filter_list_pagination', qi_addons_for_elementor_get_template_part( 'pagination', 'templates/pagination', 'standard', $params ), $params );
	?>
</div>
