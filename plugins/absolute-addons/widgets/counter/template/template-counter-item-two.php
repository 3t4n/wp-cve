<?php
/**
 * Template Style Two for Counter
 *
 * @package AbsoluteAddons
 */

use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->add_inline_editing_attributes( 'counter_title', 'basic' );
$this->add_render_attribute( 'counter_title', 'class', 'count-title' );
?>
<div class="counter-box">
	<h2 <?php $this->print_render_attribute_string( 'absp-counterup' ); ?>><?php absp_render_title( $settings['counter_number'] ); ?></h2>
	<?php if ( ! empty( $settings['counter_suffix'] ) ) : ?>
		<span><?php echo esc_html( $settings['counter_suffix'] ); ?></span>
	<?php endif; ?>
	<h4 <?php $this->print_render_attribute_string( 'counter_title' ); ?>><?php absp_render_title( $settings['counter_title'] ); ?></h4>
</div>
<?php if ( ! empty( $settings['counter_link_select'] ) && 'true' === $settings['counter_link_select'] ) : ?>
	<a class="btn btn-primary" href="<?php echo esc_url( $settings['link']['url'] ); ?>">
		<?php echo esc_html( $settings['link_text'] ); ?>
		<?php if ( ! empty( $settings['counter_link_icon']['value'] ) ) : ?>
			<?php Icons_Manager::render_icon( $settings['counter_link_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		<?php endif; ?>
	</a>
<?php endif; ?>
