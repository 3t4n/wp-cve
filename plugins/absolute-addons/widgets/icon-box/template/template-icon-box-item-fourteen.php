<?php
/**
 * Template Style Fourteen for Icon Box
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<?php $this->render_separator( $settings ); ?>
<div class="icon-box">
	<?php $this->render_box_icon( $settings, '<div class="icon-box-icon">', '</div>' ); ?>
	<div class="icon-box-content-sec">
		<div class="icon-box-inner">
			<div class="icon-box-label-text">
				<?php if ( ! empty( $settings['icon_box_label_text_fourteen'] ) ) { ?>
					<span <?php $this->print_render_attribute_string( 'icon_box_label_text_fourteen' ); ?> ><?php absp_render_title( $settings['icon_box_label_text_fourteen'] ); ?></span>
				<?php } ?>
			</div>
		</div>
		<?php $this->render_title( $settings, '<div class="icon-box-inner"><div class="icon-box-content">', '</div></div>' ); ?>
	</div>
</div>
