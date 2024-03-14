<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$vik = VAPApplication::getInstance();

?>

<div class="inspector-form vap-media-inspector" id="inspector-attachment-form">

	<div class="media-info-box">
		<div class="media-thumbnail media-thumbnail-image">
			<a href="#" target="_blank">
				<img src="" />
			</a>
		</div>

		<div class="media-details">
			<div class="filename"></div>
			<div class="uploaded"></div>
			<div class="file-size"></div>
			<div class="dimensions"></div>
		</div>
	</div>

</div>

<script>

	(function($) {
		$(function() {
			// fill the form before showing the inspector
			$('#attachments-inspector').on('inspector.show', (event) => {
				var media = $(event.params.element);

				var mediaUrl = media.find('*[src],*[data-src]');
				mediaUrl = mediaUrl.attr('src') || mediaUrl.data('src');

				var box = $('#inspector-attachment-form .media-info-box').last();

				box.find('.media-thumbnail-image a')
					.attr('href', mediaUrl)
					.html(media.find('.media-centered').html());

				box.find('.media-details .filename').text(media.data('name'));
				box.find('.media-details .uploaded').text(media.data('date'));
				box.find('.media-details .file-size').text(media.data('size'));
			});
		});
	})(jQuery);

</script>
