<?php
if ( ! defined( 'EDDMP_PLUGIN_URL' ) ) {
	echo 'Direct access not allowed.';
	exit; }

$ffmpeg_system_path = defined( 'PHP_OS' ) && 'linux' == strtolower( PHP_OS ) && function_exists( 'shell_exec' ) ? @shell_exec( 'which ffmpeg' ) : '';

// include resources
wp_enqueue_style( 'eddmp-admin-style', plugin_dir_url( __FILE__ ) . '../css/style.admin.css', array(), EDDMP_PLUGIN_VERSION );

$ios_controls                   = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_ios_controls', false );
$troubleshoot_default_extension = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_default_extension', false );
$troubleshoot_onload            = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_onload', false );
$include_main_player_hook       = trim( $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_main_player_hook', '' ) );
$include_all_players_hook       = trim( $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_all_players_hook', '' ) );

$enable_player   = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_enable_player', false );
$show_in         = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_show_in', 'all' );
$players_in_cart = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_players_in_cart', false );
$player_style    = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_player_layout', EDDMP_DEFAULT_PLAYER_LAYOUT );
$volume          = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_player_volume', EDDMP_DEFAULT_PLAYER_VOLUME );
$player_controls = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_player_controls', EDDMP_DEFAULT_PLAYER_CONTROLS );
$player_title    = intval( $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_player_title', 1 ) );
$preload         = $GLOBALS['EDDMusicPlayer']->get_global_attr(
	'_eddmp_preload',
	// This option is only for compatibility with versions previous to 1.0.28
					$GLOBALS['EDDMusicPlayer']->get_global_attr( 'preload', 'none' )
);
$play_simultaneously = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_play_simultaneously', 0 );
$play_all            = $GLOBALS['EDDMusicPlayer']->get_global_attr(
	'_eddmp_play_all',
	// This option is only for compatibility with versions previous to 1.0.28
					$GLOBALS['EDDMusicPlayer']->get_global_attr( 'play_all', 0 )
);
$loop            	   = intval( $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_loop', 0 ) );
$analytics_integration = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_analytics_integration', 'ua' );
$analytics_property    = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_analytics_property', '' );
$analytics_api_secret  = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_analytics_api_secret', '' );
$registered_only       = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_registered_only', 0 );
$fade_out              = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_fade_out', 1 );
$apply_to_all_players  = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_apply_to_all_players', 0 );
?>
<h1><?php esc_html_e( 'Music Player for Easy Digital Downloads - Global Settings', 'music-player-for-easy-digital-downloads' ); ?></h1>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="eddmp_nonce" value="<?php echo esc_attr( wp_create_nonce( 'eddmp_updating_plugin_settings' ) ); ?>" />
<table class="widefat" style="border-left:0;border-right:0;border-bottom:0;padding-bottom:0;">
	<tr>
		<td>
			<div style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">
			<?php
			_e( '<p>The player uses the audio files associated to the download. If you want protecting the audio files for selling, tick the checkbox: <b>"Protect the file"</b>, in whose case the plugin will create a truncated version of the audio files for selling to be used for demo. The size of audio files for demo is based on the number entered through the attribute: <b>"Percent of audio used for protected playbacks"</b>.</p><p><b>Protecting the files prevents that malicious users can access to the original audio files without pay for them.</b></p><p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
			_e( '<p>For testing the professional features, visit the online demo:</p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
			print '<p>' . esc_html__( 'Public website', 'music-player-for-easy-digital-downloads' ) . ': <a href="https://demos.dwbooster.com/music-player-for-easy-digital-downloads" target="_blank">Music Player for Easy Digital Downloads</a><br>' . esc_html__( 'WordPress', 'music-player-for-easy-digital-downloads' ) . ': <a href="https://demos.dwbooster.com/music-player-for-easy-digital-downloads/wp-login.php" target="_blank">WordPress</a></p>';
			?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table class="widefat" style="border:1px solid #e1e1e1;margin-bottom:20px;">
				<tr>
					<td colspan="2"><h2><?php esc_html_e( 'General Settings', 'music-player-for-easy-digital-downloads' ); ?></h2></td>
				</tr>
				<tr>
					<td width="30%" style="border:2px solid #E6DB55;border-right:0;"><?php esc_html_e( 'Include the players only for registered users', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td style="border:2px solid #E6DB55;border-left:0;"><input aria-label="<?php esc_attr_e( 'Include the players only for registered users', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_registered_only" <?php print( ( $registered_only ) ? 'CHECKED' : '' ); ?> /></td>
				</tr>
				<tr>
					<td width="30%"><?php esc_html_e( 'Apply fade out to playing audio when possible', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><input aria-label="<?php esc_attr_e( 'Apply fade out to playing audio when possible', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_fade_out" <?php print( ( $fade_out ) ? 'CHECKED' : '' ); ?> /></td>
				</tr>
				<tr>
					<td width="30%" style="color:#DADADA;"><?php esc_html_e( 'For buyers, play the purchased audio files instead of the truncated files for demo', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td style="color:#DADADA;">
						<input aria-label="<?php esc_attr_e( 'Play the orignal audio files for buyers', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" disabled />
						<?php esc_html_e( 'Reset the files', 'music-player-for-easy-digital-downloads' ); ?>
						<select aria-label="<?php esc_attr_e( 'Reset files interval', 'music-player-for-easy-digital-downloads' ); ?>" disabled>
							<option><?php print esc_html( __( 'daily', 'music-player-for-easy-digital-downloads' ) ); ?></option>
						</select>
						<?php _e( '<p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><?php _e( '<p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				</tr>
				<tr>
					<td width="30%" style="color:#DADADA"><?php esc_html_e( 'Truncate the audio files for demo with ffmpeg', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><input aria-label="<?php esc_attr_e( 'Enabling ffmpeg', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" DISABLED /></td>
				</tr>
				<tr>
					<td width="30%" style="color:#DADADA"><?php esc_html_e( 'ffmpeg path', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'ffmpeg path', 'music-player-for-easy-digital-downloads' ); ?>" type="text" value="<?php print esc_attr( ! empty( $ffmpeg_system_path ) ? $ffmpeg_system_path : '' ); ?>" DISABLED style="width:100%;" /><br />
						<i style="color:#DADADA">Ex: /usr/bin/</i>
					</td>
				</tr>
				<tr>
					<td width="30%" style="color:#DADADA"><?php esc_html_e( 'Watermark audio', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Watermark audio', 'music-player-for-easy-digital-downloads' ); ?>" type="text" style="width:calc( 100% - 60px ) !important;" class="eddmp-file-url" DISABLED /><input type="button" class="button-secondary" value="<?php esc_attr_e( 'Select', 'music-player-for-easy-digital-downloads' ); ?>" style="float:right;" DISABLED /><br />
						<i style="color:#DADADA"><?php esc_html_e( 'Select an audio file if you want to apply a watermark to the audio files for demos. The watermark will be applied to the protected demos (Experimental feature).', 'music-player-for-easy-digital-downloads' ); ?></i>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td width="30%"><?php esc_html_e( 'Delete the demo files generated previously', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><input aria-label="<?php esc_attr_e( 'Delete local demo files', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_delete_demos" /> (only applied to demo files stored locally on the website)</td>
				</tr>
				<?php
					do_action( 'eddmp_general_settings' );
				?>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><h2><?php esc_html_e( 'Troubleshoot Area', 'music-player-for-easy-digital-downloads' ); ?></h2></td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'On iPads and iPhones, use native controls', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Use default players on iPhone and iPads', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_ios_controls" <?php if ( $ios_controls ) {
							print 'CHECKED';} ?>/>
						<?php esc_html_e( 'tick the checkbox if the players do not work properly on iPads or iPhones', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Loading players in the onload event', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Generate players on onload event', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_onload" <?php if ( $troubleshoot_onload ) {
							print 'CHECKED';} ?>/>
						<?php esc_html_e( 'tick the checkbox if the players are not being loaded properly', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'For files whose extensions cannot be determined', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Managing files as mp3 by default', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_default_extension" <?php if ( $troubleshoot_default_extension ) {
							print 'CHECKED';} ?>/>
						<?php esc_html_e( 'handle them as mp3 files', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Easy Digital Downloads hook used to display the players in the shop pages', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Hook used to include the players on shop pages', 'music-player-for-easy-digital-downloads' ); ?>" type="text" name="_eddmp_main_player_hook" value="<?php print esc_attr( $include_main_player_hook ); ?>" style="width:100%" /><br />
						<?php _e( 'The plugin uses by default the <b>edd_shop_loop_item_title</b> hook. If the player is not being displayed, enter the hook used by the theme active on your website.', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Easy Digital Downloads hook used to display the players on the download pages', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Hook used to include the players on download pages', 'music-player-for-easy-digital-downloads' ); ?>" type="text" name="_eddmp_all_players_hook" value="<?php print esc_attr( $include_all_players_hook ); ?>" style="width:100%" /><br />
						<?php _e( 'The plugin uses by default the <b>edd_before_download_content</b> hook. If the player is not being displayed, enter the hook used by the theme active on your website.', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><?php _e( 'Click on <a href="https://docs.easydigitaldownloads.com/category/504-hooks" target="_blank">THIS LINK</a> to access the list of available <a href="https://easydigitaldownloads.com/categories/docs/actions/" target="_blank" style="font-weight:bold;font-size:1.3em;">EDD Hooks</a>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				</tr>
			</table>
			<table class="widefat eddmp-player-settings" style="border:1px solid #e1e1e1;">
				<tr>
					<td width="30%"><?php esc_html_e( 'Include music player in all downloads', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><div class="eddmp-tooltip"><span class="eddmp-tooltiptext"><?php esc_html_e( 'The player is shown only if the download has at least an audio file between the "Downloadable files", or you have selected your own audio files', 'music-player-for-easy-digital-downloads' ); ?></span><input aria-label="<?php esc_attr_e( 'Enabling player', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_enable_player" <?php echo ( ( $enable_player ) ? 'checked' : '' ); ?> /></div></td>
				</tr>
				<tr>
					<td width="30%"><?php esc_html_e( 'Include in', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Downloads pages only', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_show_in" value="single" <?php echo ( ( 'single' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'single-entry pages <i>(Download\'s page only)</i>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br />

						<input aria-label="<?php esc_attr_e( 'Multi-entry pages only', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_show_in" value="multiple" <?php echo ( ( 'multiple' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'multiple entries pages <i>(Shop pages, archive pages, but not in the download\'s page)</i>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br />

						<input aria-label="<?php esc_attr_e( 'Multi-entry and download pages', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_show_in" value="all" <?php echo ( ( 'all' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'all pages <i>(with single or multiple-entries)</i>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
				</tr>
				<tr>
					<td width="30%"><?php esc_html_e( 'Include players in cart', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Include players in cart', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_players_in_cart" <?php echo ( ( $players_in_cart ) ? 'checked' : '' ); ?> />
					</td>
				</tr>
				<tr>
					<td valign="top" width="30%"><?php esc_html_e( 'Player layout', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<table>
							<tr>
								<td><input aria-label="<?php esc_attr_e( 'Skin 1 - classic', 'music-player-for-easy-digital-downloads' ); ?>" name="_eddmp_player_layout" type="radio" value="mejs-classic" <?php echo ( ( 'mejs-classic' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td><img alt="<?php esc_attr_e( 'Skin 1', 'music-player-for-easy-digital-downloads' ); ?>" src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin1.png" /></td>
							</tr>

							<tr>
								<td><input aria-label="<?php esc_attr_e( 'Skin 2 - ted', 'music-player-for-easy-digital-downloads' ); ?>" name="_eddmp_player_layout" type="radio" value="mejs-ted" <?php echo ( ( 'mejs-ted' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td><img alt="<?php esc_attr_e( 'Skin 2', 'music-player-for-easy-digital-downloads' ); ?>" src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin2.png" /></td>
							</tr>

							<tr>
								<td><input aria-label="<?php esc_attr_e( 'Skin 3 - wmp', 'music-player-for-easy-digital-downloads' ); ?>" name="_eddmp_player_layout" type="radio" value="mejs-wmp" <?php echo ( ( 'mejs-wmp' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td><img alt="<?php esc_attr_e( 'Skin 3', 'music-player-for-easy-digital-downloads' ); ?>" src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin3.png" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Preload', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<label><input aria-label="<?php esc_attr_e( 'Preload - none', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_preload" value="none" <?php if ( 'none' == $preload ) {
							echo 'CHECKED';} ?> /> None</label><br />
						<label><input aria-label="<?php esc_attr_e( 'Preload - metadata', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_preload" value="metadata" <?php if ( 'metadata' == $preload ) {
							echo 'CHECKED';} ?> /> Metadata</label><br />
						<label><input aria-label="<?php esc_attr_e( 'Preload - auto', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_preload" value="auto" <?php if ( 'auto' == $preload ) {
							echo 'CHECKED';} ?> /> Auto</label><br />
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Play all', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Play all', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_play_all" <?php if ( $play_all ) {
							echo 'CHECKED';} ?> />
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Loop', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Loop', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_loop" <?php if ( $loop ) {
							echo 'CHECKED';} ?> />
					</td>
				</tr>
				<tr>
					<td width="30%">
						<?php esc_html_e( 'Allow multiple players to play simultaneously', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Allow multiple players to play simultaneously', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_play_simultaneously" <?php if ( $play_simultaneously ) {
							echo 'CHECKED';} ?> /><br />
						<i><?php
							esc_html_e( 'By default, only one player would be playing at once. By pressing the play button of a player, the other players would stop. By ticking the checkbox, multiple players could play simultaneously.', 'music-player-for-easy-digital-downloads' );
						?></i>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player volume (from 0 to 1)', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Default volume', 'music-player-for-easy-digital-downloads' ); ?>" type="number" name="_eddmp_player_volume" min="0" max="1" step="0.01" value="<?php echo esc_attr( $volume ); ?>" />
					</td>
				</tr>
				<tr>
					<td width="30%"><?php esc_html_e( 'Player controls', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Play/pause button', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_player_controls" value="button" <?php echo ( ( 'button' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'the play/pause button only', 'music-player-for-easy-digital-downloads' ); ?><br />
						<input aria-label="<?php esc_attr_e( 'All controls', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_player_controls" value="all" <?php echo ( ( 'all' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'all controls', 'music-player-for-easy-digital-downloads' ); ?><br />
						<input aria-label="<?php esc_attr_e( 'Depending on context', 'music-player-for-easy-digital-downloads' ); ?>" type="radio" name="_eddmp_player_controls" value="default" <?php echo ( ( 'default' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'the play/pause button only, or all controls depending on context', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
				</tr>
				<tr>
					<td width="30%"><?php esc_html_e( 'Display the player\'s title', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Display titles', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_player_title" <?php echo ( ( ! empty( $player_title ) ) ? 'checked' : '' ); ?> />
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php _e( '<p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				</tr>
				<tr>
					<td width="30%" style="color:#DADADA;"><?php esc_html_e( 'Protect the file', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><input aria-label="<?php esc_attr_e( 'Protect file', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" DISABLED /></td>
				</tr>
				<tr valign="top">
					<td width="30%" style="color:#DADADA;"><?php esc_html_e( 'Percent of audio used for protected playbacks', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td style="color:#DADADA;">
						<input aria-label="<?php esc_attr_e( 'Percentage', 'music-player-for-easy-digital-downloads' ); ?>" type="number" DISABLED /> % <br />
						<em style="color:#DADADA;"><?php esc_html_e( 'To prevent unauthorized copying of audio files, the files will be partially accessible', 'music-player-for-easy-digital-downloads' ); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td width="30%" style="color:#DADADA;">
						<?php esc_html_e( 'Text to display beside the player explaining that demos are partial versions of the original files', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<textarea aria-label="<?php esc_attr_e( 'Explanation text', 'music-player-for-easy-digital-downloads' ); ?>" style="width:100%;" rows="4" DISABLED></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table class="widefat" style="border:0;">
	<tr>
		<td>
			<table class="widefat" style="border:1px solid #e1e1e1;">
				<tr>
					<td>
						<div><?php esc_html_e( 'Scope', 'music-player-for-easy-digital-downloads' ); ?></div>
						<div><div class="eddmp-tooltip"><span class="eddmp-tooltiptext"><?php esc_html_e( 'Ticking the checkbox the previous settings are applied to all downloads, even if they have a player enabled.', 'music-player-for-easy-digital-downloads' ); ?></span><input aria-label="<?php esc_attr_e( 'Apply the settings to existent downloads', 'music-player-for-easy-digital-downloads' ); ?>" type="checkbox" name="_eddmp_apply_to_all_players" <?php print $apply_to_all_players == 1 ? 'CHECKED' : ''; ?> /></div> <?php esc_html_e( 'Apply the previous settings to all downloads pages in the website.', 'music-player-for-easy-digital-downloads' ); ?></div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table class="widefat" style="border:0;">
	<tr>
		<td>
			<table class="widefat" style="border:1px solid #e1e1e1;">
				<tr>
					<td><h2><?php esc_html_e( 'Analytics', 'music-player-for-easy-digital-downloads' ); ?></h2></td>
				</tr>
				<tr>
					<td>
						<p><?php esc_html_e( 'Allows the integration with Google Analytics for registering new events when the songs are played. The event information would include: the URL to the audio file as the event label and the download\'s id as its value.', 'music-player-for-easy-digital-downloads' ); ?></p>
						<p style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;"><b><?php esc_html_e( 'Note', 'music-player-for-easy-digital-downloads' ); ?></b>: <?php esc_html_e( 'If the preload option is configured as Metadata or Auto in the players settings, the event would be registered when the audio file is loaded by the player and not exactly when they are playing.', 'music-player-for-easy-digital-downloads' ); ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<label><input type="radio" name="_eddmp_analytics_integration" value="ua" <?php print 'ua' == $analytics_integration ? 'CHECKED' : ''; ?>> <?php esc_html_e( 'Universal Analytics', 'music-player-for-easy-digital-downloads' ); ?></label>
						<label style="margin-left:30px;"><input type="radio" name="_eddmp_analytics_integration" value="g" <?php print 'g' == $analytics_integration ? 'CHECKED' : ''; ?>> <?php esc_html_e( 'Measurement Protocol (Google Analytics 4)', 'music-player-for-easy-digital-downloads' ); ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<div><?php esc_html_e( 'Measurement Id', 'music-player-for-easy-digital-downloads' ); ?></div>
						<div><input aria-label="<?php esc_attr_e( 'Measurement Id', 'music-player-for-easy-digital-downloads' ); ?>" type="text" name="_eddmp_analytics_property" value="<?php print esc_attr( $analytics_property ); ?>" style="width:100%;" placeholder=""></div>
					</td>
				</tr>
				<tr class="eddmp-analytics-g4" style="display:<?php print esc_attr( 'ua' == $analytics_integration ? 'none' : 'table-row' ); ?>;">
					<td style="width:100%;">
						<div><?php esc_html_e( 'API Secret', 'music-player-for-easy-digital-downloads' ); ?></div>
						<div><input aria-label="<?php esc_attr_e( 'API Secret', 'music-player-for-easy-digital-downloads' ); ?>" type="text" name="_eddmp_analytics_api_secret" value="<?php print esc_attr( $analytics_api_secret ); ?>" style="width:100%;"></div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div style="margin-top:20px;"><input type="submit" value="<?php esc_html_e( 'Save settings', 'music-player-for-easy-digital-downloads' ); ?>" class="button-primary" /></div>
</form>
<script>jQuery(window).on('load', function(){
	var $ = jQuery;
	function coverSection()
	{
		var v = $('[name="_eddmp_player_controls"]:checked').val(),
			c = $('.eddmp-on-cover');
		if('default' == v || 'button' == v) c.show();
		else c.hide();
	};
	$(document).on('change', '[name="_eddmp_player_controls"]', function(){
		coverSection();
	});
	$(document).on('change', '[name="_eddmp_analytics_integration"]', function(){
		var v = $('[name="_eddmp_analytics_integration"]:checked').val();
		$('.eddmp-analytics-g4').css('display', 'g' == v ? 'table-row' : 'none');
		$('[name="_eddmp_analytics_property"]').attr('placeholder', 'g' == v ? 'G-XXXXXXXX' : 'UA-XXXXX-Y');
	});
	$('[name="_eddmp_analytics_integration"]:eq(0)').trigger('change');
	coverSection();
});</script>
<style>.eddmp-player-settings tr td:first-child{width:225px;}</style>
