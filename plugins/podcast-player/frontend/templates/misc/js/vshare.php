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
