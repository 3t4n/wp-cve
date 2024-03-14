<?php
/**
* Template for Design 1
*
* @package Audio Player with Playlist Ultimate
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( $grid == '2' ) {
	$audiogrid = "6";
} else if( $grid == '3' ) {
	$audiogrid = "4";
}  else if( $grid == '4' ) {
	$audiogrid = "3";
}  else if( $grid == '5' ) {
	$audiogrid = "c5";	
} else if ( $grid == '1' ) {
	$audiogrid = "12";
} else {
	$audiogrid = "12";
} ?>

<div class="apwpultimate-audio-player-innr-wrap apwp-medium-<?php echo esc_attr($audiogrid); ?> apwp-columns player-layout-one">
	<div id="apwpultimate-jplayer-<?php echo esc_attr($unique); ?>" class="apwpultimate-audio-player-grid jp-jplayer"></div>
		<div id="apwpultimate-jplayer-<?php echo esc_attr($unique); ?>-cntrl" class="player-main-block  jp-audio" role="application" aria-label="media player">
		<div class="jp-type-single">
			<div class="jp-gui jp-interface">
				<div class="apwp-medium-2 apwp-columns">
					<div class="jp-controls">
						<button class="jp-play controller-common" role="button" tabindex="0">&nbsp;</button>
					</div>
				</div>
				<div class="apwp-medium-7 apwp-columns">	
					<div class="jp-progress position-unset">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-time-holder position-unset">
						<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
						<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
						<div class="jp-toggles">
							<button class="jp-repeat" role="button" tabindex="0">&nbsp;</button>
						</div>
					</div>	
				</div>
				<div class="apwp-medium-3 apwp-columns">
					<div class="jp-volume-controls position-unset">
						<button class="jp-mute" role="button" tabindex="0">&nbsp;</button>
						<div class="jp-volume-bar position-unset">
							<div class="jp-volume-bar-value"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="jp-details">
				<div class="jp-title" aria-label="title">&nbsp;</div>
				<div class="jp-artist"><?php esc_html_e( 'by', 'audio-player-with-playlist-ultimate' ); ?> <?php echo esc_html($artist_name); ?></div>
			</div>
			<div class="jp-no-solution">
				<span>Update Required</span>
				To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
			</div>
		</div>
	</div>
	<div class="apwpultimate-conf apwpultimate-hide"><?php echo htmlspecialchars(json_encode($record)); ?></div>
</div>