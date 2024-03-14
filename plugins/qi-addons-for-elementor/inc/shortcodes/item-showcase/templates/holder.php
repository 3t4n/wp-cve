<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-items qodef--left">
		<?php
		if ( count( $items ) ) {
			foreach ( $items[0] as $item ) {
				qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'variations/' . $layout . '/templates/' . $layout, '', array_merge( $params, array( 'item' => $item ) ) );
			}
		}
		?>
	</div>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'templates/parts/image', '', $params ); ?>
	<div class="qodef-m-items qodef--right">
		<?php
		if ( count( $items ) ) {
			foreach ( $items[1] as $item ) {
				qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'variations/' . $layout . '/templates/' . $layout, '', array_merge( $params, array( 'item' => $item ) ) );
			}
		}
		?>
	</div>
</div>
