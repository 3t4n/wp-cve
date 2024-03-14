<?php
/**
 * Template Style Twenty One for Info Box
 *
 * @package AbsoluteAddons
 * @var $settings
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="info-box">
	<?php if ( ! empty( $settings['info_box_image'] ) ) { ?>
		<div class="info-box-img">
			<img src="<?php echo esc_url( $settings['info_box_image']['url'] ); ?>" alt=""/>
		</div>
	<?php } ?>
	<div class="info-box-content">
		<?php if ( ! empty( $settings['info_box_title'] ) ) { ?>
			<h2 <?php $this->print_render_attribute_string( 'info_box_title' ); ?> ><?php absp_render_title( $settings['info_box_title'] ); ?></h2>
		<?php } ?>
		<?php absp_render_content( $settings['info_box_content'] ); ?>
	</div>
</div>

