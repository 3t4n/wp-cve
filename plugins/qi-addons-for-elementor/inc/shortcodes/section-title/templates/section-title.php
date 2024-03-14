<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php if ( 'above' === $params['subtitle_position'] ) { ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/section-title', 'templates/parts/subtitle', '', $params ); ?>
	<?php } ?>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/section-title', 'templates/parts/title', '', $params ); ?>
	<?php if ( 'below' === $params['subtitle_position'] ) { ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/section-title', 'templates/parts/subtitle', '', $params ); ?>
	<?php } ?>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/section-title', 'templates/parts/text', '', $params ); ?>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/section-title', 'templates/parts/button', '', $params ); ?>
</div>
