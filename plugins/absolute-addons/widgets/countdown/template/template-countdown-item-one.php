<?php
/**
 * Template Style One Count Down
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 */
?>
<div class="absp-countdown-flex-wrapper">
	<?php $this->render_digits( $settings, false , $this->get_id() ); ?>
</div>

