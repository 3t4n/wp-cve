<?php
/*
* Handle files ajax calls
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// Handle Doc upload
add_action( 'wp_ajax_classic_upload_doc_callback', 'classic_upload_doc_callback' );
add_action( 'wp_ajax_nopriv_classic_upload_doc_callback', 'classic_upload_doc_callback' );
if (!function_exists('classic_upload_doc_callback')) {
	function classic_upload_doc_callback(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upf_classic_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = 'error - Could not verify POST values';
			echo json_encode($res_array);
			exit;
		}
		$user_id = get_current_user_id();
		
		if(isset($_FILES)){
			if(!empty($_FILES['docfile'])){
									
				$wp_checked = wp_check_filetype_and_ext( $_FILES['docfile']['tmp_name'], $_FILES['docfile']['name'], get_allowed_mime_types() );
				
				if( $wp_checked['ext'] && $wp_checked['type'] ){
				
					$doc_ttl = $doc_desc = '';
					if(isset($_POST['doc_ttl'])){
						$doc_ttl = sanitize_text_field($_POST['doc_ttl']);
					}
					if(isset($_POST['doc_desc'])){
						$doc_desc = sanitize_textarea_field($_POST['doc_desc']);
					}
					$uploaded_file = $_FILES['docfile']['tmp_name'];
					$type = $_FILES['docfile']['type'];
					$ext = $wp_checked['ext'];
					$filename = 'user_' . $user_id . '_doc_'.time().'.'.$ext;
					$upload_dir = wp_upload_dir();
					$upf_dir_path = $upload_dir['basedir'] . "/upf-docs";
					$upf_dir_url = $upload_dir['baseurl'] . "/upf-docs";
					$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upf_dir_path ) . DIRECTORY_SEPARATOR;
					$upload_file = move_uploaded_file($uploaded_file, $upload_path . $filename);
					
					if($upload_file){
						$attachment = array(
							'post_mime_type' => $type,
							'post_title'     => $doc_ttl,
							'post_content'     => $doc_desc,
							'post_status'    => 'inherit',
							'guid'           => $upf_dir_url . '/' . basename( $filename )
						);
						$attach_id = wp_insert_attachment( $attachment, $upf_dir_path . '/' . $filename );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $upf_dir_path . '/' . $filename);
						wp_update_attachment_metadata( $attach_id, $attach_data );
						if ( is_wp_error( $attach_id ) ) {
							$res_array['error'] = __("Error generating the details for your document! Please try later or contact us", "user-private-files");
						} else {
							$doc_src = $upf_dir_url.'/'.$filename;
							$res_array['new_doc_id'] = $attach_id;
							update_post_meta($attach_id, 'upf_doc', 'true');
							$res_array['doc_src'] = $doc_src;
							$res_array['doc_type'] = $type;
							$thumb_url = wp_get_attachment_image_src($attach_id, 'thumbnail');
							$res_array['thumb_url'] = $thumb_url[0];
							// do some action after insertion
							do_action('upf_file_inserted', $attach_id);
						}
					} else{
						$res_array['error'] = __("Error uploading the photo! Please try later.", "user-private-files");
					}
				} 
				else{
					$res_array['error'] = __("This file type is not allowed.", "user-private-files");
				}
				
				echo json_encode($res_array);
				exit;
				
			}
		}
		exit;
	}
}

// Add user to file - Ajax call add allow other users access to a file
add_action( 'wp_ajax_dpk_upvf_update_doc', 'dpk_upvf_update_doc' );
add_action( 'wp_ajax_nopriv_dpk_upvf_update_doc', 'dpk_upvf_update_doc' );
if (!function_exists('dpk_upvf_update_doc')) {
	function dpk_upvf_update_doc() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upf_classic_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = 'error - Could not verify POST values';
			echo json_encode($res_array);
			exit;
		}
		$docid = $usr_email = '';
		if(isset($_POST['docid'])){
			$docid = sanitize_text_field( $_POST['docid'] );
		}
		if(isset($_POST['usr_email'])){
			$usr_email = sanitize_email( $_POST['usr_email'] );
		}
		if(!$docid || !$usr_email){
			$res_array['error'] = 'error - Empty POST values';
			echo json_encode($res_array);
			exit;
		}
		$docid = str_replace('doc_', '', $docid);
		$req_user = get_user_by( 'email', $usr_email );
		
		if($req_user){
			$req_user_id = $req_user->ID;
			// convert the ID to string for better search operation
			$req_user_id = strval($req_user_id);
			
			$new_users = array();
			$curr_allowed_users = get_post_meta($docid, 'upf_allowed', true);
			if($curr_allowed_users){
				if (!in_array($req_user_id, $curr_allowed_users)){
					array_push($curr_allowed_users, $req_user_id);
				}
				$new_users = $curr_allowed_users;
			} else{
				$new_users[] = $req_user_id;
			}
			
			$allowed_users_updated = update_post_meta($docid, 'upf_allowed', $new_users);
			
			if(!$allowed_users_updated){
				$res_array['error'] = __("error - Unable to add users to this document", "user-private-files");
			}
			$res_array['added_user_email'] = $usr_email;
			$res_array['added_user_id'] = $req_user_id;
			echo json_encode($res_array);
			exit;
		}
	}
}

// Remove users from file access
add_action( 'wp_ajax_dpk_upvf_rmv_access', 'dpk_upvf_rmv_access' );
add_action( 'wp_ajax_nopriv_dpk_upvf_rmv_access', 'dpk_upvf_rmv_access' );
if (!function_exists('dpk_upvf_rmv_access')) {
	function dpk_upvf_rmv_access() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upf_classic_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo 'Could not verify POST values';
			exit;
		}
		$doc_id = sanitize_text_field( $_POST['doc_id'] );
		$doc_id = str_replace('doc_', '', $doc_id);
		$user_id = sanitize_text_field( $_POST['user'] );
		
		$curr_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
		if($curr_allowed_users){
			if (($key = array_search($user_id, $curr_allowed_users)) !== false) {
				unset($curr_allowed_users[$key]);
			}
		}
		$allowed_users_updated = update_post_meta($doc_id, 'upf_allowed', $curr_allowed_users);
		if(!$allowed_users_updated){
			$res_array['error'] = __("error - Unable to remove the user from this document. Please try again later or contact us.", "user-private-files");
		}
		$res_array['rmvd_usr'] = $user_id;
		$user_obj = get_userdata( $user_id );
		$res_array['rmvd_usr_email'] = $user_obj->user_email;
		
		echo json_encode($res_array);
		exit;
	}
}

// Remove a file
add_action( 'wp_ajax_dpk_upvf_rmv_file', 'dpk_upvf_rmv_file' );
add_action( 'wp_ajax_nopriv_dpk_upvf_rmv_file', 'dpk_upvf_rmv_file' );
if (!function_exists('dpk_upvf_rmv_file')) {
	function dpk_upvf_rmv_file() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upf_classic_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo 'Could not verify POST values';
			exit;
		}
		$doc_id = sanitize_text_field( $_POST['doc_id'] );
		$doc_id = str_replace('doc_', '', $doc_id);
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $doc_id);
		if($curr_user_id == $doc_author){
			$file_deleted = wp_delete_post($doc_id);
			if(!$file_deleted){
				$res_array['error'] = __("error - Unable to remove this file. Please try again later or contact us.", "user-private-files");
			}
			$res_array['rmvd_file'] = true;
		}
		echo json_encode($res_array);
		exit;
	}
}
