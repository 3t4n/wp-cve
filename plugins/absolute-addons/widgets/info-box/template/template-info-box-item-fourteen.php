<?php
/**
 * Template Style Fourteen for Info Box
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="info-box">
	<div class="info-box-bg-shape">
		<svg version="1.1" id="info-box-bg-shape-layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 300.5 334.64" style="enable-background:new 0 0 300.5 334.64;" xml:space="preserve">
			<path class="info-box-bg-shape-svg" d="M0.25,103.61v127.42c0,14.17,7.56,27.26,19.83,34.34l110.35,63.71c12.27,7.08,27.38,7.08,39.65,0l110.35-63.71 c12.27-7.08,19.83-20.17,19.83-34.34V103.61c0-14.17-7.56-27.26-19.83-34.34L170.08,5.56c-12.27-7.08-27.38-7.08-39.65,0 L20.08,69.27C7.81,76.35,0.25,89.45,0.25,103.61z"/>
		</svg>
		<div class="info-box-content-area">
			<div class="info-box-content">
				<?php if ( ! empty( $settings['info_box_title'] ) ) { ?>
					<h2 <?php $this->print_render_attribute_string( 'info_box_title' ); ?> ><?php absp_render_title( $settings['info_box_title'] ); ?></h2>
				<?php } ?>
				<?php if ( 'true' === $settings['info_box_separator_enable'] ) : ?>
					<div class="info-box-separator-inner">
						<span class="info-box-separator"></span>
					</div>
				<?php endif; ?>
				<?php echo wp_kses_post( $settings['info_box_content'] ); ?>
				<?php $this->render_button( $settings ); ?>
			</div>
		</div>
	</div>
</div>
