<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/misc/js/controls.php.
 *
 * HOWEVER, on occasion Podcast Player will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Podcast Player
 * @version 1.0.0
 * @since   1.0.0
 */

use Podcast_Player\Helper\Functions\Markup as Markup_Fn;

?>

<div class="ppjs__head-container">
	<div class="head-wrapper">
		<div class="ppjs__podcast-title"></div>
		<div class="ppjs__episode-title"></div>
		<a class="ppjs__episode-excerpt" href="#">
			<span class="ppjs__excerpt-content"></span>
			<span class="ppjs__more"><?php esc_html_e( '[...]', 'podcast-player' ); ?><span>
		</a>
	</div>
</div>
<div class="ppjs__secondary-controls">
	<div class="ppjs__control_btns">
		<div class="ppjs__button ppjs__play-rate-button">
			<button type="button">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Change Playback Rate', 'podcast-player' ); ?>
				</span>
				<div class="play-rate-text">
					<span class="pp-rate">1</span>
					<span class="pp-times">x</span>
				</div>
			</button>
			<ul class="play-rate-list">
				<li><a class="prl-item" href="#">0.8</a></li>
				<li><a class="prl-item" href="#">1</a></li>
				<li><a class="prl-item" href="#">1.2</a></li>
				<li><a class="prl-item" href="#">1.5</a></li>
				<li><a class="prl-item" href="#">2</a></li>
			</ul>
		</div>
		<div class="ppjs__button ppjs__skip-prev-button">
			<button type="button" class="pp-prev-btn">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Go to previous episode', 'podcast-player' ); ?>
				</span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-previous' ) ); ?>
			</button>
		</div>
		<div class="ppjs__button ppjs__skip-backward-button">
			<button type="button">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Skip Backward', 'podcast-player' ); ?>
				</span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-rotate-ccw' ) ); ?>
			</button>
		</div>
		<div class="ppjs__button ppjs__playpause-button">
			<button type="button">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Play Pause', 'podcast-player' ); ?>
				</span>
				<?php
				Markup_Fn::the_icon( array( 'icon' => 'pp-play' ) );
				Markup_Fn::the_icon( array( 'icon' => 'pp-pause' ) );
				Markup_Fn::the_icon( array( 'icon' => 'pp-refresh' ) );
				?>
			</button>
		</div>
		<div class="ppjs__button ppjs__jump-forward-button">
			<button type="button">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Jump Forward', 'podcast-player' ); ?>
				</span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-rotate-cw' ) ); ?>
			</button>
		</div>
		<div class="ppjs__button ppjs__skip-next-button">
			<button type="button" class="pp-next-btn">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Skip to next episode', 'podcast-player' ); ?>
				</span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-next' ) ); ?>
			</button>
		</div>
		<div class="ppjs__button ppjs__download-alt-button">
			<a role="button" class="ppshare__download button" href="" title="Download" download="">
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-download' ) ); ?>
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Download', 'podcast-player' ); ?>
				</span>
			</a>
		</div>
		<div class="ppjs__button ppjs__share-button">
			<button type="button">
				<span class="ppjs__offscreen">
					<?php esc_html_e( 'Share This Episode', 'podcast-player' ); ?>
				</span>
				<?php Markup_Fn::the_icon( array( 'icon' => 'pp-share' ) ); ?>
			</button>
			<ul class="ppshare__social ppsocial">
				<li class="ppshare-item social">
					<a class="ppsocial__link ppsocial__facebook" href="" target="_blank" title="<?php esc_html_e( 'Share on Facebook', 'podcast-player' ); ?>">
						<?php Markup_Fn::the_icon( array( 'icon' => 'pp-facebook' ) ); ?>
						<span class="ppjs__offscreen">
							<?php esc_html_e( 'Facebook', 'podcast-player' ); ?>
						</span>
					</a>
				</li>
				<li class="ppshare-item social">
					<a class="ppsocial__link ppsocial__twitter" href="" target="_blank" title="<?php esc_html_e( 'Share on Twitter', 'podcast-player' ); ?>">
						<?php Markup_Fn::the_icon( array( 'icon' => 'pp-twitter' ) ); ?>
						<span class="ppjs__offscreen">
							<?php esc_html_e( 'Twitter', 'podcast-player' ); ?>
						</span>
					</a>
				</li>
				<li class="ppshare-item social">
					<a class="ppsocial__link ppsocial__linkedin" href="" target="_blank" title="<?php esc_html_e( 'Share on Linkedin', 'podcast-player' ); ?>">
						<?php Markup_Fn::the_icon( array( 'icon' => 'pp-linkedin' ) ); ?>
						<span class="ppjs__offscreen">
							<?php esc_html_e( 'Linkedin', 'podcast-player' ); ?>
						</span>
					</a>
				</li>
				<li class="ppshare-item social">
					<a class="ppsocial__link ppsocial__copylink" href="#" title="<?php esc_html_e( 'Copy episode link', 'podcast-player' ); ?>">
						<?php Markup_Fn::the_icon( array( 'icon' => 'pp-copy' ) ); ?>
						<span class="ppjs__offscreen">
							<?php esc_html_e( 'Copy episode link', 'podcast-player' ); ?>
						</span>
						<span class="pp-copylink-msg"><?php esc_html_e( 'Copied', 'podcast-player' ); ?></span>
					</a>
				</li>
				<li class="ppshare-item download">
					<a role="button" class="ppshare__download" href="" title="Download" download="">
						<?php Markup_Fn::the_icon( array( 'icon' => 'pp-download' ) ); ?>
						<span class="ppjs__offscreen">
							<?php esc_html_e( 'Download', 'podcast-player' ); ?>
						</span>
					</a>
				</li>
				<input type="text" value="" class="pp-copylink" style="display: none;">
				</ul>
		</div>
	</div><!-- .ppjs__control_btns -->
</div><!-- .ppjs__secondary-controls -->
