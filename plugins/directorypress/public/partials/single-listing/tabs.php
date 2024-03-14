<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
if($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_tab']): 
	$tab_ordering = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress-listings-tabs-order']['enabled'];
	unset($tab_ordering['placebo']);
	$default_tab_keys = array();
	
	foreach ($tab_ordering as $key=>$value){
		$default_tab_keys[] = $key;
	}
	
?>
<div class="single-listing-tabs-wrapper">
	<script>
		(function($) {
			"use strict";
	
			$(function() {
				<?php if ($tab_ordering): 
									
					//$tab_ordering = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress-listings-tabs-order']['enabled'];
				?>
				if (1==2) var x = 1;
				<?php foreach ($tab_ordering as $key=>$value): ?>
					else if ($('#<?php echo esc_attr($key); ?>').length)
						directorypress_show_tab($('.directorypress-listing-tabs a[data-tab="#<?php echo esc_attr($key); ?>"]'));
				<?php endforeach; ?>
				<?php else: ?>
					directorypress_show_tab($('.directorypress-listing-tabs a:first'));
				<?php endif; ?>
			});
		})(jQuery);
	</script>
	<?php 
		//print_r($default_tab_keys); 
	?>
	<?php if (($fields_groups = $listing->get_fields_groups_in_tabs()) || ($listing->is_map() && $listing->locations) || (directorypress_is_reviews_allowed()) || ($listing->package->videos_allowed && $listing->videos) || $DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages'): ?>
		<?php if(wp_is_mobile()){ ?>
								<ul class="directorypress-listing-tabs nav navbar-nav clearfix" role="tablist">
									<?php if (directorypress_has_map() && ($DIRECTORYPRESS_ADIMN_SETTINGS['map_on_single_listing_tab'] && $listing->is_map() && $listing->locations && in_array('addresses-tab', $default_tab_keys))): ?>
									<li><a href="javascript: void(0);" data-tab="#addresses-tab" role="tab"><i class="directorypress-icon-home"></i><?php _e('Map Views', 'DIRECTORYPRESS'); ?></a></li>
									<?php endif; ?>
									<?php if (directorypress_is_reviews_allowed() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_position'] == 'intab' && in_array('comments-tab', $default_tab_keys)): ?>
									<li><a href="javascript: void(0);" data-tab="#comments-tab" role="tab"><i class="directorypress-icon-comments-o"></i><?php echo _n('Comment', 'Comments', esc_attr($listing->post->comment_count), 'DIRECTORYPRESS'); ?> (<?php echo esc_attr($listing->post->comment_count); ?>)</a></li>
									<?php endif; ?>
									<?php if ($listing->package->videos_allowed && $listing->videos && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_video_position'] == 'intab' && in_array('videos-tab', $default_tab_keys)): ?>
									<li><a href="javascript: void(0);" data-tab="#videos-tab" role="tab"><i class="directorypress-icon-play"></i><?php echo _n('Video', 'Videos', count($listing->videos), 'DIRECTORYPRESS'); ?> (<?php echo esc_attr(count($listing->videos)); ?>)</a></li>
									<?php endif; ?>
									<?php
									foreach ($fields_groups AS $fields_group): ?>
									<li><a href="javascript: void(0);" data-tab="#field-group-tab-<?php echo esc_attr($fields_group->id); ?>" role="tab"><?php echo esc_html($fields_group->name); ?></a></li>
									<?php endforeach; ?>
								</ul>
		<?php }else{ ?>
			<ul class="directorypress-listing-tabs nav nav-tabs clearfix" role="tablist">
									<?php if (directorypress_has_map() && ($DIRECTORYPRESS_ADIMN_SETTINGS['map_on_single_listing_tab'] && $listing->is_map() && $listing->locations && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_map_position'] == 'intab' && in_array('addresses-tab', $default_tab_keys) )): ?>
									<li><a href="javascript: void(0);" data-tab="#addresses-tab" data-toggle="directorypress-tab" role="tab"><i class="directorypress-icon-home"></i><?php _e('Map Views', 'DIRECTORYPRESS'); ?></a></li>
									<?php endif; ?>
									<?php if (directorypress_is_reviews_allowed() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_position'] == 'intab' && in_array('comments-tab', $default_tab_keys)): ?>
									<li><a href="javascript: void(0);" data-tab="#comments-tab" data-toggle="directorypress-tab" role="tab"><i class="directorypress-icon-comments-o"></i><?php echo _n('Comment', 'Comments', $listing->post->comment_count, 'DIRECTORYPRESS'); ?> (<?php echo esc_attr($listing->post->comment_count); ?>)</a></li>
									<?php endif; ?>
									<?php if ($listing->package->videos_allowed && $listing->videos && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_video_position'] == 'intab'  && in_array('videos-tab', $default_tab_keys)): ?>
									<li><a href="javascript: void(0);" data-tab="#videos-tab" data-toggle="directorypress-tab" role="tab"><i class="directorypress-icon-play"></i><?php echo _n('Video', 'Videos', count($listing->videos), 'DIRECTORYPRESS'); ?> (<?php echo esc_attr(count($listing->videos)); ?>)</a></li>
									<?php endif; ?>
									<?php
									foreach ($fields_groups AS $fields_group): ?>
									<li><a href="javascript: void(0);" data-tab="#field-group-tab-<?php echo esc_attr($fields_group->id); ?>" data-toggle="directorypress-tab" role="tab"><?php echo esc_html($fields_group->name); ?></a></li>
									<?php endforeach; ?>
			</ul>
		<?php } ?>
		<div class="tab-content">
								<?php if ($listing->is_map() && $listing->locations && directorypress_has_map()): ?>
									<div id="addresses-tab" class="tab-pane fade" role="tabpanel">
										<?php $listing->display_map($hash, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_directions'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], false, false); ?>
									</div>
								<?php endif; ?>
								<?php if (directorypress_is_reviews_allowed() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_position'] == 'intab'): ?>
									<div id="comments-tab" class="tab-pane fade" role="tabpanel">
										<?php comments_template( '', true ); ?>
									</div>
								<?php endif; ?>
								<?php if ($listing->package->videos_allowed && $listing->videos && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_video_position'] == 'intab'): ?>
									<div id="videos-tab" class="tab-pane fade" role="tabpanel">
										<?php foreach ($listing->videos AS $video): ?>
												<?php if (strlen($video['id']) == 11): ?>
													<iframe width="100%" height="400" class="directorypress-video-iframe fitvidsignore" src="//www.youtube.com/embed/<?php echo esc_attr($video['id']); ?>" frameborder="0" allowfullscreen></iframe>
												<?php elseif (strlen($video['id']) == 9): ?>
													<iframe width="100%" height="400" class="directorypress-video-iframe fitvidsignore" src="https://player.vimeo.com/video/<?php echo esc_attr($video['id']); ?>?color=d1d1d1&title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
												<?php endif; ?>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<?php foreach ($fields_groups AS $fields_group): ?>
									<div id="field-group-tab-<?php echo esc_attr($fields_group->id); ?>" class="tab-pane fade" role="tabpanel">
										<?php echo wp_kses_post($fields_group->display_output($listing)); ?>
									</div>
								<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
<?php endif; ?>

 