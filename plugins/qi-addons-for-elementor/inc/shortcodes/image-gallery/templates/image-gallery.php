<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-grid-inner">
		<?php
		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				qi_addons_for_elementor_template_part( 'shortcodes/image-gallery', 'templates/parts/image', '', array_merge( $params, $image ) );
			}
		}
		?>
	</div>
</div>
