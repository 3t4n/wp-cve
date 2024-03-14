<?php
/**
 * Template Style Twelve for Icon Box
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>

<div class="icon-box">
	<div class="icon-box-content">
		<?php $this->render_box_icon( $settings, '<div class="icon-box-icon">', '</div>' ); ?>
		<?php $this->render_title( $settings ); ?>
	</div>
	<?php $this->render_separator( $settings ); ?>
	<?php $this->render_sub_title( $settings, '<div class="icon-box-icon-sec">', '</div>' ); ?>
</div>
