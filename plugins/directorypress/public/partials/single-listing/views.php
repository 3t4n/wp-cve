<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

global $DIRECTORYPRESS_ADIMN_SETTINGS;
?>
<?php if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_views']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_views']): ?>
	<div class="listing-views">
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_url']) .'" alt="'. esc_attr__('published', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon'] : 'dicode-material-icons dicode-material-icons-eye-outline'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo sprintf(__('Views: %d', 'DIRECTORYPRESS'), (get_post_meta($listing->post->ID, '_total_clicks', true) ? get_post_meta($listing->post->ID, '_total_clicks', true) : 0)); ?>
	</div>
<?php endif; ?>