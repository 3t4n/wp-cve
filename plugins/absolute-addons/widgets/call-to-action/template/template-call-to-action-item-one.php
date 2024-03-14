<?php
/**
 * Template Style One for Call To Action
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c2a-box">
	<div class="c2a-box-inner">
		<div class="c2a-box-icon">
			<?php
			if ( 'svg' === $settings['c2a_box_icons']['library'] ) {
				if ( ! empty( $settings['c2a_box_icons']['value']['id'] ) ) {
					echo wp_get_attachment_image( $settings['c2a_box_icons']['value']['id'], 'full' );

				}else { ?>
					<img src="<?php echo esc_url( $settings['c2a_box_icons']['value']['url'] ); ?>" alt="Placeholder Image">
					<?php
				}
			} else { ?>
				<i class="<?php echo esc_attr( $settings['c2a_box_icons']['value'] ); ?>"></i>
				<?php
			}
			?>
		</div>
	</div>
	<div class="c2a-box-inner">
		<div class="c2a-box-content">
			<?php if ( ! empty( $settings['c2a_box_title'] ) ) {?>
			<h2 <?php $this->print_render_attribute_string( 'c2a_box_title' ); ?> ><?php absp_render_title( $settings['c2a_box_title'] ); ?></h2>
			<?php } ?>
			<span <?php $this->print_render_attribute_string( 'c2a_box_sub_title' ); ?>><?php absp_render_title( $settings['c2a_box_sub_title'] ); ?></span>

			<?php absp_render_content( $settings['c2a_box_content'] ); ?>
		</div>
	</div>
	<div class="c2a-box-inner">
		<div class="c2a-btn-inner">
			<?php $this->render_c2a_button( $settings ); ?>
		</div>
	</div>
</div>
