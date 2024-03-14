<?php
/**
 * Template Style One for Icon Box Carousel
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
			echo '<div class="icon-box-img">';
			echo wp_get_attachment_image( $feature['carousel_icons']['value']['id'] );
			echo '</div>';
		} else { ?>
			<i class="<?php echo esc_attr( $feature['carousel_icons']['value'] ); ?>" aria-hidden="true"></i>
			<?php
		}
	} else { ?>
		<i class="<?php echo esc_attr( $feature['carousel_icons']['value'] ); ?>" aria-hidden="true"></i>
		<?php
	}
	?>
</div>
<div class="absp-carousel-item-content">
	<h2 class="absp-carousel-item-title"><?php absp_render_title( $feature['carousel_title'] ); ?></h2>
	<span class="absp-carousel-item-counter-number"><?php absp_render_title( $feature['sub_title'] ); ?></span>
	<span class="absp-carousel-item-content"><?php absp_render_title( $feature['content'] ); ?></span>
</div>
