<?php
/**
 * Template Style Four Icon Box Item
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="icon-box">
	<div class="icon-box-inner-wrapper">
		<?php $this->render_box_icon( $settings, '<div class="icon-box-inner"><div class="icon-box-icon-left">', '</div></div>' ); ?>
		<?php $this->render_title( $settings, '<div class="icon-box-inner"><div class="icon-box-icon-right">', '</div></div>' ); ?>
	</div>
	<?php $this->render_button( $settings, '<div class="icon-box-content">', '</div>' ); ?>
</div>
