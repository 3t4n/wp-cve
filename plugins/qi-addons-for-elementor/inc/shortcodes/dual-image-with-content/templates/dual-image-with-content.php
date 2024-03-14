<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-dual-content">
		<div class="qodef-m-inner-content">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/dual-image-with-content', 'templates/parts/title', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/dual-image-with-content', 'templates/parts/text', '', $params ); ?>
		</div>
		<div class="qodef-m-inner-bottom">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/dual-image-with-content', 'templates/parts/content', '', $params ); ?>
		</div>
	</div>
	<div class="qodef-image-holder">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/dual-image-with-content', 'templates/parts/main-image', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/dual-image-with-content', 'templates/parts/second-image', '', $params ); ?>
	</div>
</div>
