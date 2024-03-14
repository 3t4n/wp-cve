<?php 
global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
$listing_id = $GLOBALS['listing_id'];
$resurva_url = get_post_meta($listing_id, '_post_resurva_url', true);

if(!empty($resurva_url) && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_resurva_booking']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_resurva_booking'])):
 
	echo wp_kses_post($args['before_widget']);
		if (!empty($title)){
			echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
		}
		echo '<div class=" directorypress-widget directorypress_resurva_widget">';
			echo '<iframe src="'. esc_url($resurva_url) .'" name="resurva-frame" frameborder="0" width="450" height="450" style="max-width:100%"></iframe>';
		echo '</div>';
	echo wp_kses_post($args['after_widget']); 
endif;