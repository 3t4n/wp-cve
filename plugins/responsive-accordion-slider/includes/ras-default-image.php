<?php
$file = RESP_ACCORDION_SLIDER_IMAGES_PATH.'default-img.jpg';
$file1 = RESP_ACCORDION_SLIDER_IMAGES_PATH.'default-img1.jpg';
$file2 = RESP_ACCORDION_SLIDER_IMAGES_PATH.'default-img2.jpg';
$filename = basename($file);
$filename1 = basename($file1);
$filename2 = basename($file2);


// The ID of the post this attachment is for.
$parent_post_id = 0;
$parent_post_id1 = 1;
$parent_post_id2 = 2;

if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}

$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
$upload_file1 = wp_upload_bits($filename1, null, file_get_contents($file1));
$upload_file2 = wp_upload_bits($filename2, null, file_get_contents($file2));

// Get the path to the upload directory
$wp_upload_dir = wp_upload_dir();


if (!$upload_file['error']) {
	$wp_filetype = wp_check_filetype($filename, null );
	$wp_filetype1 = wp_check_filetype($filename1, null );
	$wp_filetype2 = wp_check_filetype($filename2, null );

	$attachment = array(
		'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ), 
		'post_mime_type' => $wp_filetype['type'],
		'post_parent' => $parent_post_id,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);

	$attachment1 = array(
		'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ), 
		'post_mime_type' => $wp_filetype1['type'],
		'post_parent' => $parent_post_id1,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);

	$attachment2 = array(
		'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ), 
		'post_mime_type' => $wp_filetype2['type'],
		'post_parent' => $parent_post_id2,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_content' => '',
		'post_status' => 'inherit'
	);

	
	$attachment_id_1 = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
	$attachment_id_2 = wp_insert_attachment( $attachment1, $upload_file1['file'], $parent_post_id1 );
	$attachment_id_3 = wp_insert_attachment( $attachment2, $upload_file2['file'], $parent_post_id2 );
	
	
	if (!is_wp_error($attachment_id_1)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id_1, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id_1,  $attachment_data );
	}
	if (!is_wp_error($attachment_id_2)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id_2, $upload_file1['file'] );
		wp_update_attachment_metadata( $attachment_id_2,  $attachment_data );
	}
	if (!is_wp_error($attachment_id_3)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id_3, $upload_file2['file'] );
		wp_update_attachment_metadata( $attachment_id_3,  $attachment_data );
	}

}

update_option( "ras-slider-images-default", 
    	array(
		    array(
				'id' => $attachment_id_1,
				'alt' => 'Read More',
				'title' => 'Lorem ipsum dolor sit amet',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet erat eu eros dapibus convallis quis nec odio. Nam auctor lorem et est mollis, eu molestie quam interdum.',
				'halign' => 'center',
				'valign' => 'middle',
				'full' => RESP_ACCORDION_SLIDER_IMAGES_PATH . 'default-img.jpg',
				'thumbnail' => RESP_ACCORDION_SLIDER_IMAGES_PATH . 'default-img.jpg',
				'orientation' => '',
				'alt' => 'Read More',
				'link' => 'https://www.google.com',
				'target' => 1,
				'width' => 2,
				'height' => 2,
			),									    
			array(
				'id' => $attachment_id_2,
				'alt' => '',
				'title' => 'Lorem ipsum dolor sit amet',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet erat eu eros dapibus convallis quis nec odio. Nam auctor lorem et est mollis, eu molestie quam interdum.',
				'halign' => 'center',
				'valign' => 'middle',
				'full' => RESP_ACCORDION_SLIDER_IMAGES_PATH . 'default-img1.jpg',
				'thumbnail' => RESP_ACCORDION_SLIDER_IMAGES_PATH . 'default-img1.jpg',
				'orientation' => '',
				'alt' => 'Read More',
				'link' => 'https://www.google.com',
				'target' => 1,
				'width' => 2,
				'height' => 2,
			),									    
			array(
				'id' => $attachment_id_3,
				'alt' => '',
				'title' => 'Lorem ipsum dolor sit amet',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet erat eu eros dapibus convallis quis nec odio. Nam auctor lorem et est mollis, eu molestie quam interdum.',
				'halign' => 'center',
				'valign' => 'middle',
				'full' => RESP_ACCORDION_SLIDER_IMAGES_PATH . 'default-img2.jpg',
				'thumbnail' => RESP_ACCORDION_SLIDER_IMAGES_PATH . 'default-img2.jpg',
				'orientation' => '',
				'alt' => 'Read More',
				'link' => 'https://www.google.com',
				'target' => 1,
				'width' => 2,
				'height' => 2,
			),
		) 
    );

?>