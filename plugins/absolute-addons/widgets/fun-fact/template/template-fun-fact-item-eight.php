<?php
/**
 * Template Style Eight for Fun Fact
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="fun-fact-item">
	<?php $this->render_fact_icon( $settings ); ?>
	<?php if ( ! empty( $settings['fun_fact_number'] ) ) { ?>
		<div class="fun-fact-number-wrapper">
			<span <?php $this->print_render_attribute_string( 'absp-fun-fact' ); ?>><?php echo esc_html( $settings['fun_fact_number'] ) ?></span>
			<span <?php $this->print_render_attribute_string( 'fun_fact_counter_suffix' ); ?>><?php echo esc_html( $settings['fun_fact_counter_suffix'] ) ?></span>
		</div>
	<?php } ?>
	<?php if ( ! empty( $settings['fun_fact_title'] ) ) { ?>
		<h2 <?php $this->print_render_attribute_string( 'fun_fact_title' ); ?>><?php absp_render_title( $settings['fun_fact_title'] ); ?></h2>
	<?php } ?>
</div>
