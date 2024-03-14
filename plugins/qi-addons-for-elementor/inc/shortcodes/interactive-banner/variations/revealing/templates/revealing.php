<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/interactive-banner', 'templates/parts/image', '', $params ); ?>
	<div class="qodef-m-content">
		<div class="qodef-m-content-inner">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/interactive-banner', 'templates/parts/subtitle', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/interactive-banner', 'templates/parts/title', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/interactive-banner', 'templates/parts/text', '', $params ); ?>
			<?php
			if ( 'yes' === $enable_button ) {
				qi_addons_for_elementor_template_part( 'shortcodes/interactive-banner', 'templates/parts/button', '', $params );
			}
			?>
		</div>
	</div>
	<?php
	if ( 'yes' === $enable_link_overlay ) {
		qi_addons_for_elementor_template_part( 'shortcodes/interactive-banner', 'templates/parts/link', '', $params );
	}
	?>
</div>
