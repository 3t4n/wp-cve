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

<div class="pp-modal-aux-wrapper">
	<ul class="pp-modal-tabs">
		<li class="pp-modal-tabs-item lists-tab"><?php esc_html_e( 'All Episodes', 'podcast-player' ); ?></li>
		<li class="pp-modal-tabs-item content-tab selected"><?php esc_html_e( 'Shownotes', 'podcast-player' ); ?></li>
	</ul>
	<div class="pp-modal-tabs-list" style="display: none;"></div>
	<div class="pp-modal-tabs-content"></div>
</div>
