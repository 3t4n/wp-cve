<?php
/**
 * Template Style One Icon Box Item
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
	<div class="icon-box-content">
		<?php
		$this->render_title( $settings );
		$this->render_button( $settings );
		?>
	</div>
</div>
