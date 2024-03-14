<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-inner">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/pricing-table', 'templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/pricing-table', 'templates/parts/price', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/pricing-table', 'templates/parts/separator', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/pricing-table', 'templates/parts/content', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/pricing-table', 'templates/parts/button', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/pricing-table', 'templates/parts/label', '', $params ); ?>
	</div>
	<div class="qodef-m-vertical-image-holder">
		<?php
		qi_addons_for_elementor_template_part(
			'shortcodes/pricing-table',
			'templates/parts/image',
			'',
			array(
				'image'      => $vertical_image,
				'proportion' => $vertical_image_proportion,
			)
		);
		?>
	</div>
</div>
