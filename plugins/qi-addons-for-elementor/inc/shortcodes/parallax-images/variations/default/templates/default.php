<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-images">
		<div class="qodef-e-main-image-holder">
			<div class="qodef-e-main-image-zoom-holder">
				<?php if ( ! empty( $main_image ) ) : ?>
					<div class="qodef-e-main-image" <?php qi_addons_for_elementor_framework_inline_attrs( $main_image_data_attrs ); ?>>
						<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $main_image_proportion, $main_image, intval( $main_image_custom_width ), intval( $main_image_custom_height ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( ! empty( $items ) ) { ?>
			<?php foreach ( $items as $item ) { ?>
				<?php
				$item_classes    = $this_shortcode->get_item_classes( $item );
				$item_data_attrs = $this_shortcode->get_item_data_atts( $item );
				?>
				<div <?php qi_addons_for_elementor_framework_class_attribute( $item_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attrs( $item_data_attrs ); ?>>
					<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $item['parallax_image_proportion'], $item['parallax_image'], intval( $item['parallax_image_custom_width'] ), intval( $item['parallax_image_custom_height'] ) ); ?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>
