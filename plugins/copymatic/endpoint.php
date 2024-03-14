<?php
$wp_load = $_SERVER['DOCUMENT_ROOT'].'wp-load.php';
if(file_exists($wp_load))
  require_once($wp_load);
elseif(file_exists($_SERVER['DOCUMENT_ROOT']."/wordpress/wp-load.php")) 
  require_once($_SERVER['DOCUMENT_ROOT']."/wordpress/wp-load.php");

require_once('copymatic.php');
$post_values = file_get_contents('php://input');
if(!empty($post_values)){
	$website_key = esc_html(get_option('copymatic_website_key'));
	if(empty($website_key)){die();}
	$post = json_decode($post_values, true);
	$title = $content = '';
	if(!empty($post['title'])){
		$title = trim($post['title']);
	}
	if(empty($post['content'])){echo json_encode(array('success'=>false,'reason'=>'Empty content'));die();}
	$new_post = array(
		'post_title' => $title,
		'post_content' => $post['content'],
		'post_status' => 'draft',
		'post_date' => date('Y-m-d H:i:s'),
		'post_type' => 'post',
		'post_category' => array(0)
	);
	$post_id = wp_insert_post($new_post);
	if(!empty($post_id)){
		if(!empty($post['featured_image'])){
			// Include required WordPress files for handling media uploads
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');

			// Download image and create an attachment
			$image_url = $post['featured_image'];
			$upload_dir = wp_upload_dir(); // Get WordPress upload directory
			$image_data = file_get_contents($image_url);
			$filename = basename($image_url);
			if(wp_mkdir_p($upload_dir['path'])){
				$file = $upload_dir['path'] . '/' . $filename;
			}else{
				$file = $upload_dir['basedir'] . '/' . $filename;
			}
			file_put_contents($file, $image_data);

			// Check the type of file and create the attachment
			$wp_filetype = wp_check_filetype($filename, null);
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			// Insert the attachment
			$attach_id = wp_insert_attachment($attachment, $file, $post_id);
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata($attach_id, $file);
			wp_update_attachment_metadata($attach_id, $attach_data);

			// Set as featured image
			set_post_thumbnail($post_id, $attach_id);
		}
		if (class_exists('RankMath')) {
			// Rank Math is installed and active
			if(!empty($post['meta_title'])){
				update_post_meta($post_id, 'rank_math_title', $post['meta_title']);
			}
			if(!empty($post['meta_description'])){
				update_post_meta($post_id, 'rank_math_description', $post['meta_description']);
			}
			if(!empty($post['keyword'])){
				update_post_meta($post_id, 'rank_math_focus_keyword', $post['keyword']);
			}
		}
		if (defined('WPSEO_VERSION')) {
			// Yoast SEO is installed and active
			if(!empty($post['meta_title'])){
				update_post_meta($post_id, '_yoast_wpseo_title', $post['meta_title']);
			}
			if(!empty($post['meta_description'])){
				update_post_meta($post_id, '_yoast_wpseo_metadesc', $post['meta_description']);
			}
		}
		echo json_encode(array('success'=>true));
	}else{
		echo json_encode(array('success'=>false));
	}
}else{
	echo json_encode(array('success'=>false));
}
die();
?>