<?php
/*
 * Settings page tab.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Settingstab for boardthemes and piecethemes.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_page_settingstab_themes() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	} ?>

	<input type="hidden" id="cs_tab" name="cs_tab" value="cs_tab_themes" />
	<?php
	settings_fields( 'chessgame_shizzle_options' );
	do_settings_sections( 'chessgame_shizzle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'chessgame_shizzle_page_settingstab_themes' );
	echo '<input type="hidden" id="chessgame_shizzle_page_settingstab_themes" name="chessgame_shizzle_page_settingstab_themes" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr valign="top">
			<th scope="row"><label for="cs_boardtheme"><?php esc_html_e('Boardtheme', 'chessgame-shizzle'); ?></label></th>
			<td>
				<select name="cs_boardtheme" id="cs_boardtheme">
				<?php
				$boardthemes = chessgame_shizzle_get_boardthemes();
				$boardtheme = chessgame_shizzle_get_boardtheme();
				foreach ( $boardthemes as $theme ) {
					echo '<option value="' . esc_attr( $theme ) . '"';
					if ( $theme === $boardtheme ) {
						echo ' selected="selected"';
					}
					echo '>' . esc_html( $theme ) . '</option>';
				}
				?>
				</select>
				<br />
				<span class="setting-description"><?php esc_html_e('The theme for the chessboard.', 'chessgame-shizzle'); ?></span>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cs_piecetheme"><?php esc_html_e('Piecetheme', 'chessgame-shizzle'); ?></label></th>
			<td>
				<select name="cs_piecetheme" id="cs_piecetheme">
				<?php
				$piecethemes = chessgame_shizzle_get_piecethemes();
				$piecetheme = chessgame_shizzle_get_piecetheme();
				$piecetheme_url = chessgame_shizzle_get_piecetheme_url();
				foreach ( $piecethemes as $theme ) {
					echo '<option value="' . esc_attr( $theme['name'] ) . '"';
					if ( $theme['name'] === $piecetheme ) {
						echo ' selected="selected"';
					}
					echo '>' . esc_html( $theme['name'] ) . '</option>';
				}
				?>
				</select>
				<br />
				<span class="setting-description"><?php esc_html_e('The theme for the chess pieces.', 'chessgame-shizzle'); ?></span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="chessgame_shizzle_settings_admin" id="chessgame_shizzle_settings_admin" class="button-primary" value="<?php esc_attr_e('Save settings', 'chessgame-shizzle'); ?>" />
				</p>
			</th>
		</tr>

		<tr>
			<td colspan="2">
				<?php
				$r = new WP_Query( array(
					'posts_per_page'         => 1,
					'no_found_rows'          => true,
					'post_status'            => 'publish',
					'post_type'              => 'cs_chessgame',
					'ignore_sticky_posts'    => true,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				) );

				if ($r->have_posts()) {
					while ( $r->have_posts() ) {

						$r->the_post();

						$permalink = get_permalink( get_the_ID() );
						$raquo = '<a href="' . esc_attr( $permalink ) . '" title="' . esc_attr__('Click here to get to the chessgame', 'chessgame-shizzle') . '">&raquo;</a>';
						echo '<h3>' . esc_html__('Preview:', 'chessgame-shizzle') . '<br />' . esc_html( get_the_title() ) . ' ' . $raquo . '</h3>';

						echo chessgame_shizzle_get_iframe( get_the_ID() );

					}
					// Reset the global $the_post as this query will have stomped on it
					wp_reset_postdata();

				} ?>
			</td>
		</tr>

		</tbody>
	</table>

	<?php
}
