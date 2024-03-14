<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<div class="qodef-m-digit-wrapper">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/counter', 'variations/' . $layout . '/templates/parts/digit', '', $params ); ?>
	</div>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/counter', 'variations/' . $layout . '/templates/parts/separator', '', $params ); ?>
	<div class="qodef-m-content">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/counter', 'variations/' . $layout . '/templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/counter', 'variations/' . $layout . '/templates/parts/text', '', $params ); ?>
	</div>
</div>
