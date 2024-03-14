<?php

/**
 * Testimonial Style one
 *
 * @package AbsolutePlugins
 * @version 1.0.0
 * @since 1.0.0
 */

use Elementor\Control_Media;
use Elementor\Icons_Manager;
?>
<div class="testimonial-style-one">
	<?php if ( 'yes' === $settings['item_style_one_after_before_icon_select'] ) : ?>
		<div class="<?php echo esc_attr( $settings['item_style_one_quote_position'] ); ?>">
			<?php if ( $settings['before_icon'] ) : ?>
				<?php Icons_Manager::render_icon( $settings['before_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="testimonial-top-wrapper">
		<div class="testimonial-img">
			<img src="<?php echo esc_url( $settings['item_style_one_img']['url'] ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $settings['item_style_one_img'] ) ); ?>">
		</div>
		<div class="testimonial-name">
			<figcaption class="testimonial-name">
				<h4 <?php $this->print_render_attribute_string( 'item_style_one_title' ); ?>><?php absp_render_title( $settings['item_style_one_title'] ); ?></h4>
				<?php if ( ! empty( $settings['item_style_one_desig'] ) ) { ?>
					<span <?php $this->print_render_attribute_string( 'item_style_one_desig' ); ?>><?php echo esc_html( $settings['item_style_one_desig'] ); ?></span>
				<?php } ?>
			</figcaption>
		</div>
	</div>
	<div class="testimonial-bottom-content">
		<div <?php $this->print_render_attribute_string( 'item_style_one_content' ); ?>>
			<?php if ( ! empty( $settings['item_style_one_content'] ) ) { ?>
				<?php absp_render_content( $settings['item_style_one_content'] ); ?>
			<?php } ?>
		</div>
	</div>
</div>
