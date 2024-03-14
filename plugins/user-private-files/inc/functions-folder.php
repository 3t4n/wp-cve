<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// Refresh left panel for folders
if (!function_exists('refresh_left_panel')) {
	function refresh_left_panel(){
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		$uploading_allwd = true;
		if($uploading_allwd){
			// Get root folders
			$args = array(
				'post_type'		=> 'upf_folder',
				'post_status'	=> 'publish',
				'author'		=> get_current_user_id(),
				'posts_per_page' => -1, 
				'meta_query' 	=> array(
					array(
						'key'     => 'upf_parent_fldr',
						'value'   => '',
						'compare' => 'NOT EXISTS'
					)
				)
			);
			$folders = get_posts($args);
		}
		// Root Folders shared with the User
		$user_id = get_current_user_id();
		$shared_args = array(
			'post_type' => 'upf_folder',
			'post_status' => 'publish',
			'posts_per_page' => -1, 
			'meta_query' => array(
				array(
					'key' => 'upf_allowed',
					'value' => serialize(strval($user_id)),
					'compare' => 'LIKE',
				),
			)
		);
		$shared_folders = get_posts($shared_args);
		
		$panel_html = '';
		if($uploading_allwd){
			$panel_html .= '<h4>' . __("Dashboard", "user-private-files") . '</h4>
							<ul class="upfp_nav_dash">
								<li data-folder-id="all-files" data-folder-name="" class="upfp_li_active upfp_fldr_obj">
									<a id="upfp_home_link" class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-home"></i> <span>' . __("All Files", "user-private-files") . '</span></a>
								</li>
							</ul>
							<ul class="upfp_nav_list my_folders">';
								foreach($folders as $folder){
									$panel_html .= '<li id="upfp_nav_fldr_' . $folder->ID . '" data-folder-id="' . $folder->ID . '" data-folder-name="' . $folder->post_title . '" class="upfp_fldr_obj">
										<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i> <span> ' . $folder->post_title . '</span></a>
									</li>';
								}
			$panel_html .= '</ul>';	
		}
		
		$panel_html .= '<h4>' . __("Shared with me", "user-private-files") . '</h4>
						<ul class="upfp_nav_list shared_fldrs">
							<li data-folder-id="all-shared-files" data-folder-name="' . __("Shared", "user-private-files") . '" data-share="true" class="upfp_fldr_obj">
								<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i> <span> ' . __("All Files", "user-private-files") . '</span></a>
							</li>';
							$sf_array = array();
							// select only top level folders
							foreach( $shared_folders as $sf){
								$sf_id = $sf->ID;
								$is_shared = false;
								$parent_folder = get_post_meta($sf->ID, 'upf_parent_fldr', true);
								$alwd_users = get_post_meta($parent_folder, 'upf_allowed', true);
								if($parent_folder && $alwd_users){
									$is_shared = in_array($user_id, $alwd_users);
								}
								while($parent_folder && $is_shared){
									$sf_id = (int)$parent_folder;
									$parent_folder = get_post_meta($parent_folder, 'upf_parent_fldr', true);
									$alwd_users = get_post_meta($parent_folder, 'upf_allowed', true);
									if($parent_folder && $alwd_users){
										$is_shared = in_array($user_id, $alwd_users);
									}
								}
								$sf_array[] = $sf_id;
							}
							
							$sf_array = array_unique($sf_array);
							foreach($sf_array as $sf_id){
								$sf_name = get_the_title($sf_id);
								
								$panel_html .= '<li id="upfp_nav_fldr_' . $sf_id . '" data-folder-id="' . $sf_id . '" data-folder-name="' . $sf_name . '" data-share="true" class="upfp_fldr_obj">
									<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i> <span> ' . $sf_name . '</span></a>
								</li>';
							}
		$panel_html .= '</ul>';

		$panel_html .= '<ul class="upfp_nav_trash">
							<li data-folder-id="trash-files" data-folder-name="' . __("Trash", "user-private-files") . '" class="upfp_fldr_obj">
								<a id="upfp_trash_link" class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-trash"></i> <span>' . __("Trash", "user-private-files") . '</span></a>
							</li>
						</ul>';
		
		return $panel_html;
		
	}
}

// select child folders & files
if (!function_exists('select_child_elems')) {
	function select_child_elems($target_folder, $post_type = array( 'upf_folder', 'attachment' ), $post_status = array( 'inherit', 'publish' )){
		$user_id = get_current_user_id();
		$the_query = new WP_Query( array( 
			'post_type'   => $post_type,
			'post_status' => $post_status,
			// 'author' 	  => $user_id, // support for access level feature since ver 3.0.6
			'meta_query' => array(
				'relation'	  => 'OR',
				array(
					'key'     => 'upf_parent_fldr', // selecting folders
					'value'   => $target_folder,
				),
				array(
					'key'     => 'upf_foldr_id', // selecting files
					'value'   => $target_folder,
				)
			),
			'posts_per_page' => -1 )
		);
		return $the_query;	
	}
}

// share child folders & files
if (!function_exists('share_child_elems')) {
	function share_child_elems($query, $req_user_id, $access_lvl = ''){
		if($query->have_posts()){
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				
				if( 'upf_folder' == get_post_type($post_id) ){
					$the_query = select_child_elems($post_id);
					share_child_elems($the_query, $req_user_id);
				}
				
				$new_users = array();
				$curr_allowed_users = get_post_meta($post_id, 'upf_allowed', true);
				if($curr_allowed_users){
					if (!in_array($req_user_id, $curr_allowed_users)){
						array_push($curr_allowed_users, $req_user_id);
					}
					$new_users = $curr_allowed_users;
				} else{
					$new_users[] = $req_user_id;
				}
				update_post_meta($post_id, 'upf_allowed', $new_users);
				
				// Set access level
				$new_acs_users = array();
				$curr_acs_users = get_post_meta($post_id, 'upf_acs_full', true);
				if($access_lvl == 'full'){
					if($curr_acs_users){
						if (!in_array($req_user_id, $curr_acs_users)){
							array_push($curr_acs_users, $req_user_id);
						}
						$new_acs_users = $curr_acs_users;
					} else{
						$new_acs_users[] = $req_user_id;
					}
					update_post_meta($post_id, 'upf_acs_full', $new_acs_users);
				} else{
					if($curr_acs_users){
						if (($key = array_search($req_user_id, $curr_acs_users)) !== false) {
							unset($curr_acs_users[$key]);
						}
					}
					update_post_meta($post_id, 'upf_acs_full', $curr_acs_users);
				}
				
			}
		}
	}
}

// remove access from shared child folders & files
if (!function_exists('rmv_access_child_elems')) {
	function rmv_access_child_elems($query, $req_user_id){
		if($query->have_posts()){
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				
				if( 'upf_folder' == get_post_type($post_id) ){
					$the_query = select_child_elems($post_id);
					rmv_access_child_elems($the_query, $req_user_id);
				}
				
				$curr_allowed_users = get_post_meta($post_id, 'upf_allowed', true);
				if($curr_allowed_users){
					if (($key = array_search($req_user_id, $curr_allowed_users)) !== false) {
						unset($curr_allowed_users[$key]);
					}
				}
				update_post_meta($post_id, 'upf_allowed', $curr_allowed_users);
				
			}
		}
	}
}

// delete child folders & files
if (!function_exists('delete_child_elems')) {
	function delete_child_elems($query, $dlt_type){
		if($query->have_posts()){
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				
				if($dlt_type == 'trash'){
					$status_select = array( 'inherit', 'publish' );
				} else{
					$status_select = array( 'trash' );
				}
				
				if( 'upf_folder' == get_post_type($post_id) ){
					$the_query = select_child_elems($post_id, array( 'upf_folder', 'attachment' ), $status_select);
					delete_child_elems($the_query, $dlt_type);
				}
				
				if($dlt_type == 'trash'){
					wp_trash_post($post_id);
				} else{
					wp_delete_post($post_id);
				}
				
			}
		}
	}
}

// restore child folders & files
if (!function_exists('restore_child_elems')) {
	function restore_child_elems($query){
		if($query->have_posts()){
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				
				if( 'upf_folder' == get_post_type($post_id) ){
					$the_query = select_child_elems($post_id,  array( 'upf_folder', 'attachment' ), array('trash'));
					restore_child_elems($the_query);
				}
				
				wp_untrash_post($post_id);
				
			}
		}
	}
}


// Load files from a folder
add_action( 'wp_ajax_upvf_pro_load_flder', 'upvf_pro_load_flder_callback' );
add_action( 'wp_ajax_nopriv_upvf_pro_load_flder', 'upvf_pro_load_flder_callback' );
if (!function_exists('upvf_pro_load_flder_callback')) {
	function upvf_pro_load_flder_callback(){
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		if(isset($_POST['fldr_id'])){
			$fldr_id = sanitize_text_field($_POST['fldr_id']);
			
            $curr_user_id = get_current_user_id();

            $allwd = false;

            if($fldr_id == 'all-shared-files' || $fldr_id == 'trash-files' || $fldr_id == 'all-files' || $fldr_id == 'filter-shared') {
                $allwd = true;
            } else {
                $curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
                $curr_fldr_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
                $fldr_author = get_post_field('post_author', $fldr_id);

                if($curr_user_id == $fldr_author || (is_array($curr_allowed_users) && in_array($curr_user_id, $curr_allowed_users))) {
                    $allwd = true;
                }
            }

            if($allwd) {

                $parent_folder = get_post_meta($fldr_id, 'upf_parent_fldr', true);
                $all_parent_folders = array();
                $i = 0;
                while($parent_folder){
                    
                    $all_parent_folders[$i]['id'] = (int)$parent_folder;
                    
                    $parent_folder_name = get_the_title((int)$parent_folder);
                    $all_parent_folders[$i]['name'] = $parent_folder_name;
                
                    $parent_folder = get_post_meta($parent_folder, 'upf_parent_fldr', true);
                    $i++;
                }
                
                $res_array['all_parent_folders'] = array_reverse( $all_parent_folders );
                
                $res_array['folder_ttl'] = get_the_title($fldr_id);
                
                $data_share = false;
                if(isset($_POST['data_share'])){
                    $data_share = sanitize_text_field($_POST['data_share']);
                }
                
                if($data_share != 'true'){
                    // Load allowed users
                    $alwd_emails = array();
                    
                    if($curr_allowed_users){
                        foreach($curr_allowed_users as $alwd_usr){
                            $is_author = 0;
                            $alwd_usr_obj = get_userdata( $alwd_usr );
                            if($alwd_usr_obj){
                                
                                if (is_array($curr_fldr_acs_users) && in_array($alwd_usr, $curr_fldr_acs_users)){
                                    if( get_post_field ('post_author', $fldr_id) == $alwd_usr ){
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
                
                    // check for trash status
                    $fldr_status = '';
                    if(isset($_POST['fldr_status'])){
                        $fldr_status = sanitize_text_field($_POST['fldr_status']);
                    }
                    $data = array( 'folder_id' => $fldr_id, 'folder_status' => $fldr_status );
                    
                    global $upvf_template_loader;
                    
                    ob_start();
                    $upvf_template_loader->set_template_data( $data )->get_template_part( 'files' );
                    $res_array['html'] = ob_get_contents();
                    ob_end_clean();
                }
                else{
                    
                    if($curr_fldr_acs_users){
                        $req_user_id = get_current_user_id();
                        if (in_array($req_user_id, $curr_fldr_acs_users)){
                            $res_array['full_access'] = 1;
                            // Load allowed users
                            $alwd_emails = array();
                            if($curr_allowed_users){
                                foreach($curr_allowed_users as $alwd_usr){
                                    $is_author = 0;
                                    $alwd_usr_obj = get_userdata( $alwd_usr );
                                    if($alwd_usr_obj){
                                        if (in_array($alwd_usr, $curr_fldr_acs_users)){
                                            if( get_post_field ('post_author', $fldr_id) == $alwd_usr ){
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
                    
                    $fltr_email = '';
                    if(isset($_POST['fltr_email'])){
                        $fltr_email = sanitize_text_field($_POST['fltr_email']);
                    }
                    
                    $data = array( 'folder_id' => $fldr_id, 'fltr_email' => $fltr_email );
                    
                    global $upvf_template_loader;
                    
                    ob_start();
                    $upvf_template_loader->set_template_data( $data )->get_template_part( 'files-shared' );
                    $res_array['html'] = ob_get_contents();
                    ob_end_clean();
                    
                }
                
                $author_id = get_post_field ('post_author', $fldr_id);
                $res_array['author'] = get_the_author_meta('user_email', $author_id);
                
                $res_array['left_panel'] = refresh_left_panel();

            } else {
                $res_array['error'] = __('You do not have permission to open this folder.', 'user-private-files');
            }
			
			
			echo json_encode($res_array);
			exit;
			
		} else{
			$res_array['error'] = __('Error - Folder ID is missing', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
	}
}

// New Folder
add_action( 'wp_ajax_upvf_pro_new_flder_callback', 'upvf_pro_new_flder_callback' );
add_action( 'wp_ajax_nopriv_upvf_pro_new_flder_callback', 'upvf_pro_new_flder_callback' );
if (!function_exists('upvf_pro_new_flder_callback')) {
	function upvf_pro_new_flder_callback(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$fldr_ttl = '';
		if(isset($_POST['fldr_ttl'])){
			$fldr_ttl = sanitize_text_field($_POST['fldr_ttl']);
		
			$args = array(
				'post_type' => 'upf_folder',
				'post_title'     => $fldr_ttl,
				// 'post_parent' => '',
				'post_status'    => 'publish'
			);
			
			$fldr_id = wp_insert_post($args, true);
			if ( is_wp_error( $fldr_id ) ) {
				$res_array['error'] = __("Error creating the folder! Please try later or contact us", "user-private-files");
			} else {
				
				$curr_user_id = get_current_user_id();
				update_post_meta($fldr_id, 'upf_allowed', array($curr_user_id));
				update_post_meta($fldr_id, 'upf_acs_full', array($curr_user_id));
				
				if(isset($_POST['parent_fldr'])){
					$parent_fldr = sanitize_text_field($_POST['parent_fldr']);
					update_post_meta($fldr_id, 'upf_parent_fldr', $parent_fldr);
					
					$curr_allowed_users = get_post_meta($parent_fldr, 'upf_allowed', true);
					if (!in_array($curr_user_id, $curr_allowed_users)){
						array_push($curr_allowed_users, $curr_user_id);
					}
					update_post_meta($fldr_id, 'upf_allowed', $curr_allowed_users);
					
					$curr_full_acs_users = get_post_meta($parent_fldr, 'upf_acs_full', true);
					if (!in_array($curr_user_id, $curr_full_acs_users)){
						array_push($curr_full_acs_users, $curr_user_id);
					}
					update_post_meta($fldr_id, 'upf_acs_full', $curr_full_acs_users);
				}
				
				global $upf_plugin_url;
				$folder_prvw_img = $upf_plugin_url . 'images/folder-150.png';
				
				$res_array['html'] = '<div id="sub_folder_' . $fldr_id . '" data-folder-id="' . $fldr_id . '" data-folder-name="' . $fldr_ttl . '" class="folder-item upfp_fldr_obj">
										<a class="sub-folder-action" href="javascript:void(0);">
											<img src="' . $folder_prvw_img . '">
										</a>
										<p class="folder_ttl">' . $fldr_ttl . '</p>
									 </div>';
				
				if(!isset($_POST['parent_fldr'])){ // only add to navigation if it's a root folder
					$res_array['folders_li'] = '<li id="upfp_nav_fldr_' . $fldr_id . '" data-folder-id="' . $fldr_id . '" data-folder-name="' . $fldr_ttl . '" class="upfp_fldr_obj">
												<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i><span> ' . $fldr_ttl . '</span></a>
											</li>';
				}
				
			}
			echo json_encode($res_array);
			exit;
		} else{
			$res_array['error'] = __('Error - Folder Name is empty', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
	}
}

// Rename Folder
add_action( 'wp_ajax_upvf_pro_rename_folder', 'upvf_pro_rename_folder' );
add_action( 'wp_ajax_nopriv_upvf_pro_rename_folder', 'upvf_pro_rename_folder' );
if (!function_exists('upvf_pro_rename_folder')) {
	function upvf_pro_rename_folder(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$folder_id = sanitize_text_field($_POST['folder_id']);
		$folder_new_name = sanitize_text_field($_POST['folder_new_name']);
		
		$curr_user_id = get_current_user_id();
		$folder_author = get_post_field ('post_author', $folder_id);
		$curr_acs_users = get_post_meta($folder_id, 'upf_acs_full', true);
		if($curr_user_id == $folder_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
			if($folder_id != 0){
				$folder_args = array(
					'ID'           => $folder_id,
					'post_title'   => $folder_new_name,
				);
				$fldr_id = wp_update_post( $folder_args );
				$res_array['fldr_id'] = $fldr_id;
			}
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		echo json_encode($res_array);
		exit;
	}
}

// Move folder to another folder
add_action( 'wp_ajax_upvf_pro_move_folder', 'upvf_pro_move_folder' );
add_action( 'wp_ajax_nopriv_upvf_pro_move_folder', 'upvf_pro_move_folder' );
if (!function_exists('upvf_pro_move_folder')) {
	function upvf_pro_move_folder() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$folder_id = sanitize_text_field($_POST['folder_id']);
		$target_fldr_id = sanitize_text_field($_POST['target_fldr_id']);
		
		$curr_user_id = get_current_user_id();
		$fldr_author = get_post_field ('post_author', $folder_id);
		$curr_prnt_fldr = get_post_meta($folder_id, 'upf_parent_fldr', true);
		if( $curr_user_id == $fldr_author ){ // checking permission
		
			if($target_fldr_id != 'all-files'){
				update_post_meta($folder_id, 'upf_parent_fldr', $target_fldr_id);
			} else{
				delete_post_meta($folder_id, 'upf_parent_fldr');
			}
			$res_array['new_fldr_id'] = $target_fldr_id;
			
			if($target_fldr_id == 'all-files'){
				$res_array['dont_rmv'] = 1;
				if($curr_prnt_fldr != ''){
					$fldr_ttl = get_the_title($folder_id);
					$res_array['li_html'] = '<li id="upfp_nav_fldr_' . $folder_id . '" data-folder-id="' . $folder_id . '" data-folder-name="' . $fldr_ttl . '" class="upfp_fldr_obj">
												<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i><span> ' . $fldr_ttl . '</span></a>
											</li>';
				}
			}
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}


// Add user to folder
add_action( 'wp_ajax_upvf_pro_share_folder', 'upvf_pro_share_folder' );
add_action( 'wp_ajax_nopriv_upvf_pro_share_folder', 'upvf_pro_share_folder' );
if (!function_exists('upvf_pro_share_folder')) {
	function upvf_pro_share_folder() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		$fldr_id = $usr_email = $access_lvl = '';
		if(isset($_POST['fldr_id'])){
			$fldr_id = sanitize_text_field( $_POST['fldr_id'] );
		}
		if(isset($_POST['access_lvl'])){
			$access_lvl = sanitize_text_field( $_POST['access_lvl'] );
		}
		if(isset($_POST['usr_email'])){
			$usr_email = sanitize_text_field( $_POST['usr_email'] );
		}
		
		if(!$fldr_id || !$usr_email){
			$res_array['error'] = __('error - Empty POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$req_user = get_user_by( 'email', $usr_email );
		
		if(!$req_user){
			$req_user = get_user_by( 'login', $usr_email );
		}
		
		$curr_user_id = get_current_user_id();
		$folder_author = get_post_field ('post_author', $fldr_id);
		$curr_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
		
		if($curr_user_id == $folder_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
			if($req_user){
				$req_user_id = $req_user->ID;
				// convert the ID to string for better search operation
				$req_user_id = strval($req_user_id);
				
				// Share all child folders and files
				$the_query = select_child_elems($fldr_id);
				
				share_child_elems($the_query, $req_user_id, $access_lvl);
				
				wp_reset_query();
				
				// share this folder now
				$new_users = array();
				$curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
				if($curr_allowed_users){
					if (!in_array($req_user_id, $curr_allowed_users)){
						array_push($curr_allowed_users, $req_user_id);
					}
					$new_users = $curr_allowed_users;
				} else{
					$new_users[] = $req_user_id;
				}
				
				$allowed_users_updated = update_post_meta($fldr_id, 'upf_allowed', $new_users);
				
				if(!$allowed_users_updated){
					$res_array['error'] = __("error - Unable to add users to this folder", "user-private-files");
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
						update_post_meta($fldr_id, 'upf_acs_full', $new_acs_users);
					} else{
						if($curr_acs_users){
							if (($key = array_search($req_user_id, $curr_acs_users)) !== false) {
								unset($curr_acs_users[$key]);
							}
						}
						update_post_meta($fldr_id, 'upf_acs_full', $curr_acs_users);
					}
					
					// send email to the user
					$upfp_enable_email = get_option('upfp_enable_email');
					if($upfp_enable_email == 'yes'){
						$to = $req_user->user_email;
						
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
							$res_array['error'] = __("Error - Folder is shared but unable to send email to the user. Please contact us.", "user-private-files");
						}
						
					}
					
					$alwd_emails = array();
					$curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
					$curr_fldr_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
					
					if($curr_allowed_users){
						foreach($curr_allowed_users as $alwd_usr){
							$alwd_usr_obj = get_userdata( $alwd_usr );
							if($alwd_usr_obj){
								$is_author = 0;
								if (in_array($alwd_usr, $curr_fldr_acs_users)){
									if( get_post_field ('post_author', $fldr_id) == $alwd_usr ){
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



// Bulk Add users to folder
add_action( 'wp_ajax_upvf_pro_share_folder_bulk', 'upvf_pro_share_folder_bulk' );
add_action( 'wp_ajax_nopriv_upvf_pro_share_folder_bulk', 'upvf_pro_share_folder_bulk' );
if (!function_exists('upvf_pro_share_folder_bulk')) {
	function upvf_pro_share_folder_bulk() {
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		$fldr_id = $usr_type = $access_lvl = '';
		if(isset($_POST['fldr_id'])){
			$fldr_id = sanitize_text_field( $_POST['fldr_id'] );
		}
		if(isset($_POST['usr_type'])){
			$usr_type = sanitize_text_field( $_POST['usr_type'] );
		}
		
		if(!$fldr_id || !$usr_type){
			$res_array['error'] = __('error - Empty POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		if(isset($_POST['access_lvl'])){
			$access_lvl = sanitize_text_field( $_POST['access_lvl'] );
		}
		
		$curr_user_id = get_current_user_id();
		$folder_author = get_post_field ('post_author', $fldr_id);
		
		$curr_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
		
		if($curr_user_id == $folder_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
			
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
			$curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
			$old_alwd_users = $curr_allowed_users;
			foreach( $selected_users as $usr ){
				$req_user_id = strval( $usr->id );
				
				// Share all child folders and files
				$the_query = select_child_elems($fldr_id);
				
				share_child_elems($the_query, $req_user_id, $access_lvl);
				
				wp_reset_query();
				
				
				// share this folder now
				if($curr_allowed_users){
					if (!in_array($req_user_id, $curr_allowed_users)){
						array_push($curr_allowed_users, $req_user_id);
					}
					$new_users = $curr_allowed_users;
				} else{
					$new_users[] = $req_user_id;
				}
			}
			
			$allowed_users_updated = update_post_meta($fldr_id, 'upf_allowed', $new_users);
			
			if(!$allowed_users_updated){
				$res_array['error'] = __("error - Unable to add users to this folder", "user-private-files");
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
						update_post_meta($fldr_id, 'upf_acs_full', $new_acs_users);
					} else{
						if($curr_acs_users){
							if (($key = array_search($req_user_id, $curr_acs_users)) !== false) {
								unset($curr_acs_users[$key]);
							}
						}
						update_post_meta($fldr_id, 'upf_acs_full', $curr_acs_users);
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
						$res_array['error'] = __("Error - Folder is shared but unable to send email to the user. Please contact us.", "user-private-files");
					}
					
				}
				
				$alwd_emails = array();
				$curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
				$curr_fldr_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
				if($curr_allowed_users){
					foreach($curr_allowed_users as $alwd_usr){
						$alwd_usr_obj = get_userdata( $alwd_usr );
						if($alwd_usr_obj){
							$is_author = 0;
							if (in_array($alwd_usr, $curr_fldr_acs_users)){
								if( get_post_field ('post_author', $fldr_id) == $alwd_usr ){
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



// Remove users from folder access
add_action( 'wp_ajax_upvf_pro_rmv_fldr_access', 'upvf_pro_rmv_fldr_access' );
add_action( 'wp_ajax_nopriv_upvf_pro_rmv_fldr_access', 'upvf_pro_rmv_fldr_access' );
if (!function_exists('upvf_pro_rmv_fldr_access')) {
	function upvf_pro_rmv_fldr_access() {
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			echo __('Could not verify POST values', 'user-private-files');
			exit;
		}
		$fldr_id = sanitize_text_field( $_POST['fldr_id'] );
		$user_id = sanitize_text_field( $_POST['user'] );
		
		$curr_user_id = get_current_user_id();
		$folder_author = get_post_field ('post_author', $fldr_id);
		
		$curr_acs_users = get_post_meta($fldr_id, 'upf_acs_full', true);
		if($curr_user_id == $folder_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
			// remove access from all sub-folders & files
			$the_query = select_child_elems($fldr_id);
			
			rmv_access_child_elems($the_query, $user_id);
			
			wp_reset_query();
			
			// remove access from this selected folder
			$curr_allowed_users = get_post_meta($fldr_id, 'upf_allowed', true);
			if($curr_allowed_users){
				if (($key = array_search($user_id, $curr_allowed_users)) !== false) {
					unset($curr_allowed_users[$key]);
				}
			}
			$allowed_users_updated = update_post_meta($fldr_id, 'upf_allowed', $curr_allowed_users);
			if(!$allowed_users_updated){
				$res_array['error'] = __("error - Unable to remove the user from this folder. Please try again later or contact us.", "user-private-files");
			}
			$res_array['rmvd_usr'] = $user_id;
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Delete Folder
add_action( 'wp_ajax_upvf_pro_delete_folder', 'upvf_pro_delete_folder' );
add_action( 'wp_ajax_nopriv_upvf_pro_delete_folder', 'upvf_pro_delete_folder' );
if (!function_exists('upvf_pro_delete_folder')) {
	function upvf_pro_delete_folder(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$folder_id = sanitize_text_field($_POST['folder_id']);
		$dlt_type = sanitize_text_field($_POST['dlt_type']);
		
		$curr_user_id = get_current_user_id();
		$folder_author = get_post_field ('post_author', $folder_id);
		$curr_acs_users = get_post_meta($folder_id, 'upf_acs_full', true);
		if($curr_user_id == $folder_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
			if($folder_id != 0){
				
				if($dlt_type == 'trash'){
					$parent_folder = get_post_meta($folder_id, 'upf_parent_fldr', true);
					$res_array['parent_folder'] = $parent_folder;
					$status_select = array( 'inherit', 'publish' );
				} else{
					$status_select = array( 'trash' );
				}
				
				$the_query = select_child_elems( $folder_id, array( 'upf_folder', 'attachment' ), $status_select );
				
				delete_child_elems($the_query, $dlt_type);
				
				wp_reset_query();
				
				if($dlt_type == 'trash'){
					// Trash the folder
					$res_array['deleted'] = wp_trash_post($folder_id);
				} else{
					// Delete the folder
					$res_array['deleted'] = wp_delete_post($folder_id);
				}
				
			}
			
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		
		echo json_encode($res_array);
		exit;
	}
}

// Trash - Restore Folder
add_action( 'wp_ajax_upvf_pro_restore_folder', 'upvf_pro_restore_folder' );
add_action( 'wp_ajax_nopriv_upvf_pro_restore_folder', 'upvf_pro_restore_folder' );
if (!function_exists('upvf_pro_restore_folder')) {
	function upvf_pro_restore_folder(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$folder_id = sanitize_text_field($_POST['folder_id']);
		
		$curr_user_id = get_current_user_id();
		$folder_author = get_post_field ('post_author', $folder_id);
		$curr_acs_users = get_post_meta($folder_id, 'upf_acs_full', true);
		if($curr_user_id == $folder_author || in_array($curr_user_id, $curr_acs_users)){ // checking permission
		
			if($folder_id != 0){
				
				$the_query = select_child_elems( $folder_id, array( 'upf_folder', 'attachment' ), array('trash') );
				
				restore_child_elems($the_query);
				
				wp_reset_query();
				
				// Untrash the folder
				$res_array['restored_folder'] = wp_untrash_post($folder_id);
				
				$parent_folder = get_post_meta($folder_id, 'upf_parent_fldr', true);
				if(!$parent_folder){
					$fldr_ttl = get_the_title($folder_id);
					if($curr_user_id == $folder_author){
						$res_array['li_html'] = '<li id="upfp_nav_fldr_' . $folder_id . '" data-folder-id="' . $folder_id . '" data-folder-name="' . $fldr_ttl . '" class="upfp_fldr_obj">
													<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i><span> ' . $fldr_ttl . '</span></a>
												</li>';
					} else{
						$res_array['shared_li_html'] = '<li id="upfp_nav_fldr_' . $folder_id . '" data-folder-id="' . $folder_id . '" data-folder-name="' . $fldr_ttl . '" class="upfp_fldr_obj" data-share="true">
													<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i><span> ' . $fldr_ttl . '</span></a>
												</li>';
					}
				}
			}
		
		} else{
			$res_array['error'] = __("You don't have permission to perform this action", "user-private-files");
		}
		echo json_encode($res_array);
		exit;
	}
}

// Trash - Empty Trash
add_action( 'wp_ajax_upvf_pro_empty_trash', 'upvf_pro_empty_trash' );
add_action( 'wp_ajax_nopriv_upvf_pro_empty_trash', 'upvf_pro_empty_trash' );
if (!function_exists('upvf_pro_empty_trash')) {
	function upvf_pro_empty_trash(){
		$res_array = array();
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		$user_id = get_current_user_id();
		
		$folders_deleted = $files_deleted = false;
		// delete all folders
		$the_query = new WP_Query( array( 
			'post_type'   => array( 'upf_folder' ),
			'post_status' => 'trash',
			'author' 	  => $user_id,
			'posts_per_page' => -1 )
		);
		if($the_query->have_posts()){
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post_id = get_the_ID();
				$folders_deleted = wp_delete_post($post_id);
			}
		}
		wp_reset_query();
		
		// delete all files
		$the_query = new WP_Query( array( 
			'post_type'   => array( 'attachment' ),
			'post_status' => 'trash',
			'author' 	  => $user_id,
			'meta_query' => array(
				array(
					'key'     => 'upf_doc',
					'value'   => 'true',
				)
			),
			'posts_per_page' => -1 )
		);
		if($the_query->have_posts()){
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post_id = get_the_ID();
				$files_deleted = wp_delete_post($post_id);
			}
		}
		wp_reset_query();
		
		$res_array['done'] = true;
		
		echo json_encode($res_array);
		exit;
	}
}

// Search Files & Folders
add_action( 'wp_ajax_upvf_pro_search', 'upvf_pro_search' );
add_action( 'wp_ajax_nopriv_upvf_pro_search', 'upvf_pro_search' );
if (!function_exists('upvf_pro_search')) {
	function upvf_pro_search(){
		if ( !isset( $_POST) || empty($_POST) || !is_user_logged_in() || !wp_verify_nonce( $_POST['upf_nonce'], 'upfp_ajax_nonce' ) ) {
			header( 'HTTP/1.1 400 Empty POST Values' );
			$res_array['error'] = __('Error - Could not verify POST values', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
		if(isset($_POST['search_keyword'])){
			$search_keyword = sanitize_text_field($_POST['search_keyword']);
			
			$data = array( 'keyword' => $search_keyword );
			
			global $upvf_template_loader;
			
			ob_start();
			$upvf_template_loader->set_template_data( $data )->get_template_part( 'files-search' );
			$res_array['html'] = ob_get_contents();
			ob_end_clean();
			
			echo json_encode($res_array);
			exit;
			
		} else{
			$res_array['error'] = __('Error - Folder ID is missing', 'user-private-files');
			echo json_encode($res_array);
			exit;
		}
		
	}
}
