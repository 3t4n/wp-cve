<?php
/**
 * Template Style Thirteen for Icon Box
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>

<div class="icon-box">
	<div class="icon-box-bg-shape">
		<svg version="1.1" id="icon-box-bg-shape-layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 300.5 334.64" style="enable-background:new 0 0 300.5 334.64;" xml:space="preserve">
			<path class="icon-box-bg-shape-svg" d="M0.25,103.61v127.42c0,14.17,7.56,27.26,19.83,34.34l110.35,63.71c12.27,7.08,27.38,7.08,39.65,0l110.35-63.71 c12.27-7.08,19.83-20.17,19.83-34.34V103.61c0-14.17-7.56-27.26-19.83-34.34L170.08,5.56c-12.27-7.08-27.38-7.08-39.65,0 L20.08,69.27C7.81,76.35,0.25,89.45,0.25,103.61z"/>
		</svg>
		<div class="icon-box-content-area">
			<?php $this->render_box_icon( $settings, '<div class="icon-box-img">', '</div>' ); ?>
			<?php $this->render_title( $settings, '<div class="icon-box-content">', '</div>' ); ?>
		</div>
	</div>
</div>
