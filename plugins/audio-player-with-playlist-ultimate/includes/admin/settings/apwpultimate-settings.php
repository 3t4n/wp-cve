<?php
/**
 * Settings Page
 *
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wrap apwpultimate-settings">

<h2><?php esc_html_e( 'WP Audio Playlist Settings', 'audio-player-with-playlist-ultimate' ); ?></h2><br />

<?php
// Success message
// if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) {
// 	echo '<div id="message" class="updated notice notice-success is-dismissible">
// 			<p><strong>'.esc_html__("Your changes saved successfully.", "audio-player-with-playlist-ultimate").'</strong></p>
// 		</div>';
// }
?>

<?php
	// Messages
	if( ! empty( $_POST['apwpultimate_reset_settings'] ) ) {

		// Reset message
		echo '<div id="message" class="updated notice notice-success is-dismissible">
				<p><strong>' . esc_html__( 'All settings reset successfully.', 'audio-player-with-playlist-ultimate') . '</strong></p>
			</div>';

	} elseif( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {

		// Setting Update Message
		echo '<div id="message" class="updated notice notice-success is-dismissible">
				<p><strong>'.esc_html__("Your changes saved successfully.", "audio-player-with-playlist-ultimate").'</strong></p>
			  </div>';
	}
	?>

	<!-- Plugin reset settings form -->
	<form action="" method="post" id="apwpultimate-reset-sett-form" class="apwpultimate-right apwpultimate-reset-sett-form">
		<input type="submit" class="button button-primary apwpultimate-confirm apwpultimate-btn apwpultimate-reset-sett apwpultimate-resett-sett-btn apwpultimate-reset-sett" name="apwpultimate_reset_settings" id="apwpultimate-reset-sett" value="<?php esc_attr_e( 'Reset All Settings', 'audio-player-with-playlist-ultimate' ); ?>" />
		<?php wp_nonce_field( 'apwpultimate_reset_settings', 'apwpultimate_reset_sett_nonce' ); ?>
	</form>

<form action="options.php" method="POST" id="apwpultimate-settings-form" class="apwpultimate-settings-form">

	<?php
		settings_fields( 'apwpultimate_ultimate_plugin_options' );
		global $apwpultimate_ultimate_options;
	?>

	<div class="textright apwpultimate-clearfix">
		<input type="submit" id="apwpultimate-settings-submit" name="apwpultimate_settings_submit" class="button button-primary right apwpultimate-btn apwpultimate-sett-submit apwpultimate-sett-submit" value="<?php esc_html_e('Save Changes','audio-player-with-playlist-ultimate'); ?>" />
	</div>

	<!-- Tooltip Settings Starts -->
	<div id="apwpultimate-tooltip-sett" class="post-box-container apwpultimate-tooltip-sett">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox">

					<div class="postbox-header">
						<h2 class="hndle">
							<span><?php esc_html_e( 'Playlist Settings', 'audio-player-with-playlist-ultimate' ); ?></span>
						</h2>
					</div>

					<!-- Settings box title -->
					<div class="inside">

						<table class="form-table apwpultimate-tooltip-sett-tbl">
							<tbody>
								<!-- audio_title_font_size -->
								<tr valign="top">
									<th scope="row">
										<label for="apwpultimate-audio-title-size"><?php esc_html_e('Title Font Size', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td class="row-meta">
										<input type="number" name="apwpultimate_ultimate_options[audio_title_font_size]" id="apwpultimate-audio-title-size" value="<?php echo esc_attr(apwpultimate_ultimate_get_option('audio_title_font_size')); ?>" class="regular-text" placeholder="<?php esc_html_e('30', 'audio-player-with-playlist-ultimate'); ?>" /> px <br/>
										<span class="description"><?php esc_html_e('Enter title font size in PX', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>

								<!-- playlist_font_size -->
								<tr valign="top">
									<th scope="row">
										<label for="apwpultimate-audio-playlist-size"><?php esc_html_e('Playlist Font Size', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td class="row-meta">
										<input type="number" name="apwpultimate_ultimate_options[playlist_font_size]" id="apwpultimate-audio-playlist-size" value="<?php echo esc_attr( apwpultimate_ultimate_get_option('playlist_font_size') ); ?>" class="regular-text" placeholder="<?php esc_html_e('18', 'audio-player-with-playlist-ultimate'); ?>" /> px <br/>
										<span class="description"><?php esc_html_e('Enter playlist font size in PX', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>

								<!-- Audio Player Color Setting -->
								<tr valign="top" style="border-bottom:1px solid #ddd;">
									<th scope="row" colspan="2"><h3 style="padding-bottom:0px;margin-bottom:0px;"><?php esc_html_e('Audio Player Color Setting', 'audio-player-with-playlist-ultimate'); ?></h3></th>
								</tr>

								<!-- Title Color -->
								<tr>
									<th scope="row">
											<label for="apwpultimate-title-color"><?php esc_html_e('Title Color', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td>
										<input type="text" name="apwpultimate_ultimate_options[audio_title_font_color]" id="apwpultimate-title-color" value="<?php echo esc_attr( apwpultimate_ultimate_get_option('audio_title_font_color') ); ?>" class="apwpultimate-color-box" /><br/>
										<span class="description"><?php esc_html_e('Select title color.', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>

								<!-- Playlist Font Color -->
								<tr>
									<th scope="row">
											<label for="apwpultimate-playlist-font-color"><?php esc_html_e('Playlist Font Color', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td>
										<input type="text" name="apwpultimate_ultimate_options[playlist_font_color]" id="apwpultimate-playlist-font-color" value="<?php echo esc_attr( apwpultimate_ultimate_get_option('playlist_font_color') ); ?>" class="apwpultimate-color-box" /><br/>
										<span class="description"><?php esc_html_e('Select playlist font color.', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>

								<!-- Title Background Color -->
								<tr>
									<th scope="row">
											<label for="apwpultimate-title-bgcolor"><?php esc_html_e('Title Background Color', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td>
										<input type="text" name="apwpultimate_ultimate_options[title_bg_color]" id="apwpultimate-title-bgcolor" value="<?php echo esc_attr( apwpultimate_ultimate_get_option('title_bg_color') ); ?>" class="apwpultimate-color-box" /><br/>
										<span class="description"><?php esc_html_e('Select banner overlay background color.', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>

								<!-- Playlist Background Color -->
								<tr>
									<th scope="row">
											<label for="apwpultimate-laylist-bgcolor"><?php esc_html_e('Playlist Background Color', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td>
										<input type="text" name="apwpultimate_ultimate_options[playlist_bg_color]" id="apwpultimate-laylist-bgcolor" value="<?php echo esc_attr( apwpultimate_ultimate_get_option('playlist_bg_color') ); ?>" class="apwpultimate-color-box" /><br/>
										<span class="description"><?php esc_html_e('Select playlist background color.', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>

								<!-- Select theme color Color -->
								<tr>
									<th scope="row">
											<label for="apwpultimate-theme-color"><?php esc_html_e('Player Theme Color', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td>
										<input type="text" name="apwpultimate_ultimate_options[theme_color]" id="apwpultimate-theme-color" value="<?php echo esc_attr( apwpultimate_ultimate_get_option('theme_color') ); ?>" class="apwpultimate-color-box" /><br/>
										<span class="description"><?php esc_html_e('Select player theme Color.', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>
								<tr>
									<td colspan="2" valign="top" scope="row">
										<input type="submit" id="apwpultimate-settings-submit" name="apwpultimate_settings_submit" class="button button-primary right" value="<?php esc_html_e('Save Changes','audio-player-with-playlist-ultimate'); ?>" />
									</td>
								</tr>
							</tbody>
						 </table>
					</div><!-- .inside -->
				</div><!-- .postbox -->

				<div id="custom-css" class="postbox">

					<div class="postbox-header">

						<!-- Settings box title -->
						<h2 class="hndle">
							<span><?php esc_html_e( 'Custom CSS Settings', 'audio-player-with-playlist-ultimate' ); ?></span>
						</h2>
						</div>
						<div class="inside">
						
						<table class="form-table apwpultimate-custom-css-sett-tbl">
							<tbody>
								<tr>
									<th scope="row">
										<label for="apwpultimate-custom-css"><?php esc_html_e('Custom CSS', 'audio-player-with-playlist-ultimate'); ?></label>
									</th>
									<td>
										<textarea name="apwpultimate_ultimate_options[custom_css]" class="large-text apwpultimate-code-editor apwpultimate-custom-css" id="apwpultimate-custom-css" rows="15"><?php echo esc_textarea(apwpultimate_ultimate_get_option('custom_css')); ?></textarea>
										<span class="description"><?php esc_html_e('Enter custom CSS to override plugin CSS.', 'audio-player-with-playlist-ultimate'); ?></span>
									</td>
								</tr>
								<tr>
									<td colspan="2" valign="top" scope="row">
										<input type="submit" id="apwpultimate-settings-submit" name="apwpultimate_settings_submit" class="button button-primary right" value="<?php esc_html_e('Save Changes','audio-player-with-playlist-ultimate'); ?>" />
									</td>
								</tr>
							</tbody>
						 </table>

					</div><!-- .inside -->
				</div><!-- #custom-css -->

			</div><!-- .meta-box-sortables ui-sortable -->
		</div><!-- .metabox-holder -->
	</div><!-- #apwpultimate-tooltip-sett -->
	<!-- Tooltip Settings Ends -->

</form><!-- end .apwpultimate-settings-form -->

</div><!-- end .apwpultimate-settings -->
