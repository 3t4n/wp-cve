<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $slider_attr, 'data-options' ); ?>>
	<div class="swiper-wrapper">
		<?php
		foreach ( $items as $item ) {
			$item['client_classes']      = $this_shortcode->get_item_classes( $params );
			$item['images_proportion']   = $images_proportion;
			$item['custom_image_width']  = $custom_image_width;
			$item['custom_image_height'] = $custom_image_height;
			$item['title_tag']           = ! empty( $title_tag ) ? $title_tag : 'h5';

			qi_addons_for_elementor_template_part( 'shortcodes/clients-slider', 'templates/client', '', $item );
		}
		?>
	</div>
	<?php
	if ( 'inside' === $slider_navigation_position ) {
		qi_addons_for_elementor_template_part( 'content', 'templates/swiper-nav', '', $params );
	}
	if ( 'inside' === $slider_pagination_position ) {
		qi_addons_for_elementor_template_part( 'content', 'templates/swiper-pag', '', $params );
	}
	?>
</div>
<?php
if ( 'outside' === $slider_navigation_position || 'together' === $slider_navigation_position ) {
	qi_addons_for_elementor_template_part( 'content', 'templates/swiper-nav', 'outside', $params );
}
if ( 'outside' === $slider_pagination_position ) {
	qi_addons_for_elementor_template_part( 'content', 'templates/swiper-pag', $slider_pagination_position, $params );
}
