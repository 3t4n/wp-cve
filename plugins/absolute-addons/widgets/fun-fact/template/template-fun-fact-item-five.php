<?php
/**
 * Template Style Five for Fun Fact
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="fun-fact-item">
	<div class="fun-fact-flex-wrapper <?php if ( 'yes' === $settings['content_align'] ) { ?> fun-fact-flex-wrapper-right-rtl<?php } ?>">
		<div class="fun-fact-flex-inner">
			<?php $this->render_fact_icon( $settings ); ?>
		</div>
		<div class="fun-fact-flex-inner">
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
	</div>
</div>

<?php if ( 'true' === $settings['fun_fact_separator_enable'] ) : ?>
	<span class="fun-fact-separate <?php if ( 'enable' === $settings['fun_fact_separator_right_enable'] ) { ?> fun-fact-separate-right<?php } ?> <?php if ( 'enable' === $settings['fun_fact_separator_bottom_enable'] ) { ?> fun-fact-separate-bottom<?php } ?>"></span>
<?php endif; ?>

