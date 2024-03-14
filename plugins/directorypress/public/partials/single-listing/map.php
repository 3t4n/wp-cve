<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
if (directorypress_has_map() && ($DIRECTORYPRESS_ADIMN_SETTINGS['map_on_single_listing_tab'] && $listing->is_map() && $listing->locations && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_map_position'] == 'notab')): ?>
	<div class="single-map notabs">
		<span class="directorypress-video-field-name"><?php echo esc_html__('Map View', 'DIRECTORYPRESS'); ?></span>
		<?php $listing->display_map($hash, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_directions'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], false, false); ?>
	</div>
<?php endif; ?>

 