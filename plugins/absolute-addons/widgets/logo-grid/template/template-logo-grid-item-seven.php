<?php
/**
 * Template Style Seven for Logo Grid
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

$this->add_render_attribute( [
	'logo_slider' => [
		'class' => 'absp-logo-slider absp-swiper-wrapper swiper-container',
	],
] );

$this->add_render_attribute( [ 'logo_slider' => $this->get_slider_attributes( $settings ) ] );
?>
<div class="logo-slider">
	<div <?php $this->print_render_attribute_string( 'logo_slider' ); ?>>
		<div class="swiper-wrapper">
			<?php foreach ( $settings['logo-grid-gallery'] as $logo ) : ?>
				<!-- Slides -->
				<div class="swiper-slide absp-logo-grid-item">
					<img src="<?php echo esc_url( $logo['url'] ); ?>">
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php $this->slider_nav( $settings ); ?>
</div>



