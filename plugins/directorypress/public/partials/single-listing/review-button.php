<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

global $DIRECTORYPRESS_ADIMN_SETTINGS;
$post_id = $listing->post->ID;

$text_string = ($button_text)? esc_html__('Add Review', 'DIRECTORYPRESS'): '';

?>
<a class="directorypress-add-review-link button-style-<?php echo esc_attr($button_style); ?>" href="#comments"><?php echo esc_html($text_string); ?></a>
	



 