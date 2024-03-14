<?php
/**
* Template for Design 1
*
* @package Audio Player with Playlist Ultimate
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="player-main-block player-layout-grid-one jp-video apwp-audio-player-<?php echo esc_attr($unique); ?>-css-ance" aria-label="media player">
	<div class="jp-type-playlist">
		<div class="album-art-block">
			<div id="apwp-audio-player-<?php echo esc_attr($unique); ?>" class="apwp-audio-player jp-jplayer"></div>
			<div class="jp-details track-meta-info">
				<div class="jp-title" aria-label="title">&nbsp;</div>
			</div>
			<div class="overlay-one"></div>
		</div>
		<div class="jp-gui audio-controller-wrap">
			<div class="jp-video-play">
				<button class="jp-video-play-icon" tabindex="0"><?php esc_html_e( 'play', 'audio-player-with-playlist-ultimate' ); ?> </button>
			</div>
			<div class="jp-interface">
				<div class="jp-controls">
					<button class="jp-previous controller-common" tabindex="0"></button>
					<button class="jp-play controller-common" tabindex="0"></button>
					<button class="jp-next controller-common" tabindex="0"></button>
				</div>
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="duration-2">
					<span class="jp-current-time" aria-label="time">&nbsp;</span>
					<span class="divider">/</span>
					<span class="jp-duration" aria-label="duration">&nbsp;</span>
				</div>
				<div class="jp-controls-holder">
					<div class="jp-volume-controls">
						<button class="jp-mute" tabindex="0"></button>
						<div class="jp-volume-bar">
							<div class="jp-volume-bar-value"></div>
						</div>
					</div>
					<div class="jp-toggles">
						<button class="jp-shuffle" tabindex="0"></button>
						<button class="jp-repeat" tabindex="0"></button>
						<?php if( empty( $audio_id ) && $playlist_hide == false ) { ?> <button id="playListBtnOne" class="jp-playlist playlist-btn toggleBlock active" data-id="<?php echo esc_attr($unique); ?>"></button> <?php } ?>
					</div>
				</div>
				<div class="jp-details">
				<div class="jp-title" aria-label="title">&nbsp;</div>
				</div>
			</div>
		</div>
		<?php if( $playlist_hide == false ) { ?>
			<div id="playListOne-<?php echo esc_attr($unique); ?>" class="jp-playlist <?php if( ! empty( $audio_id )) { echo 'playlist-hide'; } else { echo 'playlist-block';} ?>">
				<ul class="playlist duration-playlist">
					<!-- The method Playlist.displayPlaylist() uses this unordered list -->
					<li>&nbsp;</li>
				</ul>
			</div>
		<?php } ?>
	</div>
</div>
