<div <?php qi_addons_for_elementor_framework_class_attribute( $item_classes ); ?>>
	<div class="qodef-e-inner">
		<div class="qodef-e-top">
			<?php
			qi_addons_for_elementor_template_part(
				'shortcodes/testimonials-slider',
				'templates/post-info/image',
				'',
				array_merge(
					$params,
					array(
						'size' => 'full',
					)
				)
			);
			?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/testimonials-slider', 'templates/post-info/quote', '', $params ); ?>
		</div>
		<div class="qodef-e-content">
			<?php qi_addons_for_elementor_template_part( 'shortcodes/testimonials-slider', 'templates/post-info/title', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/testimonials-slider', 'templates/post-info/text', '', $params ); ?>
			<?php qi_addons_for_elementor_template_part( 'shortcodes/testimonials-slider', 'templates/post-info/author', '', $params ); ?>
		</div>
	</div>
</div>
