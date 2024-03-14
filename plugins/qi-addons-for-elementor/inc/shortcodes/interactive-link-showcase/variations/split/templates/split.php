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
			$item['this_shortcode'] = $this_shortcode;
			$slug                   = ( 'yes' === $split_stretch_image ) ? 'background' : '';

			qi_addons_for_elementor_template_part( 'shortcodes/interactive-link-showcase', 'templates/parts/image', $slug, $item );
		}
		?>
	</div>
</div>
