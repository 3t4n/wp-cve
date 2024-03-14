<?php
/**
 * Template Style Nine for Counter
 *
 * @package AbsoluteAddons
 */

use Elementor\Icons_Manager;

?>
<h4 class="count-title"><?php absp_render_title( $counter['counter_title_nine'] ); ?></h4>
<div class="counter-box">
	<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ); ?>">
		<h2 <?php $this->print_render_attribute_string( 'absp-counterup' ); ?>><?php absp_render_title( $counter['counter_number_nine'] ); ?></h2>
	</div>
	<?php if ( ! empty( $counter['counter_suffix_nine'] ) ) : ?>
		<div class="elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ); ?>">
			<span><?php echo esc_html( $counter['counter_suffix_nine'] ); ?></span>
		</div>
	<?php endif; ?>
</div>
<?php if ( ! empty( $counter['counter_icon_nine']['value'] ) ) : ?>
	<div class="icon-area  elementor-repeater-item-<?php echo esc_attr( $counter['_id'] ); ?>">
		<?php Icons_Manager::render_icon( $counter['counter_icon_nine'], [ 'aria-hidden' => 'true' ] ); ?>
	</div>
<?php endif; ?>
