<?php
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object, $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page FROM '.$wpdb->prefix.'directorypress_fields');
	if(isset($listing->logo_image) && !empty($listing->logo_image)){
		$image_src_array_w = wp_get_attachment_image_src($listing->logo_image, 'full');
		$image_src_w = $image_src_array_w[0];
	}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])){
		$image_src_array_w = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
		$image_src_w = $image_src_array_w;
	}else{
		$image_src_w = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
	}
	$param = array(
		'width' => 150,
		'height' => 150,
		'crop' => true
	);
	// style olx style
	echo '<figure class="directorypress-listing-figure">';
		echo '<a href="'.get_permalink().'"><img alt="'. esc_attr($listing->title()).'" src="'. esc_url(bfi_thumb($image_src_w, $param)).'" width="150" height="150" /><span class="listing-widget-hover-overlay"><i class="directorypress-icon-share"></i></span></a>';
	echo '</figure>';