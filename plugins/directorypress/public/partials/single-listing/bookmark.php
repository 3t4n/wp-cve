<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$add_string = ($button_text)? esc_html__('Bookmark', 'DIRECTORYPRESS'): '';
$remove_string = ($button_text)? esc_html__('Bookmarked', 'DIRECTORYPRESS'): '';
$tooltip_add = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Bookmark', 'DIRECTORYPRESS').'"':'';
$tooltip_remove = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Remove Bookmark', 'DIRECTORYPRESS').'"':'';
$in_favourites_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon'] : 'dicode-material-icons dicode-material-icons-heart';
$not_in_favourites_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon'] : 'dicode-material-icons dicode-material-icons-heart-outline';
?>
<?php if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_favourites_list']): ?>
	<?php if (directorypress_bookmark_list($listing->post->ID)): ?>
		<a href="javascript:void(0);" class="bookmark-button add_to_favourites btn button-style-<?php echo esc_attr($button_style); ?>" data-listingid="<?php echo esc_attr($listing->post->ID); ?>" data-in_favourites_icon="<?php echo esc_attr($in_favourites_icon); ?>" data-not_in_favourites_icon="<?php echo esc_attr($not_in_favourites_icon); ?>" <?php echo esc_attr($remove_string); ?>>
			
			<?php
				if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_type'] == 'img'){
					echo '<img class="style1 checked" src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_url']) .'" alt="'. esc_attr__('Remove', 'DIRECTORYPRESS') .'"/>';
				}else{
					
					echo '<i class="favourite-icon style1 checked '. esc_attr($in_favourites_icon) .'"></i>';
				}
			?>
			<?php echo '<span class="bookmark-button-text">'. esc_html($remove_string) .'</span>'; ?>
		</a>
	<?php else: ?>
		
		<a href="javascript:void(0);" class="bookmark-button add_to_favourites btn button-style-<?php echo esc_attr($button_style); ?>" data-listingid="<?php echo esc_attr($listing->post->ID); ?>" data-in_favourites_icon="<?php echo esc_attr($in_favourites_icon); ?>" data-not_in_favourites_icon="<?php echo esc_attr($not_in_favourites_icon); ?>" <?php echo wp_kses_post($tooltip_remove); ?>>
			<?php
				if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_type'] == 'img'){
					echo '<img class="style1 unchecked" src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_url']) .'" alt="'. esc_attr__('Bookmark', 'DIRECTORYPRESS') .'"/>';
				}else{
					
					echo '<i class="favourite-icon style1 unchecked '. esc_attr($not_in_favourites_icon) .'"></i>';
				}
			?>
			<?php echo '<span class="bookmark-button-text">'. esc_html($add_string) .'</span>'; ?>
		</a>
	<?php endif; ?>
<?php endif; ?>