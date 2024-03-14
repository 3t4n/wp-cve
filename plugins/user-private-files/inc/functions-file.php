<?php
/*
* Handle files ajax calls
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// Handle Doc upload
add_action( 'wp_ajax_upvf_pro_upload_doc_callback', 'upvf_pro_upload_doc_callback' );
add_action( 'wp_ajax_nopriv_upvf_pro_upload_doc_callback', 'upvf_pro_upload_doc_callback' );
if (!function_exists('upvf_pro_upload_doc_callback')) {
	function upvf_pro_upload_doc_callback(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		$user_id = get_current_user_id();
		
		if(isset($_FILES)){
			if(!empty($_FILES['docfile'])){
				// check for allowed file types
				$wp_checked = wp_check_filetype_and_ext( $_FILES['docfile']['tmp_name'], $_FILES['docfile']['name'], get_allowed_mime_types() );
				if( $wp_checked['ext'] && $wp_checked['type'] ){
					
					$max_upload_size = wp_max_upload_size();
					$file_size = $_FILES['docfile']['size'];
					
					if( $file_size <= $max_upload_size ){
						
						$doc_ttl = $doc_desc = '';
						if(isset($_POST['doc_ttl'])){
							$doc_ttl = sanitize_text_field($_POST['doc_ttl']);
						}
						
						$filename = 'usr' . $user_id . '_' . time() . '_' . $_FILES["docfile"]['name'];
						
						add_filter( 'upload_dir', 'upfp_modify_upload_dir' );
						$upload_file = wp_upload_bits($filename, null, file_get_contents($_FILES["docfile"]["tmp_name"]));
						remove_filter( 'upload_dir', 'upfp_modify_upload_dir' );
						
						if($upload_file){
							$attachment = array(
								'post_mime_type' => $upload_file['type'],
								'post_title'     => $doc_ttl,
								'post_status'    => 'inherit',
								'guid'           => $upload_file['url']
							);
							
							$attach_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );
							
							$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
							wp_update_attachment_metadata( $attach_id, $attach_data );
							
							if ( is_wp_error( $attach_id ) ) {
								$res_array['error'] = __("Error generating the details for your file! Please try later or contact us", "user-private-files");
							} else {
								$doc_src = $upload_file['url'];
								$res_array['new_doc_id'] = $attach_id;
								update_post_meta($attach_id, 'upf_doc', 'true');
								$res_array['doc_src'] = $doc_src;
								$res_array['doc_ttl'] = $doc_ttl;
								$thumb_url = wp_get_attachment_image_src($attach_id, 'thumbnail');
								$res_array['thumb_url'] = $thumb_url[0];
								// do some action after insertion
								do_action('upf_file_inserted', $attach_id);
							}
						} else{
							$res_array['error'] = __("Error uploading the file! Please try later.", "user-private-files");
						}
						
					} else{
						$res_array['error'] = __("Uploaded file exceeds the maximum upload size for this site", "user-private-files");
					}
				}
				else{
					$res_array['error'] = __("This file type is not allowed for uploading.", "user-private-files");
				}
				
				echo json_encode($res_array);
				exit;
				
			}
		}
		exit;
	}
}

// File preview & options
add_action( 'wp_ajax_upvf_pro_preview_file', 'upvf_pro_preview_file' );
add_action( 'wp_ajax_nopriv_upvf_pro_preview_file', 'upvf_pro_preview_file' );
if (!function_exists('upvf_pro_preview_file')) {
	function upvf_pro_preview_file() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		if(isset($_POST['doc_id'])){
			$doc_id = sanitize_text_field($_POST['doc_id']);
			$doc_id = (int)str_replace('doc_', '', $doc_id);

            $curr_user_id = get_current_user_id();
            $curr_file_acs_users = get_post_meta($doc_id, 'upf_acs_full', true);
            $curr_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
            $doc_author = get_post_field('post_author', $doc_id);
           
            if ($curr_user_id == $doc_author || (is_array($curr_allowed_users) && in_array($curr_user_id, $curr_allowed_users))){
                $res_array['doc_ttl'] = get_the_title($doc_id);
                $res_array['doc_src'] = wp_get_attachment_url($doc_id);
                $res_array['doc_desc'] = get_post_field('post_content', $doc_id);
                
                $inside_shared = '';
                if(isset($_POST['inside_shared'])){
                    $inside_shared = sanitize_text_field($_POST['inside_shared']);
                }
                
                $res_array['file_type'] = '';
                $mime_type = get_post_mime_type($doc_id);
                if (strpos($mime_type, 'image') !== false) {
                    $res_array['file_type'] = 'img';
                }
                else if (strpos($mime_type, 'video') !== false) {
                    $res_array['file_type'] = 'vdo';
                }
                
                // Get comments
                $comments_args = array(
                        'post_id'	=> $doc_id,
                        'type'		=> 'upfp_comment'
                    );
                    
                $all_comments = get_comments($comments_args);
                
                $cmnts_html = '';
                foreach($all_comments as $comment){
                    
                    $cmnt_content = $comment->comment_content;
                    $cmnt_user_id = $comment->user_id;
                    
                    $cmnt_usr_avatar = get_avatar( $cmnt_user_id, 32 );
                    $user_info = get_userdata($cmnt_user_id);
                    $cmnt_user_email = $user_info->user_email;
                    
                    $cmnts_html .= '<p>
                        <span class="cmnt_usr_avatar">' . $cmnt_usr_avatar . '</span>
                        <span class="cmnt_usr_email">' . $cmnt_user_email . '</span>
                        <span class="cmnt_usr_cmnt">' . $cmnt_content . '</span>
                    </p>';
                    
                }
                
                $res_array['cmnts_html'] = $cmnts_html;
                
                // if not shared file
                if(!$inside_shared){
                    $alwd_emails = array();
                    
                    if($curr_allowed_users){
                        foreach($curr_allowed_users as $alwd_usr){
                            $is_author = 0;
                            $alwd_usr_obj = get_userdata( $alwd_usr );
                            if($alwd_usr_obj){
                                
                                if (in_array($alwd_usr, $curr_file_acs_users)){
                                    if( get_post_field ('post_author', $doc_id) == $alwd_usr ){
                                        $acs_lvl = __('Author', 'user-private-files');
                                        $is_author = 1;
                                    } else{
                                        $acs_lvl = __('Full Access', 'user-private-files');
                                    }
                                } else{
                                    $acs_lvl = __('View Only', 'user-private-files');
                                }
                                $alwd_emails[] = array($alwd_usr, esc_html( get_the_author_meta( 'nickname', $alwd_usr) ), $acs_lvl, $is_author);
                                
                            }
                        }
                    }
                    $res_array['alwd_emails'] = $alwd_emails;
                } 
                else{
                    
                    if($curr_file_acs_users){
                        $req_user_id = get_current_user_id();
                        if (in_array($req_user_id, $curr_file_acs_users)){
                            $res_array['full_access'] = 1;
                            // Load allowed users
                            $alwd_emails = array();
                            $curr_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
                            if($curr_allowed_users){
                                foreach($curr_allowed_users as $alwd_usr){
                                    $is_author = 0;
                                    $alwd_usr_obj = get_userdata( $alwd_usr );
                                    if($alwd_usr_obj){
                                        if (in_array($alwd_usr, $curr_file_acs_users)){
                                            if( get_post_field ('post_author', $doc_id) == $alwd_usr ){
                                                $acs_lvl = __('Author', 'user-private-files');
                                                $is_author = 1;
                                            } else{
                                                $acs_lvl = __('Full Access', 'user-private-files');
                                            }
                                        } else{
                                            $acs_lvl = __('View Only', 'user-private-files');
                                        }
                                        $alwd_emails[] = array($alwd_usr, esc_html( get_the_author_meta( 'nickname', $alwd_usr) ), $acs_lvl, $is_author);
                                    }
                                }
                            }
                            $res_array['alwd_emails'] = $alwd_emails;
                        }
                    }
                    
                }
                
                $author_id = get_post_field ('post_author', $doc_id);
                $res_array['author'] = get_the_author_meta('user_email', $author_id);
            } else {
                $res_array['error'] = __('You do not have permission to view this file.', 'user-private-files');
            }
			
			echo json_encode($res_array);
			exit;
			
		}
		
	}
}

// Add user to file - Ajax call add allow other users access to a file
add_action( 'wp_ajax_upvf_pro_update_doc', 'upvf_pro_update_doc' );
add_action( 'wp_ajax_nopriv_upvf_pro_update_doc', 'upvf_pro_update_doc' );
if (!function_exists('upvf_pro_update_doc')) {
	function upvf_pro_update_doc() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		$docid = $usr_email = '';
		if(isset($_POST['docid'])){
			$docid = sanitize_text_field( $_POST['docid'] );
		}
		if(isset($_POST['access_lvl'])){
			$access_lvl = sanitize_text_field( $_POST['access_lvl'] );
		}
		if(isset($_POST['usr_email'])){
			$usr_email = sanitize_text_field( $_POST['usr_email'] );
		}
		
		if(!$docid || !$usr_email){
			$res_array['error'] = __('error - Empty POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$docid = str_replace('doc_', '', $docid);
		$req_user = get_user_by( 'email', $usr_email );
		
		if(!$req_user){
			$req_user = get_user_by( 'login', $usr_email );
		}
		
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $docid);
		$curr_acs_users = get_post_meta($docid, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
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
				} else{
					
					// Set access level
					$new_acs_users = array();
					if($access_lvl == 'full'){
						if($curr_acs_users){
							if (!in_array($req_user_id, $curr_acs_users)){
								array_push($curr_acs_users, $req_user_id);
							}
							$new_acs_users = $curr_acs_users;
						} else{
							$new_acs_users[] = $req_user_id;
						}
						update_post_meta($docid, 'upf_acs_full', $new_acs_users);
					} else{
						if($curr_acs_users){
							if (($key = array_search($req_user_id, $curr_acs_users)) !== false) {
								unset($curr_acs_users[$key]);
							}
						}
						update_post_meta($docid, 'upf_acs_full', $curr_acs_users);
					}
					
					// send email to the user
					$upfp_enable_email = get_option('upfp_enable_email');
					if($upfp_enable_email == 'yes'){
						$to = $req_user->user_email;;
						
						$curr_user = get_user_by( 'id', $curr_user_id );
						$curr_user_usrname = $curr_user->user_login;
						
						// prepare subject
						$upfp_email_subject = get_option('upfp_email_subject');
						if($upfp_email_subject == ''){
							$upfp_email_subject = __("{user} shared a file / folder with you on " . get_bloginfo( 'name' ), "user-private-files");
						}
						$upfp_email_subject = str_replace("{user}", $curr_user_usrname, $upfp_email_subject);
						
						// prepare content
						$upfp_email_content = html_entity_decode( get_option('upfp_email_content') );
						if($upfp_email_content == ''){
							$upfp_email_content = __("{user} shared a file / folder with you on " . get_bloginfo( 'name' ) . ". Login and check your files.", "user-private-files");
						}
						$upfp_email_content = str_replace("{user}", $curr_user_usrname, $upfp_email_content);
						
						// write the email content
						$header = '';
						$header .= "MIME-Version: 1.0\n";
						$header .= "Content-Type: text/html; charset=utf-8\n";
						
						$is_sent = wp_mail($to, $upfp_email_subject, $upfp_email_content, $header);
						
						if(!$is_sent){
							$res_array['error'] = __("Error - File is shared but unable to send email to the user. Please contact us.", "user-private-files");
						}
						
					}
					
					$alwd_emails = array();
					$curr_allowed_users = get_post_meta($docid, 'upf_allowed', true);
					$curr_fldr_acs_users = get_post_meta($docid, 'upf_acs_full', true);
					
					if($curr_allowed_users){
						foreach($curr_allowed_users as $alwd_usr){
							$alwd_usr_obj = get_userdata( $alwd_usr );
							if($alwd_usr_obj){
								$is_author = 0;
								if (in_array($alwd_usr, $curr_fldr_acs_users)){
									if( get_post_field ('post_author', $docid) == $alwd_usr ){
										$acs_lvl = __('Author', 'user-private-files');
										$is_author = 1;
									} else{
										$acs_lvl = __('Full Access', 'user-private-files');
									}
								} else{
									$acs_lvl = __('View Only', 'user-private-files');
								}
								$alwd_emails[] = array($alwd_usr, esc_html( get_the_author_meta( 'nickname', $alwd_usr) ), $acs_lvl, $is_author);
							}
						}
					}
					
					$res_array['alwd_emails'] = $alwd_emails;
				}
				
			} else{ // no user found
				$res_array['error'] = __('No user found with this email address or username.', 'user-private-files');
			}
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Add role to file
add_action( 'wp_ajax_upvf_pro_add_bulk', 'upvf_pro_add_bulk' );
add_action( 'wp_ajax_nopriv_upvf_pro_add_bulk', 'upvf_pro_add_bulk' );
if (!function_exists('upvf_pro_add_bulk')) {
	function upvf_pro_add_bulk() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		$docid = $usr_type = '';
		if(isset($_POST['docid'])){
			$docid = sanitize_text_field( $_POST['docid'] );
		}
		if(isset($_POST['access_lvl'])){
			$access_lvl = sanitize_text_field( $_POST['access_lvl'] );
		}
		if(isset($_POST['usr_type'])){
			$usr_type = sanitize_text_field( $_POST['usr_type'] );
		}
		
		if(!$docid || !$usr_type){
			$res_array['error'] = __('error - Empty POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$docid = str_replace('doc_', '', $docid);
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $docid);
		$curr_acs_users = get_post_meta($docid, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
			if($usr_type == 'role'){
				if(isset($_POST['usr_role'])){
					$usr_role = sanitize_text_field( $_POST['usr_role'] );
					$user_args = array(
						'fields' => array( 'id', 'user_email' ),
						'role' => $usr_role,
						'exclude' => $curr_user_id
					);
				}
			} else if($usr_type == 'all'){
				$user_args = array(
					'fields' => array( 'id', 'user_email' ),
					'exclude' => $curr_user_id
				);
			}
			
			$selected_users = get_users($user_args);
			
			$new_users = array();
			$curr_allowed_users = get_post_meta($docid, 'upf_allowed', true);
			$old_alwd_users = $curr_allowed_users;
			if($curr_allowed_users){
				
				foreach( $selected_users as $usr ){
					$user_id = strval($usr->id);
					if (!in_array($user_id, $curr_allowed_users)){
						array_push($curr_allowed_users, $user_id);
					}
					$new_users = $curr_allowed_users;
				}
			} else{
				foreach( $selected_users as $usr ){
					$user_id = strval($usr->id);
					$new_users[] = $user_id;
				}
			}
			
			$allowed_users_updated = update_post_meta($docid, 'upf_allowed', $new_users);
			if(!$allowed_users_updated){
				$res_array['error'] = __("error - Unable to add users to this document", "user-private-files");
			} else{
				
				// Set access level
				foreach( $selected_users as $usr ){
					$req_user_id = strval( $usr->id );
					$new_acs_users = array();
					if($access_lvl == 'full'){
						if($curr_acs_users){
							if (!in_array($req_user_id, $curr_acs_users)){
								array_push($curr_acs_users, $req_user_id);
							}
							$new_acs_users = $curr_acs_users;
						} else{
							$new_acs_users[] = $req_user_id;
						}
						update_post_meta($docid, 'upf_acs_full', $new_acs_users);
					} else{
						if($curr_acs_users){
							if (($key = array_search($req_user_id, $curr_acs_users)) !== false) {
								unset($curr_acs_users[$key]);
							}
						}
						update_post_meta($docid, 'upf_acs_full', $curr_acs_users);
					}
				}
				
				// send email to the user
				$upfp_enable_email = get_option('upfp_enable_email');
				if($upfp_enable_email == 'yes'){
					
					$curr_user = get_user_by( 'id', $curr_user_id );
					$curr_user_usrname = $curr_user->user_login;
					
					// prepare subject
					$upfp_email_subject = get_option('upfp_email_subject');
					if($upfp_email_subject == ''){
						$upfp_email_subject = __("{user} shared a file / folder with you on " . get_bloginfo( 'name' ), "user-private-files");
					}
					$upfp_email_subject = str_replace("{user}", $curr_user_usrname, $upfp_email_subject);
					
					// prepare content
					$upfp_email_content = html_entity_decode( get_option('upfp_email_content') );
					if($upfp_email_content == ''){
						$upfp_email_content = __("{user} shared a file / folder with you on " . get_bloginfo( 'name' ) . ". Login and check your files.", "user-private-files");
					}
					$upfp_email_content = str_replace("{user}", $curr_user_usrname, $upfp_email_content);
					
					// write the email content
					$header = '';
					$header .= "MIME-Version: 1.0\n";
					$header .= "Content-Type: text/html; charset=utf-8\n";
					
					foreach($selected_users as $usr){
						$user_id = strval($usr->id);
						if (!in_array($user_id, $old_alwd_users)){
							$to = $usr->user_email;
							$is_sent = wp_mail($to, $upfp_email_subject, $upfp_email_content, $header);
						}
					}
					
					if(!$is_sent){
						$res_array['error'] = __("Error - File is shared but unable to send email to the user. Please contact us.", "user-private-files");
					}
					
				}
				
				$alwd_emails = array();
				$curr_allowed_users = get_post_meta($docid, 'upf_allowed', true);
				$curr_fldr_acs_users = get_post_meta($docid, 'upf_acs_full', true);
				
				if($curr_allowed_users){
					foreach($curr_allowed_users as $alwd_usr){
						$alwd_usr_obj = get_userdata( $alwd_usr );
						if($alwd_usr_obj){
							$is_author = 0;
							if (in_array($alwd_usr, $curr_fldr_acs_users)){
								if( get_post_field ('post_author', $docid) == $alwd_usr ){
									$acs_lvl = __('Author', 'user-private-files');
									$is_author = 1;
								} else{
									$acs_lvl = __('Full Access', 'user-private-files');
								}
							} else{
								$acs_lvl = __('View Only', 'user-private-files');
							}
							$alwd_emails[] = array($alwd_usr, esc_html( get_the_author_meta( 'nickname', $alwd_usr) ), $acs_lvl, $is_author);
						}
					}
				}
				
				$res_array['alwd_emails'] = $alwd_emails;
				
			}
			
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Rename File
add_action( 'wp_ajax_upvf_pro_rename_file', 'upvf_pro_rename_file' );
add_action( 'wp_ajax_nopriv_upvf_pro_rename_file', 'upvf_pro_rename_file' );
if (!function_exists('upvf_pro_rename_file')) {
	function upvf_pro_rename_file(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$file_id = sanitize_text_field($_POST['file_id']);
		$file_new_name = sanitize_text_field($_POST['file_new_name']);
		
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $file_id);
		$curr_acs_users = get_post_meta($file_id, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
			if($file_id != 0){
				$file_args = array(
					'ID'           => $file_id,
					'post_title'   => $file_new_name,
				);
				$file_id = wp_update_post( $file_args );
				$res_array['file_id'] = $file_id;
			}
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Update File description
add_action( 'wp_ajax_upvf_pro_update_file_dsc', 'upvf_pro_update_file_dsc' );
add_action( 'wp_ajax_nopriv_upvf_pro_update_file_dsc', 'upvf_pro_update_file_dsc' );
if (!function_exists('upvf_pro_update_file_dsc')) {
	function upvf_pro_update_file_dsc(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$file_id = sanitize_text_field($_POST['file_id']);
		$file_new_dsc = sanitize_text_field($_POST['file_new_dsc']);
		
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $file_id);
		$curr_acs_users = get_post_meta($file_id, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
			if($file_id != 0){
				$file_args = array(
					'ID'           => $file_id,
					'post_content'   => $file_new_dsc,
				);
				$file_id = wp_update_post( $file_args );
				$res_array['file_id'] = $file_id;
			}
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Remove users from file access
add_action( 'wp_ajax_upvf_pro_rmv_access', 'upvf_pro_rmv_access' );
add_action( 'wp_ajax_nopriv_upvf_pro_rmv_access', 'upvf_pro_rmv_access' );
if (!function_exists('upvf_pro_rmv_access')) {
	function upvf_pro_rmv_access() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo __('Could not verify POST values', 'user-private-files');
			exit;
		}
		$doc_id = sanitize_text_field( $_POST['doc_id'] );
		$doc_id = str_replace('doc_', '', $doc_id);
		$user_id = sanitize_text_field( $_POST['user'] );
		
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $doc_id);
		$curr_acs_users = get_post_meta($doc_id, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission	
			$curr_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
			if($curr_allowed_users){
				if (($key = array_search($user_id, $curr_allowed_users)) !== false) {
					unset($curr_allowed_users[$key]);
				}
			}
			$allowed_users_updated = update_post_meta($doc_id, 'upf_allowed', $curr_allowed_users);
			if(!$allowed_users_updated){
				$res_array['error'] = __("error - Unable to remove the user from this file. Please try again later or contact us.", "user-private-files");
			}
			$res_array['rmvd_usr'] = $user_id;
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Remove a file
add_action( 'wp_ajax_upvf_pro_delete_file', 'upvf_pro_delete_file' );
add_action( 'wp_ajax_nopriv_upvf_pro_delete_file', 'upvf_pro_delete_file' );
if (!function_exists('upvf_pro_delete_file')) {
	function upvf_pro_delete_file() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo __('Could not verify POST values', 'user-private-files');
			exit;
		}
		$doc_id = sanitize_text_field( $_POST['doc_id'] );
		$doc_id = str_replace('doc_', '', $doc_id);
		
		$dlt_type = sanitize_text_field( $_POST['dlt_type'] );
		
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $doc_id);
		$curr_acs_users = get_post_meta($doc_id, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
			if($dlt_type == 'permanent'){
				$file_removed = wp_delete_post($doc_id);
			} else{
				$file_removed = wp_trash_post($doc_id);
			}
			
			if(!$file_removed){
				$res_array['error'] = __("error - Unable to remove this file. Please try again later or contact us.", "user-private-files");
			} else{
				$res_array['rmvd_file'] = true;
			}
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Trash - Restore a file
add_action( 'wp_ajax_upvf_pro_restore_file', 'upvf_pro_restore_file' );
add_action( 'wp_ajax_nopriv_upvf_pro_restore_file', 'upvf_pro_restore_file' );
if (!function_exists('upvf_pro_restore_file')) {
	function upvf_pro_restore_file() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo __('Could not verify POST values', 'user-private-files');
			exit;
		}
		$doc_id = sanitize_text_field( $_POST['doc_id'] );
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $doc_id);
		$curr_acs_users = get_post_meta($doc_id, 'upf_acs_full', true);
		if($curr_user_id == $doc_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
			$file_untrashed = wp_untrash_post($doc_id);
			if(!$file_untrashed){
				$res_array['error'] = __("error - Unable to restore this file. Please try again later or contact us.", "user-private-files");
			} else{
				$res_array['restored_file'] = true;
			}
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Folders - get all folders
add_action( 'wp_ajax_upvf_pro_get_folders', 'upvf_pro_get_folders' );
add_action( 'wp_ajax_nopriv_upvf_pro_get_folders', 'upvf_pro_get_folders' );
if (!function_exists('upvf_pro_get_folders')) {
	function upvf_pro_get_folders() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo __('Could not verify POST values', 'user-private-files');
			exit;
		}
		
		$fldrs_to_exclude = array();
		if( isset($_POST['selected_fldrs']) && !empty($_POST['selected_fldrs']) ){
			$fldrs_to_exclude = explode( ',', sanitize_text_field($_POST['selected_fldrs']) );
		}
		
		$curr_user_id = get_current_user_id();
		$args = array(
			'post_type'		=> 'upf_folder',
			'post_status'	=> 'publish',
			'author'		=> $curr_user_id,
			'exclude'		=> $fldrs_to_exclude,
			'posts_per_page' => -1
		);
		
		$folders = get_posts($args);
		$options = '<option value="all-files">' . __("All Files", "user-private-files") . '</option>';
		if($folders){
			foreach($folders as $folder){
				
				if( !empty($fldrs_to_exclude) ){
					$parent_fldr = get_post_meta($folder->ID, 'upf_parent_fldr', true);
					if( !in_array($parent_fldr, $fldrs_to_exclude) ){
						$options .= '<option value="'.$folder->ID.'">'.$folder->post_title.'</option>';
					}
				} else{
					$options .= '<option value="'.$folder->ID.'">'.$folder->post_title.'</option>';
				}
				
			}
		}
		$res_array['options'] = $options;
		
		echo json_encode($res_array);
		exit;
	}
}

// Move file to Folder
add_action( 'wp_ajax_upvf_pro_move_file', 'upvf_pro_move_file' );
add_action( 'wp_ajax_nopriv_upvf_pro_move_file', 'upvf_pro_move_file' );
if (!function_exists('upvf_pro_move_file')) {
	function upvf_pro_move_file(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$doc_id = sanitize_text_field($_POST['doc_id']);
		$fldr_id = sanitize_text_field($_POST['fldr_id']);
		
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $doc_id);
		if($curr_user_id == $doc_author){ // checking permission
		
			if($fldr_id != 'all-files'){
				update_post_meta($doc_id, 'upf_foldr_id', $fldr_id);
				$res_array['new_fldr_id'] = $fldr_id;
			} else{
				delete_post_meta($doc_id, 'upf_foldr_id');
				$res_array['new_fldr_id'] = $fldr_id;
			}
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Add new comment to file
add_action( 'wp_ajax_upvf_pro_file_add_cmnt', 'upvf_pro_file_add_cmnt' );
add_action( 'wp_ajax_nopriv_upvf_pro_file_add_cmnt', 'upvf_pro_file_add_cmnt' );
if (!function_exists('upvf_pro_file_add_cmnt')) {
	function upvf_pro_file_add_cmnt(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$file_id = sanitize_text_field($_POST['docid']);
		$file_id = str_replace('doc_', '', $file_id);
		$cmnt = sanitize_text_field($_POST['cmnt']);
		
		// checking permission
		$curr_user_id = get_current_user_id();
		$doc_author = get_post_field ('post_author', $file_id);
		
		$shared_with = false;
		$curr_allowed_users = get_post_meta($file_id, 'upf_allowed', true);
		if($curr_allowed_users){
			if (in_array($curr_user_id, $curr_allowed_users)){
				$shared_with = true;
			}
		}
		
		if($curr_user_id == $doc_author || $shared_with){
			if($file_id != 0){
				$cmnt_args = array(
					'user_id'			=> $curr_user_id,
					'comment_approved'	=> 1,
					'comment_content'	=> $cmnt,
					'comment_post_ID'	=> $file_id,
					'comment_type'		=> 'upfp_comment'
				);
				
				$cmnt_id = wp_insert_comment($cmnt_args);
				if($cmnt_id){
					$res_array['added'] = 1;
					
					$res_array['user_avatar'] = get_avatar( $curr_user_id, 32 );
					
					$user_info = get_userdata($curr_user_id);
					$res_array['user_email'] = $user_info->user_email;
					
				} else{
					$res_array['error'] = __("An error occured while submitting your comment. Please contact us.", "user-private-files");
				}
				
			}
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}
