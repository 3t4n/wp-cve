<?php

/**
 * Testimonial Template Style Three
 *
 * @package AbsolutePlugins
 * @version 1.0.0
 * @since 1.0.0
 */
use Elementor\Control_Media;
use Elementor\Icons_Manager;

$uid = wp_unique_id( 'svg' );
?>

<div class="absp-testimonial-item testimonial-style-three">
	<div class="testimonial-top">
		<div class="testimonial-image">
			<img src="<?php echo esc_url( $settings['item_style_one_img']['url'] ); ?>" alt="Image">
		</div>
		<div class="quote">
			<?php if ( $settings['before_icon'] ) : ?>
				<?php Icons_Manager::render_icon( $settings['before_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="testimonial-bottom">
		<div class="testimonial-name">
			<h3 <?php $this->print_render_attribute_string( 'item_style_one_title' ); ?>><?php absp_render_title( $settings['item_style_one_title'] ); ?></h3>
			<?php if ( ! empty( $settings['item_style_one_desig'] ) ) { ?>
				<span <?php $this->print_render_attribute_string( 'item_style_one_desig' ); ?>><?php echo esc_html( $settings['item_style_one_desig'] ); ?></span>
			<?php } ?>
		</div>
		<?php if ( ! empty( $settings['item_style_one_content'] ) ) { ?>
			<div <?php $this->print_render_attribute_string( 'item_style_one_content' ); ?>>
				<?php absp_render_content( $settings['item_style_one_content'] ); ?>
			</div>
		<?php } ?>
	</div>
</div>
