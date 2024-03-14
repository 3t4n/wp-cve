<?php
/**
 * Template Style One Multi Color Heading
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="multi-color-heading-item">
	<div class="multi-color-heading-flex-wrapper">
		<?php if ( ! empty( $settings['multi_color_heading_sub_title_one'] ) ) { ?>
			<div class="multi-color-heading-flex-inner sub-title-flex-inner">
				<span <?php $this->print_render_attribute_string( 'multi_color_heading_sub_title_one' ); ?>><?php absp_render_title( $settings['multi_color_heading_sub_title_one'] ); ?></span>
				<span class="multi-color-heading-sub-title-border"></span>
			</div>
		<?php } ?>
		<?php if ( ! empty( $settings['multi_color_heading_number'] ) ) { ?>
			<div class="multi-color-heading-flex-inner">
				<div class=" multi-color-heading-number" style="background-image: linear-gradient(to bottom, <?php echo esc_attr( $settings['multi_color_heading_number_gradient_color_1'] ); ?> 0%, <?php echo esc_attr( $settings['multi_color_heading_number_gradient_color_2'] ); ?> 100%);">
					<span <?php $this->print_render_attribute_string( 'multi_color_heading_number' ); ?>><?php absp_render_title( $settings['multi_color_heading_number'] ); ?></span>
				</div>
			</div>
		<?php } ?>
		<?php if ( ! empty( $settings['multi_color_heading_title'] ) ) { ?>
			<div class="multi-color-heading-flex-inner">
				<div class="multi-color-heading-title-wrapper">
				<span class="multi-color-heading-icon" style="background-image: linear-gradient(to bottom, <?php echo esc_attr( $settings['multi_color_heading_icon_gradient_color_1'] ); ?> 0%, <?php echo esc_attr( $settings['multi_color_heading_icon_gradient_color_2'] ); ?> 100%);">
				<?php
				if ( 'svg' === $settings['multi_color_heading_icon']['library'] ) {
					if ( ! empty( $settings['multi_color_heading_icon']['value']['id'] ) ) {
						echo '<div class="multi-color-heading-svg-icon">';
						echo wp_get_attachment_image( $settings['multi_color_heading_icon']['value']['id'] );
						echo '</div>';
					} else { ?>
						<i class="<?php echo esc_attr( $settings['multi_color_heading_icon']['value'] ); ?>"></i>
						<?php
					}
				} else { ?>
					<i class="<?php echo esc_attr( $settings['multi_color_heading_icon']['value'] ); ?>"></i>
					<?php
				}
				?>
				</span>
					<h2 <?php $this->print_render_attribute_string( 'multi_color_heading_title' ); ?>><?php absp_render_title( $settings['multi_color_heading_title'] ); ?></h2>
				</div>
				<span class="multi-color-heading-title-border"></span>
			</div>
		<?php } ?>
	</div>
</div>
