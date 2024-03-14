<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> <?php qi_addons_for_elementor_framework_inline_attr( $slider_attr, 'data-options' ); ?>>
	<div class="swiper-wrapper">
		<?php
		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				qi_addons_for_elementor_template_part( 'shortcodes/image-slider', 'templates/parts/image', '', array_merge( $params, $image ) );
			}
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
