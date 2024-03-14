<?php
/**
 * Banner style - cs - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cs_banner_theme">
	<h4><?php esc_html_e( 'Theme', 'iubenda' ); ?></h4>
	<div class="scrollable gap-fixer">
		<fieldset class="theme-select d-flex">
			<div class="mr-2">
				<label class="radio-theme radio-theme-dark">
					<input type="radio" name="iubenda_cookie_law_solution[simplified][banner_style]" value="dark" <?php checked( 'dark', iub_array_get( iubenda()->options['cs'], 'simplified.banner_style' ), true ); ?>>
				</label>
				<p class="text-xs text-center"><?php esc_html_e( 'Dark', 'iubenda' ); ?></p>
			</div>
			<div class="mr-2">
				<label class="radio-theme radio-theme-light">
					<input type="radio" name="iubenda_cookie_law_solution[simplified][banner_style]" value="light" <?php checked( 'light', iub_array_get( iubenda()->options['cs'], 'simplified.banner_style' ), true ); ?>>
				</label>
				<p class="text-xs text-center"><?php esc_html_e( 'Light', 'iubenda' ); ?></p>
			</div>
		</fieldset>
	</div>
</div>
