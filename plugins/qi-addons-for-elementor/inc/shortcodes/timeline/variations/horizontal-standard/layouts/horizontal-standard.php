<div <?php qi_addons_for_elementor_framework_class_attribute( $item['item_classes'] ); ?>>
	<div class="qodef-e-line-holder">
		<span class="qodef-e-line"></span>
		<div class="qodef-e-point-holder">
			<div class="qodef-e-point">
				<?php qi_addons_for_elementor_template_part( 'shortcodes/timeline', 'templates/parts/icon', '', $params ); ?>
			</div>
		</div>
	</div>
	<div class="qodef-e-item-inner">
		<div class="qodef-e-top-holder">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/timeline', 'templates/parts/image', '', $params ); ?>
		</div>
		<div class="qodef-e-content-holder">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/timeline', 'templates/parts/date', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/timeline', 'templates/parts/title', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/timeline', 'templates/parts/text', '', $params ); ?>
		</div>
	</div>
</div>
