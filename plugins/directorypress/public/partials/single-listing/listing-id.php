<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;

?>
<?php if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_id']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_id']): ?>
	<div class="listing-id">
		
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_url']) .'" alt="'. esc_attr__('id', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon'] : 'dicode-material-icons dicode-material-icons-bookmark-outline'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo sprintf(__('Id: %d', 'DIRECTORYPRESS'), $listing->post->ID); ?>
	</div>
<?php endif; ?>
 