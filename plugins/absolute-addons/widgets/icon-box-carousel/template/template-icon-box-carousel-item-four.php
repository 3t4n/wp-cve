<?php
/**
 * Template Style Four for Icon Box Carousel
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;
/**
 * @var array $feature
 */
?>
<div class="absp-carousel-item-icon">
	<?php
	if ( 'svg' === $feature['carousel_icons']['library'] ) {
		if ( ! empty( $feature['carousel_icons']['value']['id'] ) ) {
			echo wp_get_attachment_image( $feature['carousel_icons']['value']['id'], 'full' );
		} else {
			?>
			<img src="<?php echo esc_url( $feature['carousel_icons']['value']['url'] ); ?>">
			<?php
		}
	} else { ?>
		<div class="icon-box-icon">
			<i class="<?php echo esc_attr( $feature['carousel_icons']['value'] ); ?>" aria-hidden="true"></i>
		</div>
	<?php } ?>
</div>
<span class="absp-carousel-item-sub-title"><?php absp_render_title( $feature['sub_title'] ); ?></span>
<h2 class="absp-carousel-item-title"><?php absp_render_title( $feature['carousel_title'] ); ?></h2>
<div class="absp-carousel-item-content"><?php echo wp_kses_post( $feature['content'] ); ?></div>
