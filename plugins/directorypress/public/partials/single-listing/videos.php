<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS, $wp_embed;

if ($listing->package->videos_allowed && $listing->videos && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_video_position'] == 'notab'): ?>
	<div id="videos-notab" class="notab">
		<span class="directorypress-video-field-name"><?php echo esc_html__('Videos', 'DIRECTORYPRESS'); ?></span>
		<?php foreach ($listing->videos AS $video): ?>
			
			<?php if (strlen($video['id']) == 11): ?>
				<iframe width="100%" height="400" class="directorypress-video-iframe fitvidsignore" src="//www.youtube.com/embed/<?php echo esc_attr($video['id']); ?>" frameborder="0" allowfullscreen></iframe>
			<?php elseif (strlen($video['id']) == 9): ?>
				<iframe width="100%" height="400" class="directorypress-video-iframe fitvidsignore" src="https://player.vimeo.com/video/<?php echo esc_attr($video['id']); ?>?color=d1d1d1&title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			<?php else: ?>
				<?php $link = wp_get_attachment_url($video['id']); ?>
				<?php echo '<video src="'. esc_url($link) .'" controls style="width:100%"></video>'; // phpcs: OK ?>
			<?php endif; ?>						
		<?php endforeach; ?>
	</div>
<?php endif; ?>