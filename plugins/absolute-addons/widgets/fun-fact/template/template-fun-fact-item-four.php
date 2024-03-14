<?php
/**
 * Template Style Four for Fun Fact
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="fun-fact-item">
	<div class="fun-fact-flex-wrapper">
		<div class="fun-fact-flex-inner">
			<?php $this->render_fact_icon( $settings ); ?>
		</div>
		<div class="fun-fact-flex-inner">
			<?php if ( ! empty( $settings['fun_fact_number'] ) ) {?>
			<div class="fun-fact-number-wrapper">
				<span <?php $this->print_render_attribute_string( 'absp-fun-fact' ); ?>><?php echo esc_html( $settings['fun_fact_number'] ) ?></span>
				<span <?php $this->print_render_attribute_string( 'fun_fact_counter_suffix' ); ?>><?php echo esc_html( $settings['fun_fact_counter_suffix'] ) ?></span>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php $this->render_fun_fact_button( $settings ); ?>
</div>
