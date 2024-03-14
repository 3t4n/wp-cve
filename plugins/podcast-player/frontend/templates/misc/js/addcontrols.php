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

<div class="ppjs__additional_controls">
	<div class="ppjs__button ppjs__pp-text">
		<button type="button" class="pp-text-aux-btn">
			<span class="ppjs__offscreen">
				<?php esc_html_e( 'Show Podcast Episode Text', 'podcast-player' ); ?>
			</span>
			<?php Markup_Fn::the_icon( array( 'icon' => 'pp-text' ) ); ?>
			<?php Markup_Fn::the_icon( array( 'icon' => 'pp-x' ) ); ?>
		</button>
	</div>
</div>
