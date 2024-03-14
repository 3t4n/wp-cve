<?php
/**
 * Slider Gallery block template
 *
 * @package Canvas
 */

$images = (array) ( isset( $attributes['images'] ) ? $attributes['images'] : array() );

if ( empty( $images ) ) {
	return;
}

echo '<div class="' . esc_attr( $attributes['className'] ) . '" ' . ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ) . '>';

?>

<div
	class='gallery cnvs-gallery-type-slider'
	cnvs-flickity="init"
	data-sg-page-dots="<?php echo esc_attr( $attributes['showBullets'] ? 'true' : 'false' ); ?>"
	data-sg-page-info="<?php echo esc_attr( $attributes['showCaptions'] ? 'true' : 'false' ); ?>"
	data-sg-nav="<?php echo esc_attr( $attributes['showPrevNextButtons'] ? 'true' : 'false' ); ?>"
>
	<?php
	foreach ( $images as $img ) {
		$link = false;

		switch ( $attributes['linkTo'] ) {
			case 'post':
				$link = get_the_permalink( $img );
				break;
			case 'file':
				$link = wp_get_attachment_image_src( $img, 'full' )[0];
				break;
		}

		?>
		<figure class="gallery-item">
			<?php if ( $link ) : ?>
				<a href="<?php echo esc_url( $link ); ?>">
			<?php endif; ?>

			<?php echo wp_get_attachment_image( $img, $attributes['imageSize'] ); ?>

			<?php if ( $link ) : ?>
				</a>
			<?php endif; ?>

			<?php
			if ( $attributes['showCaptions'] ) {
				$caption = wp_get_attachment_caption( $img );

				if ( $caption ) {
					echo '<div class="caption wp-caption-text gallery-caption">' . wp_kses( $caption, 'post' ) . '</div>';
				}
			}
			?>
		</figure>
		<?php
	}
	?>
</div>

<?php

echo '</div>';
