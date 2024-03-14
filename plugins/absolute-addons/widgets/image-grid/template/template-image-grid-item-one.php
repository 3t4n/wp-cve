<?php
/**
 * Template Style One for Image Grid
 *
 * @package AbsoluteAddons
 * @var $settings
 *
 */
?>

<div class="absp-grid-wrapper">
	<?php foreach ( $settings['image-list'] as $image ) { ?>
		<div class="<?php echo esc_attr( $image['image-style'] ); ?> absp-grid-item">
			<a data-elementor-open-lightbox="<?php echo esc_attr( $settings['enable-lightbox'] ); ?>" href="<?php echo esc_url( $image['image']['url'] ); ?>">
				<img src="<?php echo esc_url( $image['image']['url'] ) ?>" alt="<?php echo esc_attr( $image['image-alt'] ) ?>">
			</a>
		</div>
	<?php } ?>
</div>

