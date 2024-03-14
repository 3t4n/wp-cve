<div class="<?php echo esc_attr( $item_classes ); ?>">
	<div class="qodef-e-inner">
		<?php if ( 'yes' === $enable_popup ) { ?>
			<a class="qodef-popup-item" itemprop="image" href="<?php echo esc_url( qi_addons_for_elementor_get_attachment_image_url( $image_id, 'full' ) ); ?>" data-type="image" data-fslightbox="gallery-<?php echo esc_attr( $unique ); ?>">
		<?php } elseif ( 'yes' === $enable_custom_links && ! empty( $image_link['url'] ) ) { ?>
			<a itemprop="url" href="<?php echo esc_url( $image_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $image_link ) ); ?>>
		<?php } ?>
			<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $image_size, $image_id ); ?>
		<?php if ( 'yes' === $enable_popup || ( 'yes' === $enable_custom_links && ! empty( $image_link['url'] ) ) ) { ?>
			</a>
		<?php } ?>
	</div>
</div>
