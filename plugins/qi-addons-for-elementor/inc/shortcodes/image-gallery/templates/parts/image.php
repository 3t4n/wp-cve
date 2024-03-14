<div class="<?php echo esc_attr( $item_classes ); ?>">
	<div class="qodef-e-inner">
		<?php if ( 'yes' === $enable_popup ) { ?>
			<a class="qodef-popup-item" itemprop="image" href="<?php echo esc_url( qi_addons_for_elementor_get_attachment_image_url( $image_id, 'full' ) ); ?>" data-type="image" data-fslightbox="gallery-<?php echo esc_attr( $unique ); ?>">
		<?php } elseif ( 'yes' === $enable_custom_links && ! empty( $image_link ) ) { ?>
			<a itemprop="url" href="<?php echo esc_url( $image_link ); ?>" target="<?php echo isset( $custom_links_target ) ? esc_attr( $custom_links_target ) : '_blank'; ?>">
		<?php } ?>
				<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $image_id, intval( $custom_image_width ), intval( $custom_image_height ) ); ?>
		<?php if ( 'yes' === $enable_popup || ( 'yes' === $enable_custom_links && ! empty( $image_link ) ) ) { ?>
			</a>
		<?php } ?>
	</div>
</div>
