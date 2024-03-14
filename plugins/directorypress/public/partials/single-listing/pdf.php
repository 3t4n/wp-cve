<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$text_string = ($button_text)? esc_html__('Download', 'DIRECTORYPRESS'): '';
$tooltip = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Save listing in PDF', 'DIRECTORYPRESS').'"':'';
?>
<?php if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pdf_button']): ?>
	<a href="javascript:void(0);" class="download-button directorypress-pdf-listing-link btn button-style-<?php echo esc_attr($button_style); ?>" onClick="window.open('http://pdfmyurl.com/?url=<?php echo add_query_arg('directorypress_action', 'pdflisting', get_permalink($listing->post->ID)); ?>');" <?php echo wp_kses_post($tooltip); ?>>
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_url']) .'" alt="'. esc_attr__('download', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon'] : 'dicode-material-icons dicode-material-icons-file-pdf-outline'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo esc_html($text_string); ?>
	</a>
<?php endif; ?>