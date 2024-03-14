<?php
/**
 * Button style - pp - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h4><?php esc_html_e( 'Button style', 'iubenda' ); ?></h4>
<div class="scrollable gap-fixer">
	<div class="button-style mb-3 d-flex">
		<div class="m-1 mr-2">
			<label class="radio-btn-style radio-btn-style-light">
				<input type="radio" class="update-button-style" name="iubenda_privacy_policy_solution[button_style]" value="white" <?php checked( 'white', iub_array_get( iubenda()->options['pp'], 'button_style' ) ); ?>>
				<div>
					<div class="btn-fake"></div>
				</div>
				<p class="text-xs text-center"><?php esc_html_e( 'Light', 'iubenda' ); ?></p>
			</label>
		</div>
		<div class="m-1 mr-2">
			<label class="radio-btn-style radio-btn-style-dark">
				<input type="radio" class="update-button-style" name="iubenda_privacy_policy_solution[button_style]" value="black" <?php checked( 'black', iub_array_get( iubenda()->options['pp'], 'button_style' ) ); ?>>
				<div>
					<div class="btn-fake"></div>
				</div>
				<p class="text-xs text-center"><?php esc_html_e( 'Dark', 'iubenda' ); ?></p>
			</label>
		</div>
	</div>
</div>
