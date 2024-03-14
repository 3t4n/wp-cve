<?php
/**
 * Template Style Ten for Icon Box
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
	<div class="icon-box-inner">
		<?php $this->render_box_icon( $settings, '<div class="icon-box-icon">', '</div>' ); ?>
	</div>
	<div class="icon-box-inner">
		<div class="icon-box-content">
			<?php $this->render_button( $settings ); ?>
			<?php $this->render_title( $settings ); ?>
		</div>
	</div>
</div>
