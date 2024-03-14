<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-items">
		<?php
		foreach ( $items as $item ) {
			qi_addons_for_elementor_template_part( 'shortcodes/interactive-link-showcase', 'templates/parts/title', '', $item );
		}
		?>
	</div>
	<div class="qodef-m-images">
		<?php
		foreach ( $items as $item ) {
			$item['this_shortcode']     = $this_shortcode;
			$additional_classes         = ! empty( $item['item_image_position'] ) ? 'qodef-position--' . $item['item_image_position'] : 'qodef-position--left';
			$additional_classes        .= ' elementor-repeater-item-' . $item['_id'];
			$item['additional_classes'] = $additional_classes;

			qi_addons_for_elementor_template_part( 'shortcodes/interactive-link-showcase', 'templates/parts/image', '', $item );
		}
		?>
	</div>
</div>
