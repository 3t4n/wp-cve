<?php
/**
 * Template Style Six for Call To Action
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c2a-box">
	<div class="c2a-box-inner">
		<div class="c2a-box-content">
			<?php if ( ! empty( $settings['c2a_box_title'] ) ) {?>
			<h2 <?php $this->print_render_attribute_string( 'c2a_box_title' ); ?> ><?php absp_render_title( $settings['c2a_box_title'] ); ?></h2>
			<?php } ?>
			<?php if ( ! empty( $settings['c2a_box_sub_title'] ) ) { ?>
			<span <?php $this->print_render_attribute_string( 'c2a_box_sub_title' ); ?>><?php absp_render_title( $settings['c2a_box_sub_title'] ); ?></span>
			<?php } ?>
			<?php absp_render_content( $settings['c2a_box_content'] ); ?>
			<?php $this->render_c2a_button( $settings ); ?>
		</div>
	</div>
</div>
