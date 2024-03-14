<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $holder_data, 'data-options' ); ?>>
	<div class="qodef-nav-prev">
		<?php qi_addons_for_elementor_template_part( 'content', 'templates/parts/arrow-left', '', $params ); ?>
	</div>
	<div class="qodef-nav-next">
		<?php qi_addons_for_elementor_template_part( 'content', 'templates/parts/arrow-right', '', $params ); ?>
	</div>
	<div class="qodef-grid-inner">
	<?php
	if ( count( $items ) ) {
		$i = 0;
		foreach ( $items as $item ) {
			$item['item_classes']  = $this_shortcode->get_item_classes( $params );
			$item['item_classes'] .= ' elementor-repeater-item-' . $item['_id'];

			if ( 0 === $i % 2 ) {
				$item['item_classes'] .= ' qodef-reverse';
			} else {
				$item['item_classes'] .= ' qodef-obverse';
			}

			qi_addons_for_elementor_template_part(
				'shortcodes/timeline',
				'variations/' . $layout . '/layouts/' . $layout,
				'',
				array_merge(
					$params,
					array(
						'item' => $item,
					)
				)
			);

			$i++;
		}
	}
	?>
	</div>
</div>
