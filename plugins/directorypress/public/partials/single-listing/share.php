<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

global $DIRECTORYPRESS_ADIMN_SETTINGS;
$post_id = $listing->post->ID;

$text_string = ($button_text)? esc_html__('Share', 'DIRECTORYPRESS'): '';
$tooltip = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Share', 'DIRECTORYPRESS').'"':'';
$enabled = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_share_buttons']['enabled'];
unset($enabled['placebo']);
?>

<?php if (!empty($enabled)): ?>
	<a class="share-button directorypress-sharing-link button-style-<?php echo esc_attr($button_style); ?>"  data-popup-open="single_sharing_data" href="#" <?php echo wp_kses_post($tooltip); ?>>
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_url']) .'" alt="'. esc_attr__('share', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon'] : 'dicode-material-icons dicode-material-icons-share-variant'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo esc_html($text_string); ?>
	</a>
	<div class="directorypress-custom-popup" data-popup="single_sharing_data">
		<div class="directorypress-custom-popup-inner single-contact">
			<div class="directorypress-popup-title"><?php echo esc_html__('Share This Listing', 'DIRECTORYPRESS'); ?><a class="directorypress-custom-popup-close" data-popup-close="single_sharing_data" href="#"><i class="far fa-times-circle"></i></a></div>
			<div class="directorypress-popup-content">
				<div class="directorypress-share-buttons">
					<?php foreach ($enabled AS $button): ?>	
						<div class="directorypress-share-button">
							<?php directorypress_social_sharing_display($post_id, $button); ?>
						</div>	
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>



 