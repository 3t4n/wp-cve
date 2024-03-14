<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


add_filter( 'plugin_action_links_classified-ads/classified-ads.php', 'classified_ads_tools' );
function classified_ads_tools( $links ) {
	// Build and escape the URL.
	$url = esc_url( get_admin_url().'tools.php?page=classified_ads_import' );
	// Create the link.
	$settings_link = "<a style=\"color:blue;font-weight:bold;\" href='$url'>" . __( 'Open Classified Ads Plugin Tools', 'classified-ads') . '</a>';
	// Adds the link to the end of the array.
    $links[] = $settings_link;
	return $links;
}

if(file_exists(CLASSIFIED_ADS_PATH . 'extensions/theme-classifield.php')) {
	require_once CLASSIFIED_ADS_PATH . 'extensions/theme-classifield.php';
	new \Classified_Ads\Extensions\Themes\Classified();
}
?>