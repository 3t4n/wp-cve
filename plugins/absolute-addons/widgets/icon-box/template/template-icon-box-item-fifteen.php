<?php
/**
 * Template Style Fifteen for Icon Box
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="icon-box">
	<div class="icon-box-inner">
		<?php $this->render_box_icon( $settings, '<div class="icon-box-img">', '</div>' ); ?>
	</div>
	<div class="icon-box-inner">
		<div class="icon-box-content">
			<?php $this->render_sub_title( $settings ); ?>
			<?php $this->render_title( $settings ); ?>
			<?php absp_render_content( $settings['icon_box_content'] ); ?>
		</div>
	</div>
</div>
<?php if ( 'true' === $settings['icon_box_divider_enable'] ) : ?>
<div class="divider-option">
	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		viewBox="0 0 15 142.96" style="enable-background:new 0 0 15 142.96;" xml:space="preserve">
	<style type="text/css">
		.icon_box_15_st0{fill:none;stroke:#EFEFEF;stroke-miterlimit:10;}
	</style>
	<g>
		<path class="icon_box_15_st0" d="M8.86,0.26c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9c-2.1,3.37-2.1,7.64,0,11.01l6.79,10.9
			c2.1,3.37,2.1,7.64,0,11.01L2.08,65.97c-2.1,3.37-2.1,7.64,0,11.01l6.78,10.89c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9
			c-2.1,3.37-2.1,7.64,0,11l6.79,10.91c2.1,3.37,2.1,7.64,0,11"/>
		<path class="icon_box_15_st0" d="M12.92,0.26c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9c-2.1,3.37-2.1,7.64,0,11.01l6.79,10.9
			c2.1,3.37,2.1,7.64,0,11.01L6.14,65.97c-2.1,3.37-2.1,7.64,0,11.01l6.78,10.89c2.1,3.37,2.1,7.64,0,11.01l-6.79,10.9
			c-2.1,3.37-2.1,7.64,0,11l6.79,10.91c2.1,3.37,2.1,7.64,0,11"/>
	</g>
	</svg>
</div>
<?php endif;?>
