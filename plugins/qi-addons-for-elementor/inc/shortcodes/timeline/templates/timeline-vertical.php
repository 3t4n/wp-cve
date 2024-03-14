<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php
	if ( count( $items ) ) {
		$i = 0;
		foreach ( $items as $item ) {
			$item['item_classes']  = $this_shortcode->get_item_classes( $params );
			$item['item_classes'] .= ' elementor-repeater-item-' . $item['_id'];

			if ( 1 === $i % 2 ) {
				$item['item_classes'] .= ' qodef-reverse';
			} else {
				$item['item_classes'] .= ' qodef-obverse';
			}

			$new_params = array_merge(
				$params,
				array(
					'item' => $item,
				)
			);


			echo apply_filters(
				'qi_addons_for_elementor_filter_timeline_render_template',
				qi_addons_for_elementor_get_template_part(
					'shortcodes/timeline',
					'variations/' . $layout . '/layouts/' . $layout,
					'',
					$new_params
				),
				$new_params
			);

			$i++;
		}
	}
	?>
</div>
