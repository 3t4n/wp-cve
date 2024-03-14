<?php
if ( ! defined( 'EDDMP_PLUGIN_URL' ) ) {
	echo 'Direct access not allowed.';
	exit; }

// include resources
wp_enqueue_style( 'eddmp-admin-style', plugin_dir_url( __FILE__ ) . '../css/style.admin.css', array(), EDDMP_PLUGIN_VERSION );
wp_enqueue_script( 'eddmp-admin-js', plugin_dir_url( __FILE__ ) . '../js/admin.js', array(), EDDMP_PLUGIN_VERSION );
$eddmp_js = array(
	'File Name'         => __( 'File Name', 'music-player-for-easy-digital-downloads' ),
	'Choose file'       => __( 'Choose file', 'music-player-for-easy-digital-downloads' ),
	'Delete'            => __( 'Delete', 'music-player-for-easy-digital-downloads' ),
	'Select audio file' => __( 'Select audio file', 'music-player-for-easy-digital-downloads' ),
	'Select Item'       => __( 'Select Item', 'music-player-for-easy-digital-downloads' ),
);
wp_localize_script( 'eddmp-admin-js', 'eddmp', $eddmp_js );

global $post;
$enable_player   = $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_enable_player', false );
$show_in         = $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_show_in', 'all' );
$player_style    = $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_player_layout', EDDMP_DEFAULT_PLAYER_LAYOUT );
$volume          = $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_player_volume', EDDMP_DEFAULT_PLAYER_VOLUME );
$player_controls = $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_player_controls', EDDMP_DEFAULT_PLAYER_CONTROLS );
$player_title    = intval( $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_player_title', 1 ) );
$play_all        = intval(
	$GLOBALS['EDDMusicPlayer']->get_download_attr(
		$post->ID,
		'_eddmp_play_all',
		// This option is only for compatibility with versions previous to 1.0.28
						$GLOBALS['EDDMusicPlayer']->get_download_attr(
							$post->ID,
							'play_all',
							0
						)
	)
);
$loop    = intval( $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_loop', 0 ) );
$preload = $GLOBALS['EDDMusicPlayer']->get_download_attr(
	$post->ID,
	'_eddmp_preload',
	$GLOBALS['EDDMusicPlayer']->get_download_attr(
		$post->ID,
		'preload',
		'none'
	)
);
?>
<input type="hidden" name="eddmp_nonce" value="<?php echo esc_attr( wp_create_nonce( 'eddmp_updating_download' ) ); ?>" />
<table class="widefat" style="border-left:0;border-right:0;border-bottom:0;padding-bottom:0;">
	<tr>
		<td>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
			<div class="eddmp-highlight-box">
				<?php
				_e( '<p>The player uses the audio files associated to the download. If you want protecting the audio files for selling, tick the checkbox: <b>"Protect the file"</b>, in whose case the plugin will create a truncated version of the audio files for selling to be used for demo. The size of audio files for demo is based on the number entered through the attribute: <b>"Percent of audio used for protected playbacks"</b>.</p><p><b>Protecting the files prevents that malicious users can access to the original audio files without pay for them.</b></p><p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
				_e( '<p>For testing the professional features, visit the online demo:</p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
				print '<p>' . esc_html__( 'Public website', 'music-player-for-easy-digital-downloads' ) . ': <a href="https://demos.dwbooster.com/music-player-for-easy-digital-downloads" target="_blank">Music Player for Easy Digital Downloads</a><br>' . esc_html__( 'WordPress', 'music-player-for-easy-digital-downloads' ) . ': <a href="https://demos.dwbooster.com/music-player-for-easy-digital-downloads/wp-login.php" target="_blank">WordPress</a></p>';
				?>
			</div>
			<?php endif; ?>
			<div class="eddmp-highlight-box">
				<div id="eddmp_tips_header">
					<h2 style="margin-top:2px;margin-bottom:5px;cursor:pointer;" onclick="jQuery('#eddmp_tips_body').toggle();">
						<?php esc_html_e( '[+|-] Tips', 'music-player-for-easy-digital-downloads' ); ?>
					</h2>
				</div>
				<div id="eddmp_tips_body">
					<div class="eddmp-highlight-box">
						<a class="eddmp-tip"href="javascript:void(0);" onclick="jQuery(this).next('.eddmp-tip-text').toggle();">
						<?php esc_html_e( '[+|-] Using the audio files stored on Google Drive', 'music-player-for-easy-digital-downloads' ); ?>
						</a>
						<div class="eddmp-tip-text">
						<ul>
							<li>
								<p> -
								<?php
									_e( 'Go to Drive, press the right click on the file to use, and select the option: <b>"Get Shareable Link"</b>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
								?>
								</p>
								<p>
								<?php
									esc_html_e(
										'The previous action will generate an url with the structure: ',
										'music-player-for-easy-digital-downloads'
									);
									?><b>https://drive.google.com/open?id=FILE_ID</b>
								</p>
							</li>
							<li>
								<p> -
									<?php
									esc_html_e(
										'Knowing the FILE_ID, extracted from the previous URL, enter the URL below, into the Easy Digital Downloads download, to allow the Music Player accessing to it:',
										'music-player-for-easy-digital-downloads'
									);
									?>
								</p>
								<p>
									<b>https://drive.google.com/uc?export=download&id=FILE_ID&.mp3</b>
								</p>
								<p>
									<?php
									_e( '<b>Note:</b> Pay attention to the use of the fake parameter: <b>&.mp3</b> as the last one in the URL', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
									?>
								</p>
							</li>
						</div>
					</div>
					<div class="eddmp-highlight-box">
						<a class="eddmp-tip"href="javascript:void(0);" onclick="jQuery(this).next('.eddmp-tip-text').toggle();">
						<?php esc_html_e( '[+|-] Using the audio files stored on DropBox', 'music-player-for-easy-digital-downloads' ); ?>
						</a>
						<div class="eddmp-tip-text">
						<ul>
							<li>
								<p> -
								<?php
									esc_html_e(
										'Sign in to ',
										'music-player-for-easy-digital-downloads'
									);
									?><a href="https://www.dropbox.com/login" target="_blank">dropbox.com </a>
								</p>
							</li>
							<li>
								<p> -
								<?php
									_e( "Hover your cursor over the file or folder you'd like to share and click <b>Share</b> when it appears.", 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
								?>
								</p>
							</li>
							<li>
								<p> -
								<?php
									esc_html_e(
										"If a link hasn't been created, click Create a link. (If a link was already created, click Copy link.",
										'music-player-for-easy-digital-downloads'
									);
									?>
								</p>
								<p>
								<?php
									esc_html_e(
										'The link structure would be similar to:',
										'music-player-for-easy-digital-downloads'
									);
									?><br> https://www.dropbox.com/s/rycvgn8iokfedmo/file.mp3?dl=0
								</p>
							</li>
							<li>
								<p> -
								<?php
									esc_html_e(
										'Enter the URL into the Easy Digital Downloads download with the following structure:',
										'music-player-for-easy-digital-downloads'
									);
									?><br> https://www.dropbox.com/s/rycvgn8iokfedmo/file.mp3?dl=1&.mp3
								</p>
								<p>
									<?php
									_e( '<b>Note:</b> Pay attention to the use of the fake parameter: <b>&.mp3</b> as the last one in the URL. Furthermore, the parameter <b>dl=0</b>, has been modified as <b>dl=1</b>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput
									?>
								</p>
							</li>
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table class="widefat eddmp-player-settings" style="border:1px solid #e1e1e1;">
				<tr>
					<td><?php esc_html_e( 'Include music player', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><div class="eddmp-tooltip"><span class="eddmp-tooltiptext"><?php esc_html_e( 'The player is shown only if the download has at least an audio file between the "Downloadable files", or you have selected your own audio files', 'music-player-for-easy-digital-downloads' ); ?></span><input aria-label="<?php print esc_attr( __( 'Enabling player', 'music-player-for-easy-digital-downloads' ) ); ?>" type="checkbox" name="_eddmp_enable_player" <?php echo ( ( $enable_player ) ? 'checked' : '' ); ?> /></div></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Include in', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php print esc_attr( __( 'Downloads pages only', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_show_in" value="single" <?php echo ( ( 'single' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'single-entry pages <i>(Download\'s page only)</i>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br />

						<input aria-label="<?php print esc_attr( __( 'Multi-entry pages only', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_show_in" value="multiple" <?php echo ( ( 'multiple' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'multiple entries pages <i>(Shop pages, archive pages, but not in the download\'s page)</i>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br />

						<input aria-label="<?php print esc_attr( __( 'Multi-entry and download pages', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_show_in" value="all" <?php echo ( ( 'all' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'all pages <i>(with single or multiple-entries)</i>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
				</tr>
				<tr>
					<td valign="top"><?php esc_html_e( 'Player layout', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<table>
							<tr>
								<td><input aria-label="<?php print esc_attr( __( 'Skin 1', 'music-player-for-easy-digital-downloads' ) ); ?>" name="_eddmp_player_layout" type="radio" value="mejs-classic" <?php echo ( ( 'mejs-classic' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td><img alt="<?php print esc_attr( __( 'Skin 1', 'music-player-for-easy-digital-downloads' ) ); ?>" src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin1.png" /></td>
							</tr>

							<tr>
								<td><input aria-label="<?php print esc_attr( __( 'Skin 2', 'music-player-for-easy-digital-downloads' ) ); ?>" name="_eddmp_player_layout" type="radio" value="mejs-ted" <?php echo ( ( 'mejs-ted' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td><img alt="<?php print esc_attr( __( 'Skin 2', 'music-player-for-easy-digital-downloads' ) ); ?>" src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin2.png" /></td>
							</tr>

							<tr>
								<td><input aria-label="<?php print esc_attr( __( 'Skin 3', 'music-player-for-easy-digital-downloads' ) ); ?>" name="_eddmp_player_layout" type="radio" value="mejs-wmp" <?php echo ( ( 'mejs-wmp' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td><img alt="<?php print esc_attr( __( 'Skin 3', 'music-player-for-easy-digital-downloads' ) ); ?>" src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin3.png" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Preload', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<label><input aria-label="<?php print esc_attr( __( 'Preload - none', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_preload" value="none" <?php if ( 'none' == $preload ) {
							echo 'CHECKED';} ?> /> None</label><br />
						<label><input aria-label="<?php print esc_attr( __( 'Preload - metadata', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_preload" value="metadata" <?php if ( 'metadata' == $preload ) {
							echo 'CHECKED';} ?> /> Metadata</label><br />
						<label><input aria-label="<?php print esc_attr( __( 'Preload - auto', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_preload" value="auto" <?php if ( 'auto' == $preload ) {
							echo 'CHECKED';} ?> /> Auto</label><br />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Play all', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php print esc_attr( __( 'Play all', 'music-player-for-easy-digital-downloads' ) ); ?>" type="checkbox" name="_eddmp_play_all" <?php if ( ! empty( $play_all ) ) {
							echo 'CHECKED';} ?> />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Loop', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
					<td>
						<input aria-label="<?php print esc_attr( __( 'Loop', 'music-player-for-easy-digital-downloads' ) ); ?>" type="checkbox" name="_eddmp_loop" <?php if ( ! empty( $loop ) ) {
							echo 'CHECKED';} ?> />
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player volume (from 0 to 1)', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php print esc_attr( __( 'Default volume', 'music-player-for-easy-digital-downloads' ) ); ?>" type="number" name="_eddmp_player_volume" min="0" max="1" step="0.01" value="<?php echo esc_attr( $volume ); ?>" />
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player controls', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php print esc_attr( __( 'Play/pause button', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_player_controls" value="button" <?php echo ( ( 'button' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'the play/pause button only', 'music-player-for-easy-digital-downloads' ); ?><br />
						<input aria-label="<?php print esc_attr( __( 'All controls', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_player_controls" value="all" <?php echo ( ( 'all' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'all controls', 'music-player-for-easy-digital-downloads' ); ?><br />
						<input aria-label="<?php print esc_attr( __( 'Depending on context', 'music-player-for-easy-digital-downloads' ) ); ?>" type="radio" name="_eddmp_player_controls" value="default" <?php echo ( ( 'default' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'the play/pause button only, or all controls depending on context', 'music-player-for-easy-digital-downloads' ); ?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Display the player\'s title', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td>
						<input aria-label="<?php print esc_attr( __( 'Display titles', 'music-player-for-easy-digital-downloads' ) ); ?>" type="checkbox" name="_eddmp_player_title" <?php echo ( ( ! empty( $player_title ) ) ? 'checked' : '' ); ?> />
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php _e( '<p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				</tr>
				<tr>
					<td style="color:#DADADA;"><?php esc_html_e( 'Protect the file', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td><input aria-label="<?php print esc_attr( __( 'Protect file', 'music-player-for-easy-digital-downloads' ) ); ?>" type="checkbox" DISABLED /></td>
				</tr>
				<tr valign="top">
					<td style="color:#DADADA;"><?php esc_html_e( 'Percent of audio used for protected playbacks', 'music-player-for-easy-digital-downloads' ); ?></td>
					<td style="color:#DADADA;">
						<input aria-label="<?php print esc_attr( __( 'Percentage', 'music-player-for-easy-digital-downloads' ) ); ?>" type="number" DISABLED /> % <br /><br />
						<em><?php esc_html_e( 'To prevent unauthorized copying of audio files, the files will be partially accessible', 'music-player-for-easy-digital-downloads' ); ?></em>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table class="widefat" style="border:0;padding-bottom:20px;">
	<tr>
		<td>
			<table class="widefat" style="border:1px solid #e1e1e1;">
				<tr>
					<td><?php _e( '<p style="color:red;font-weight:bold;">FEATURE AVAILABLE IN THE PROFFESIONAL VERSION OF THE PLUGIN: <a target="_blank" href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads?wp=1#download"  class="eddmp-blink">CLICK HERE</a></p>', 'music-player-for-easy-digital-downloads' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				</tr>
				<tr valign="top">
					<td style="color:#DADADA;"><input type="checkbox" DISABLED /> <?php esc_html_e( 'Select my own demo files', 'music-player-for-easy-digital-downloads' ); ?></td>
				</tr>
				<tr valign="top">
					<td style="color:#DADADA;"><input aria-label="<?php print esc_attr( __( 'Own demo files', 'music-player-for-easy-digital-downloads' ) ); ?>" type="checkbox" DISABLED /> <?php esc_html_e( 'Load directly the own demo files without preprocessing', 'music-player-for-easy-digital-downloads' ); ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
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
	coverSection();
});</script>
<style>.eddmp-player-settings tr td:first-child{width:225px;}</style>
