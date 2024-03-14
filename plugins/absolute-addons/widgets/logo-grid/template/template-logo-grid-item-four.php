<?php
/**
 * Template Style Four for Logo Grid
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;

?>
<div class="image-grid">
	<?php foreach ( $settings['logo-grid-gallery'] as $logo ) { ?>
		<div class="absp-logo-grid-item img-wrapper"><img src="<?php echo esc_url( $logo['url'] ) ?>"></div>
	<?php } ?>
</div>
