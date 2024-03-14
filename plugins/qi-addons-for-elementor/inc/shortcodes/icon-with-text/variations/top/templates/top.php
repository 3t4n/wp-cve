<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<?php if ( isset( $icon_type ) && ! empty( $icon_type['value'] ) ) { ?>
		<div class="qodef-m-icon-wrapper">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text', 'templates/parts/icon', '', $params ); ?>
		</div>
	<?php } ?>
	<div class="qodef-m-content">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text', 'templates/parts/separator', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text', 'templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text', 'templates/parts/text', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/icon-with-text', 'templates/parts/button', '', $params ); ?>
	</div>
</div>
