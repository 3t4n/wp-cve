<?php

function photo_gallery_generate_image_links( $item_data, $item, $settings ){

	$image_full = '';
	$image_url = '';

	// If the image is not resized we will try to resized it now
	// This is safe to call every time, as resize_image() will check if the image already exists, preventing thumbnails from being generated every single time.
	$resizer = new Photo_Gallery_Image();

	$gallery_type = isset( $settings['type'] ) ? $settings['type'] : 'creative-gallery';
	$grid_sizes = array(
		'width' => isset( $item['width'] ) ? $item['width'] : 1,
		'height' => isset( $item['height'] ) ? $item['height'] : 1,
	);
	$sizes = $resizer->get_image_size( $item['id'], $settings['img_size'], $gallery_type, $grid_sizes );
	$image_full = $sizes['url'];
	$image_url = $resizer->resize_image( $sizes['url'], $sizes['width'], $sizes['height'] );

	// If we couldn't resize the image we will return the full image.
	if ( is_wp_error( $image_url ) ) {
		$image_url = $image_full;
	}

	$item_data['image_full'] = $image_full;
	$item_data['image_url']  = $image_url;

	// Add src/data-src attributes to img tag
	$item_data['img_attributes']['src'] = $image_url;
	$item_data['img_attributes']['data-src'] = $image_url;

	return $item_data;
}



