<?php
/**
 * Template Style Six for Counter
 *
 * @package AbsoluteAddons
 */

use Elementor\Control_Media;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

?>
<div class="counter-box">
	<?php if ( ! empty( $counter['counter_string_number_six'] ) ) : ?>
		<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ); ?>">
			<span><?php echo esc_html( $counter['counter_string_number_six'] ); ?></span>
		</div>
	<?php endif; ?>
	<h4 class="count-title"><?php absp_render_title( $counter['counter_title_six'] ); ?></h4>
</div>
<?php if ( ! empty( $counter['counter_image_six'] ) ) : ?>
	<div class="counter-img">
		<img src="<?php echo esc_url( $counter['counter_image_six']['url'] ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $counter['counter_image_six'] ) ); ?>">
	</div>
<?php endif; ?>
<div class="counter-number-flex">
	<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ) ?>">
		<h2 <?php $this->print_render_attribute_string( 'absp-counterup' ); ?>><?php absp_render_title( $counter['counter_number_six'] ) ?></h2>
	</div>
	<?php if ( ! empty( $counter['counter_suffix_six'] ) ) : ?>
		<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ) ?>">
			<span><?php echo esc_html( $counter['counter_suffix_six'] ) ?></span>
		</div>
	<?php endif; ?>
</div>
