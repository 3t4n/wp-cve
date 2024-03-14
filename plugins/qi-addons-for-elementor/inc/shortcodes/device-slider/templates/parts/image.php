<div class="qodef-m-item swiper-slide">
	<?php if ( 'yes' === $enable_custom_links && ! empty( $image_link ) ) { ?>
		<a itemprop="url" href="<?php echo esc_url( $image_link ); ?>" target="_blank">
	<?php } ?>
	<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( 'full', $image_id ); ?>
	<?php if ( 'yes' === $enable_custom_links && ! empty( $image_link ) ) { ?>
		</a>
	<?php } ?>
</div>
