<?php
/**
 * Template Style One for Counter
 *
 * @package AbsoluteAddons
 * @version 1.0.0
 */

use Elementor\Icons_Manager;
?>
<h4 class="count-title">
	<?php if ( ! empty( $settings['counter_icon']['value'] ) ) : ?>
		<?php Icons_Manager::render_icon( $settings['counter_icon'], [ 'aria-hidden' => 'true' ] );?>
	<?php endif;?>
	<?php absp_render_title( $settings['counter_title'] ); ?>
</h4>
<div class="counter-number-box">
	<h2 <?php $this->print_render_attribute_string( 'absp-counterup' ); ?>><?php absp_render_title( $settings['counter_number'] ) ?></h2>
	<?php if ( ! empty( $settings['counter_suffix'] ) ) : ?>
		<span><?php echo esc_html( $settings['counter_suffix'] ); ?></span>
	<?php endif;?>
</div>
