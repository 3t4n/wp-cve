<?php if ( 'no' !== $slider_navigation ) {
	$nav_next_classes = '';
	$nav_prev_classes = '';

	if ( isset( $unique ) && ! empty( $unique ) ) {
		$nav_next_classes = 'swiper-button-outside swiper-button-next-' . esc_attr( $unique );
		$nav_prev_classes = 'swiper-button-outside swiper-button-prev-' . esc_attr( $unique );
	}
	?>
	<?php if ( 'together' === $slider_navigation_position ) { ?>
		<div class="qodef-swiper-together-nav">
			<div class="qodef-swiper-together-inner">
	<?php } ?>
	<div class="swiper-button-prev <?php echo esc_attr( $nav_prev_classes ); ?>"><?php qi_addons_for_elementor_template_part( 'content', 'templates/parts/arrow-left', '', $params ); ?></div>
	<div class="swiper-button-next <?php echo esc_attr( $nav_next_classes ); ?>"><?php qi_addons_for_elementor_template_part( 'content', 'templates/parts/arrow-right', '', $params ); ?></div>
	<?php if ( 'together' === $slider_navigation_position ) { ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>
