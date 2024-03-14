<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-inner">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/business-hours', 'templates/parts/subtitle', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/business-hours', 'templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/business-hours', 'templates/parts/separator', '', $params ); ?>
		<div class="qodef-m-items">
			<?php
			foreach ( $items as $item ) {
				$classes         = array( 'qodef-e', 'qodef-e-item' );
				$classes[]       = 'elementor-repeater-item-' . $item['_id'];
				$item['classes'] = $classes;
				$item['day_tag'] = $day_tag;
				qi_addons_for_elementor_template_part( 'shortcodes/business-hours', 'variations/' . $layout . '/templates/item', '', $item );
			}
			?>
		</div>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/business-hours', 'templates/parts/text', '', $params ); ?>
	</div>
</div>
