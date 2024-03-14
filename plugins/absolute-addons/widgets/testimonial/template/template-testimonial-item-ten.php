<?php

/**
 * Testimonial Template Style Ten
 *
 * @package AbsolutePlugins
 * @version 1.0.0
 * @since 1.0.0
 */

use Elementor\Control_Media;

$this->add_inline_editing_attributes( 'testimonial_style_ten_title', 'basic' );
$this->add_render_attribute( 'testimonial_style_ten_title', 'class', 'testimonial-title' );

$this->add_inline_editing_attributes( 'testimonial_style_ten_designation', 'basic' );
$this->add_render_attribute( 'testimonial_style_ten_designation', 'class', 'testimonial-desig' );

$this->add_inline_editing_attributes( 'testimonial_style_ten_content', 'basic' );
$this->add_render_attribute( 'testimonial_style_ten_content', 'class', 'testimonial-content' );

?>

<div class="testimonial-slider-ten">
	<div class="testimonial-item image-position-<?php echo esc_attr( $settings['item_style_ten_position'] ); ?>">
		<div class="testimonial-image">
			<img src="<?php echo esc_url( $settings['testimonial_ten_image']['url'] ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $settings['testimonial_ten_image'] ) ); ?>">
		</div>
		<?php if ( ! empty( $settings['testimonial_style_ten_content'] ) ) { ?>
			<div <?php $this->print_render_attribute_string( 'testimonial_style_ten_content' ); ?>>
				<?php absp_render_content( $settings['testimonial_style_ten_content'] ); ?>
			</div>
		<?php } ?>
		<figcaption class="testimonial-name">
			<h4 <?php $this->print_render_attribute_string( 'testimonial_style_ten_title' ); ?>><?php absp_render_title( $settings['testimonial_style_ten_title'] ); ?></h4>
			<?php if ( ! empty( $settings['testimonial_style_ten_designation'] ) ) { ?>
				<span <?php $this->print_render_attribute_string( 'testimonial_style_ten_designation' ); ?>><?php absp_render_content( $settings['testimonial_style_ten_designation'] ); ?></span>
			<?php } ?>
		</figcaption>
	</div>
</div>
