<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-inner">
		<?php
		$title_tag = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'h5';

		foreach ( $items as $key => $item ) {
			$classes                   = array( 'qodef-m-item', 'qodef-e' );
			$classes[]                 = 'elementor-repeater-item-' . $item['_id'];
			$classes[]                 = ! empty( $item['item_discount_price'] ) ? 'qodef-has-discount' : '';
			$item['key']               = $key + 1;
			$item['classes']           = $classes;
			$item['title_tag']         = $title_tag;
			$item['border_style']      = $border_style;
			$item['additional_params'] = apply_filters( 'qi_addons_for_elementor_filter_pricing_list_additional_params', array(), $params, $item );

			echo apply_filters( 'qi_addons_for_elementor_filter_pricing_list_item_template', qi_addons_for_elementor_get_template_part( 'shortcodes/pricing-list', 'variations/' . $layout . '/templates/item', '', $item ), $layout, $item );
		}
		?>
	</div>
	<?php do_action( 'qi_addons_for_elementor_action_pricing_list_after_items', $params ); ?>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text', 'templates/parts/button', '', $params ); ?>
</div>
