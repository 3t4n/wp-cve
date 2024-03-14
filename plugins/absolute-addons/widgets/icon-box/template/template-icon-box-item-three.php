<?php
/**
 * Template Style Three Icon Box Item
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>

<div class="icon-box">
	<?php $this->render_box_icon( $settings, '<div class="icon-box-icon">', '</div>' ); ?>
	<?php $this->render_title( $settings, '<div class="icon-box-content">', '</div>' ); ?>
</div>
