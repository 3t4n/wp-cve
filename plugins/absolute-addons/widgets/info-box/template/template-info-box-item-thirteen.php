<?php
/**
 * Template Style Thirteen for Info Box
 *
 * @package AbsoluteAddons
 * @var $settings
 *
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="info-box">
	<div class="info-box-content">
		<?php if ( ! empty( $settings['info_box_title'] ) ) { ?>
			<h2 <?php $this->print_render_attribute_string( 'info_box_title' ); ?>><?php absp_render_title( $settings['info_box_title'] ); ?></h2>
		<?php } ?>
		<?php absp_render_content( $settings['info_box_content'] ); ?>
	</div>
	<?php if ( 'true' === $settings['info_box_separator_enable'] ) : ?>
		<div class="info-box-separator-inner">
			<span class="info-box-separator"></span>
		</div>
	<?php endif; ?>
	<div class="info-box-icon-sec">
		<div class="info-box-icon-inner">
			<div class="info-box-icon info-box-icon-left">
				<?php $this->render_box_icon( $settings ); ?>
			</div>
		</div>
		<?php if ( ! empty( $settings['info_box_sub_title'] ) ) { ?>
			<div class="info-box-icon-inner">
				<div class="info-box-icon-right">
					<span <?php $this->print_render_attribute_string( 'info_box_sub_title' ); ?>><?php absp_render_title( $settings['info_box_sub_title'] ); ?></span>
				</div>
			</div>
		<?php } ?>
	</div>
</div>


