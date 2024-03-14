<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-grid-inner">
		<?php
		foreach ( $items as $item_key => $item ) {
			$item['item_classes']   = $this_shortcode->get_item_classes( $params );
			$item['item_title_tag'] = isset( $item_title_tag ) ? $item_title_tag : 'h5';
			$item['item_key']       = ( $item_key + 1 ) . '.';
			$item['id']             = $item['_id'];

			qi_addons_for_elementor_template_part( 'shortcodes/process', 'variations/' . $layout . '/layouts/' . $layout, '', $item );
		}
		?>
	</div>
</div>
