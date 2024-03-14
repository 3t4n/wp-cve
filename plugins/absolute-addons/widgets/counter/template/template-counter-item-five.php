<?php
/**
 * Template Style Five for Counter
 *
 * @package AbsoluteAddons
 */

use Elementor\Icons_Manager;

?>
<div class="counter-box">
	<?php if ( ! empty( $counter['counter_icon_five']['value'] ) ) : ?>
		<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ); ?>">
			<?php Icons_Manager::render_icon( $counter['counter_icon_five'], [ 'aria-hidden' => 'true' ] ); ?>
		</div>
	<?php endif; ?>
	<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ); ?>">
		<h2 <?php $this->print_render_attribute_string( 'absp-counterup' ); ?>><?php absp_render_title( $counter['counter_number_five'] ) ?></h2>
	</div>
	<?php if ( ! empty( $counter['counter_suffix_five'] ) ) : ?>
		<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ) ?>">
			<span><?php echo esc_html( $counter['counter_suffix_five'] ) ?></span>
		</div>
	<?php endif; ?>
</div>
<h4 class="count-title"><?php absp_render_title( $counter['counter_title_five'] ) ?></h4>
