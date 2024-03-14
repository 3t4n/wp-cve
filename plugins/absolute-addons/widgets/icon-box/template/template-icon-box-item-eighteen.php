<?php
/**
 * Template Style Eighteen for Icon Box
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="icon-box">
	<?php $this->render_box_icon( $settings, '<div class="icon-box-img">', '</div>' ); ?>
	<div class="icon-box-content">
		<?php $this->render_sub_title( $settings ); ?>
		<?php $this->render_title( $settings ); ?>
	</div>
</div>
