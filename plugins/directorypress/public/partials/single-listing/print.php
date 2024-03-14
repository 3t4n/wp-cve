<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$text_string = ($button_text)? esc_html__('Print', 'DIRECTORYPRESS'): '';
$tooltip = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Print Listing', 'DIRECTORYPRESS').'"':'';
?>
<?php if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_print_button']): ?>
	<script>
		var window_width = 860;
		var window_height = 800;
		var leftPosition, topPosition;
		(function($) {
			"use strict";
				
			$(function() {
				leftPosition = (window.screen.width / 2) - ((window_width / 2) + 10);
				topPosition = (window.screen.height / 2) - ((window_height / 2) + 50);
			});
		})(jQuery);
	</script>
	<a href="javascript:void(0);" class="print-button directorypress-print-listing-link btn button-style-<?php echo esc_attr($button_style); ?>" onClick="window.open('<?php echo add_query_arg('directorypress_action', 'printlisting', get_permalink($listing->post->ID)); ?>', 'print_window', 'height='+window_height+',width='+window_width+',left='+leftPosition+',top='+topPosition+',menubar=yes,scrollbars=yes');" <?php echo wp_kses_post($tooltip); ?>>
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_url']) .'" alt="'. esc_attr__('Print', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon'] : 'dicode-material-icons dicode-material-icons-printer-wireless'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo esc_html($text_string); ?>
	</a>	
<?php endif; ?>



 