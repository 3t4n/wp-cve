<?php
/**
 * Template Style Two Multi Color Heading
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="multi-color-heading-item">
	<div class="multi-color-heading-flex-wrapper">
		<?php if ( ! empty( $settings['multi_color_heading_sub_title_two'] ) ) { ?>
			<div class="multi-color-heading-flex-inner sub-title-flex-inner">
				<span <?php $this->print_render_attribute_string( 'multi_color_heading_sub_title_two' ); ?>><?php absp_render_title( $settings['multi_color_heading_sub_title_two'] ); ?></span>
			</div>
		<?php } ?>
		<?php if ( ! empty( $settings['multi_color_heading_number'] ) ) { ?>
			<div class="multi-color-heading-flex-inner multi-color-heading-number-wrapper">
				<div class="multi-color-heading-number">
					<span <?php $this->print_render_attribute_string( 'multi_color_heading_number' ); ?>><?php absp_render_title( $settings['multi_color_heading_number'] ); ?></span>
					<span class="multi-color-heading-number-bg"></span>
				</div>
				<span class="multi-color-heading-number-circle-space"></span>
			</div>
		<?php } ?>
		<?php if ( ! empty( $settings['multi_color_heading_title'] ) ) { ?>
			<div class="multi-color-heading-flex-inner">
				<div class="multi-color-heading-title-wrapper">
					<h2 <?php $this->print_render_attribute_string( 'multi_color_heading_title' ); ?>><?php absp_render_title( $settings['multi_color_heading_title'] ); ?></h2>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
