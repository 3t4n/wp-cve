<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

global $DIRECTORYPRESS_ADIMN_SETTINGS;
?>
<?php if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_publish_date']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_publish_date']): ?>
	<div class="directorypress-listing-date" datetime="<?php echo date("Y-m-d", mysql2date('U', $listing->post->post_date)); ?>T<?php echo date("H:i", mysql2date('U', $listing->post->post_date)); ?>">
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_url']) .'" alt="'. esc_attr__('published', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon'] : 'dicode-material-icons dicode-material-icons-clock-time-five-outline'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo get_the_date(); ?>
	</div>
<?php endif; ?>


 