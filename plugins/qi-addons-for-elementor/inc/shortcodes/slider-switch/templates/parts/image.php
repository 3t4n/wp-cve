<div class="<?php echo esc_attr( $item_classes ); ?>">
	<?php if ( ! empty( $item_link['url'] ) ) { ?>
		<a itemprop="url" href="<?php echo esc_url( $item_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item_link ) ); ?>>
	<?php } ?>
	<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $image_id, intval( $custom_image_width ), intval( $custom_image_height ) ); ?>
	<?php if ( ! empty( $item_link['url'] ) ) { ?>
		</a>
	<?php } ?>
</div>
