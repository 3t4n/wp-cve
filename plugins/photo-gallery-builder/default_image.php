<?php
$file = PHOTO_GALLERY_BUILDER_IMAGES.'default-img.jpg';
$filename = basename($file);

// The ID of the post this attachment is for 
$parent_post_id = 0;

if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}

$upload_file = wp_upload_bits($filename, null, file_get_contents($file));

// Get the path to the upload directory.
$wp_upload_dir = wp_upload_dir();


if (!$upload_file['error']) {
	$wp_filetype = wp_check_filetype($filename, null );

	$attachment = array(
		'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ), 
		'post_mime_type' => $wp_filetype['type'],
		'post_parent' => $parent_post_id,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);

	
	$attachment_id_1 =wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
	$attachment_id_2 =wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
	$attachment_id_3 =wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
	
	
	if (!is_wp_error($attachment_id_1)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id_1, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id_1,  $attachment_data );
	}
	if (!is_wp_error($attachment_id_2)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id_2, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id_2,  $attachment_data );
	}
	if (!is_wp_error($attachment_id_3)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id_3, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id_3,  $attachment_data );
	}

}

update_option( "pgb-photo-gallery-images-default", 
    	array(
		    array(
				'id' => $attachment_id_1,
				'alt' => '',
				'title' => 'Lorem lipsum 1',
				'description' => 'Lorem lipsum 1 Description',
				'halign' => 'center',
				'valign' => 'middle',
				'full' => PHOTO_GALLERY_BUILDER_IMAGES . 'default-img.jpg',
				'thumbnail' => PHOTO_GALLERY_BUILDER_IMAGES . 'default-img.jpg',
				'orientation' => '',
				'link' => 'https://www.google.com/',
				'target' => 1,
				'width' => 2,
				'height' => 2,
			),									    
			array(
				'id' => $attachment_id_2,
				'alt' => '',
				'title' => 'Lorem lipsum 2',
				'description' => 'Lorem lipsum 2 Description',
				'halign' => 'center',
				'valign' => 'middle',
				'full' => PHOTO_GALLERY_BUILDER_IMAGES . 'default-img.jpg',
				'thumbnail' => PHOTO_GALLERY_BUILDER_IMAGES . 'default-img.jpg',
				'orientation' => '',
				'link' => 'https://www.google.com/',
				'target' => 1,
				'width' => 2,
				'height' => 2,
			),									    
			array(
				'id' => $attachment_id_3,
				'alt' => '',
				'title' => 'Lorem lipsum 3',
				'description' => 'Lorem lipsum 3 Description',
				'halign' => 'center',
				'valign' => 'middle',
				'full' => PHOTO_GALLERY_BUILDER_IMAGES . 'default-img.jpg',
				'thumbnail' => PHOTO_GALLERY_BUILDER_IMAGES . 'default-img.jpg',
				'orientation' => '',
				'link' => 'https://www.google.com/',
				'target' => 1,
				'width' => 2,
				'height' => 2,
			),
		) 
    );

?>