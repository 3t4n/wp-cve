<?php
if ( 'no' !== $slider_pagination ) {

	$pagination_classes = '';
	if ( isset( $unique ) && ! empty( $unique ) ) {
		$pagination_classes = 'qodef-swiper-pagination-outside swiper-pagination-' . esc_attr( $unique );
	}
	?>
	<div class="swiper-pagination <?php echo esc_attr( $pagination_classes ); ?>"></div>
<?php } ?>
