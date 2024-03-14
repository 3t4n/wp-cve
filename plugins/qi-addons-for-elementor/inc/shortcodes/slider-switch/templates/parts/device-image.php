<div class="qodef-m-item swiper-slide">
	<?php if ( ! empty( $item_link['url'] ) ) { ?>
		<a itemprop="url" href="<?php echo esc_url( $item_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item_link ) ); ?>>
	<?php } ?>
	<?php echo qi_addons_for_elementor_get_list_shortcode_item_image( 'full', $image_id ); ?>
	<?php if ( ! empty( $item_link['url'] ) ) { ?>
		</a>
	<?php } ?>
</div>
