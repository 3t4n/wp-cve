<?php
/*
 * Settings page tab.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Settingstab for antispam.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_page_settingstab_antispam() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	} ?>

	<input type="hidden" id="cs_tab" name="cs_tab" value="cs_tab_antispam" />
	<?php
	settings_fields( 'chessgame_shizzle_options' );
	do_settings_sections( 'chessgame_shizzle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'chessgame_shizzle_page_settingstab_antispam' );
	echo '<input type="hidden" id="chessgame_shizzle_page_settingstab_antispam" name="chessgame_shizzle_page_settingstab_antispam" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr valign="top">
			<th scope="row"><label for="chessgame_shizzle_nonce"><?php esc_html_e('Nonce', 'chessgame-shizzle'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'chessgame_shizzle-nonce', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="chessgame_shizzle_nonce" id="chessgame_shizzle_nonce">
				<label for="chessgame_shizzle_nonce">
					<?php esc_html_e('Use Nonce.', 'chessgame-shizzle'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add a Nonce to the form. It is a way to check for a human user. If it does not validate, the entry will be marked as spam.', 'chessgame-shizzle');
					echo '<br />';
					$link_wp = '<a href="https://codex.wordpress.org/Wordpress_Nonce_Implementation" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'If you want to know more about what a Nonce is and how it works, please read about it on the %sWordPress Codex%s.', 'chessgame-shizzle' ), $link_wp, '</a>' );
					echo '<br />';
					esc_html_e('If your website uses caching, it is possible that you get false-positives in your spamfolder. If this is the case, you could either disable the Nonce, or disable caching for the guestbook page.', 'chessgame-shizzle');
					?>
				</span>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="chessgame_shizzle_honeypot"><?php esc_html_e('Honeypot', 'chessgame-shizzle'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'chessgame_shizzle-honeypot', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="chessgame_shizzle_honeypot" id="chessgame_shizzle_honeypot">
				<label for="chessgame_shizzle_honeypot">
					<?php esc_html_e('Use Honeypot.', 'chessgame-shizzle'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('This will add a non-visible input field to the form. It should not get filled in, but when it is, the entry will be marked as spam.', 'chessgame-shizzle'); ?>
				</span>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="chessgame_shizzle_timeout"><?php esc_html_e('Form Timeout', 'chessgame-shizzle'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'chessgame_shizzle-timeout', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="chessgame_shizzle_timeout" id="chessgame_shizzle_timeout">
				<label for="chessgame_shizzle_timeout">
					<?php esc_html_e('Set timeout for form submit.', 'chessgame-shizzle'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will enable a timer function for the form. If the form is submitted faster than the timeout the entry will be marked as spam.', 'chessgame-shizzle');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="chessgame_shizzle_settings_admin" id="chessgame_shizzle_settings_admin" class="button-primary" value="<?php esc_attr_e('Save settings', 'chessgame-shizzle'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
