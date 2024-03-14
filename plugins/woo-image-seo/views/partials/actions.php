<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="form__actions">
	<input
		type="submit"
		value="<?php _e( 'Save Settings', 'woo-image-seo' ) ?>"
	>

	<input
		type="button"
		value="<?php _e( 'Reset to Default', 'woo-image-seo' ) ?>"
		id="reset-settings"
		data-confirm="<?php _e( 'Reset plugin settings?', 'woo-image-seo' ) ?>"
	>
</div><!-- /.form__actions -->