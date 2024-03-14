<?php
/**
 * Template Style Sixteen for Info Box
 *
 * @package AbsoluteAddons
 * @var $settings
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="info-box">
	<div class="info-box-inner">
		<div class="info-box-svg-icon">
			<?php $this->render_box_icon( $settings ); ?>
		</div>
	</div>
	<div class="info-box-inner">
		<div class="info-box-content">
			<?php if ( ! empty( $settings['info_box_sub_title'] ) ) { ?>
				<span <?php $this->print_render_attribute_string( 'info_box_sub_title' ); ?>><?php absp_render_title( $settings['info_box_sub_title'] ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $settings['info_box_title'] ) ) { ?>
				<h2 <?php $this->print_render_attribute_string( 'info_box_title' ); ?> ><?php absp_render_title( $settings['info_box_title'] ); ?></h2>
			<?php } ?>
			<?php absp_render_content( $settings['info_box_content'] ); ?>
			<?php $this->render_button( $settings ); ?>
		</div>
	</div>
</div>

<?php if ( 'true' === $settings['info_box_divider_enable'] ) : ?>
	<div class="divider-option">
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15 208.69" style="enable-background:new 0 0 15 208.69;" xml:space="preserve">
			<style type="text/css">
				.st0 {
					fill: none;
					stroke: #EFEFEF;
					stroke-miterlimit: 10;
				}
			</style>
			<g>
				<path class="st0" d="M2.07,0.26c-2.1,3.37-2.1,7.64,0,11.01l6.79,10.9c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9 c-2.1,3.37-2.1,7.64,0,11.01l6.79,10.9c2.1,3.37,2.1,7.64,0,11.01L2.08,87.88c-2.1,3.37-2.1,7.64,0,11.01l6.78,10.89 c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9c-2.1,3.37-2.1,7.64,0,11l6.79,10.91c2.1,3.37,2.1,7.64,0,11l-6.79,10.9 c-2.1,3.37-2.1,7.63,0,11l6.79,10.92c2.1,3.37,2.1,7.63,0,11"/>
				<path class="st0" d="M6.14,0.26c-2.1,3.37-2.1,7.64,0,11.01l6.79,10.9c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9 c-2.1,3.37-2.1,7.64,0,11.01l6.79,10.9c2.1,3.37,2.1,7.64,0,11.01L6.14,87.88c-2.1,3.37-2.1,7.64,0,11.01l6.78,10.89 c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9c-2.1,3.37-2.1,7.64,0,11l6.79,10.91c2.1,3.37,2.1,7.64,0,11l-6.79,10.9 c-2.1,3.37-2.1,7.63,0,11l6.79,10.92c2.1,3.37,2.1,7.63,0,11"/>
			</g>
		</svg>
	</div>
<?php endif; ?>

