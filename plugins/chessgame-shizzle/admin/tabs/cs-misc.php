<?php
/*
 * Settings page tab.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Settingstab for misc options.
 *
 * @since 1.0.8
 */
function chessgame_shizzle_page_settingstab_misc() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	} ?>

	<input type="hidden" id="cs_tab" name="cs_tab" value="cs_tab_misc" />
	<?php
	settings_fields( 'chessgame_shizzle_options' );
	do_settings_sections( 'chessgame_shizzle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'chessgame_shizzle_page_settingstab_misc' );
	echo '<input type="hidden" id="chessgame_shizzle_page_settingstab_misc" name="chessgame_shizzle_page_settingstab_misc" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr valign="top">
			<th scope="row"><label for="chessgame_shizzle_simple_list_search"><?php esc_html_e('Search', 'chessgame-shizzle'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'chessgame_shizzle-simple-list-search', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="chessgame_shizzle_simple_list_search" id="chessgame_shizzle_simple_list_search">
				<label for="chessgame_shizzle_simple_list_search">
					<?php esc_html_e('Add search option to shortcode for simple list.', 'chessgame-shizzle'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add a search option to the shortcode of simple list for searching chessgames inside that list.', 'chessgame-shizzle');
					?>
				</span>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="chessgame_shizzle_rss"><?php esc_html_e('RSS Feed', 'chessgame-shizzle'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'chessgame_shizzle-rss', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="chessgame_shizzle_rss" id="chessgame_shizzle_rss">
				<label for="chessgame_shizzle_rss">
					<?php esc_html_e('Add games to RSS Feed.', 'chessgame-shizzle'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add the chessgames to the main RSS Feed.', 'chessgame-shizzle');
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
