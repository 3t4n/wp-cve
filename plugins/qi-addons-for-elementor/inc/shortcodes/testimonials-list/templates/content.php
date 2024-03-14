<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-grid-inner">
		<?php
		foreach ( $items as $item ) {
			$item['item_classes']    = $this_shortcode->get_item_classes( $params );
			$item['item_classes']   .= ' elementor-repeater-item-' . $item['_id'];
			$item['item_title_tag']  = isset( $title_tag ) ? $title_tag : 'h2';
			$item['item_text_tag']   = isset( $item_text_tag ) ? $item_text_tag : 'h3';
			$item['item_author_tag'] = isset( $item_author_tag ) ? $item_author_tag : 'h5';
			$item['item_quote_icon'] = $item_quote_icon;

			qi_addons_for_elementor_template_part( 'shortcodes/testimonials-list', 'variations/' . $layout . '/layouts/' . $layout, '', $item );
		}
		?>
	</div>
</div>
