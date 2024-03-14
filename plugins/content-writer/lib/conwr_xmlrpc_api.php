<?php
require_once(dirname( __FILE__ ) . '/sc_functions.php');

add_filter('xmlrpc_methods', 'conwr_new_xmlrpc_methods');

if (!function_exists("conwr_new_xmlrpc_methods")) {
	function conwr_new_xmlrpc_methods($methods) {
		set_time_limit(120);
		$methods['conwr.IsMultisite'] = 'conwr_xmlrpc_is_multisite';
		$methods['conwr.SitesGet'] = 'conwr_xmlrpc_get_multisite_sites';
		$methods['conwr.PostCreate'] = 'conwr_xmlrpc_create_post';
		$methods['conwr.PostUpdate'] = 'conwr_xmlrpc_update_post';
		$methods['conwr.PostGet'] = 'conwr_xmlrpc_get_post';
		$methods['conwr.PostsGet'] = 'conwr_xmlrpc_get_posts';
		$methods['conwr.PostRemove'] = 'conwr_xmlrpc_remove_post';
		$methods['conwr.PostRemoveDraft'] = 'conwr_xmlrpc_remove_draft_post';
		$methods['conwr.PostPreview'] = 'conwr_xmlrpc_create_post_preview';
		$methods['conwr.PostChangeStatus'] = 'conwr_xmlrpc_change_post_status';
		$methods['conwr.PostChangeType'] = 'conwr_xmlrpc_change_post_type';
		$methods['conwr.CategoriesGet'] = 'conwr_xmlrpc_get_post_categories';
		$methods['conwr.AuthorsGet'] = 'conwr_xmlrpc_get_authors';
		$methods['conwr.CategoryCreate'] = 'conwr_xmlrpc_create_category';
		$methods['conwr.AuthorCreate'] = 'conwr_xmlrpc_create_author';
		$methods['conwr.PluginsGet'] = 'conwr_xmlrpc_get_plugins';
		$methods['conwr.ThemeGet'] = 'conwr_xmlrpc_get_curent_theme';
		$methods['conwr.CheckPlugin'] = 'conwr_xmlrpc_check_plugin';
		$methods['conwr.GetPluginStatus'] = 'conwr_xmlrpc_get_plugin_update_status';
		$methods['conwr.VerifyPlugin'] = 'conwr_xmlrpc_verify_plugin_from_app';
		$methods['conwr.IsPluginVerified'] = 'conwr_xmlrpc_verify_plugin_from_app';

		return $methods;
	}
}

if (!function_exists("conwr_xmlrpc_rest_validate_token")) {
	function conwr_xmlrpc_rest_validate_token($data, $function_name = "") {
		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));
		
		if (isset($post_data) && !empty($post_data)) {
			$token = $post_data->RequestToken;

			//check security token
			$URL = 'http://api.steadycontent.com/validate.aspx?action=login&token=' . $token;
			conwr_api_write_log("Request URL: " . $URL);

			$is_valid_token = conwr_get_request($URL);

			conwr_api_write_log("Response: " . $is_valid_token);

			if($is_valid_token == 'True') {
				return true;
			}
		}

		conwr_api_write_log("Request token is not valid. Function: " . $function_name);

		return false;
	}
}

if (!function_exists("conwr_xmlrpc_is_multisite")) {
	function conwr_xmlrpc_is_multisite($data) {		
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		$is_multisite = is_multisite() ? "true" : "false";

		return json_encode(array('Status' => "Successful", 'IsMultisite' => $is_multisite));
	}
}

if (!function_exists("conwr_xmlrpc_get_multisite_sites")) {
	function conwr_xmlrpc_get_multisite_sites($data) {		
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		$blog_url = home_url();

		if (!is_multisite()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Site {$blog_url} is not multisite"));
		}

		$sites = get_sites();

		if (isset($sites) && is_array($sites)) {
			return json_encode(array('Status' => "Successful", 'Sites'  => $sites));
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error'  => "Sites could not be retrieved."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_create_post")) {
	function conwr_xmlrpc_create_post($data, $is_preview = false) {
		$response = "";

		try
		{
			if (!conwr_check_api_key()) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
			}
	
			if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
			}

			global $wp_xmlrpc_server;
			$wp_xmlrpc_server->escape($data);
			$json_request = $data[0];
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data) && !empty($post_data)) {
				$sc_id = isset($post_data->SCID) ? $post_data->SCID : '';
				$post_slug = isset($post_data->PostSlug) ? $post_data->PostSlug : '';
				$post_title = isset($post_data->PostTitle) ? $post_data->PostTitle : '';
				$post_content = isset($post_data->PostContent) ? $post_data->PostContent : '';
				$post_type = isset($post_data->PostType) ? $post_data->PostType : 'post';
				$post_images = isset($post_data->PostImages) ? $post_data->PostImages : '';
				$post_status = isset($post_data->PostStatus) ? $post_data->PostStatus : '';
				$post_date = isset($post_data->PostScheduled) ? $post_data->PostScheduled : '';
				$post_feat_image = isset($post_data->FeaturedImage) ? $post_data->FeaturedImage : '';
				$post_categories = isset($post_data->Categories) ? $post_data->Categories : '';
				$post_author = isset($post_data->Author) ? $post_data->Author : '';
				$post_meta_title = isset($post_data->METATitle) ? $post_data->METATitle : '';
				$post_meta_description = isset($post_data->METADescription) ? $post_data->METADescription : '';
				$post_meta_keywords = isset($post_data->METAKeywords) ? $post_data->METAKeywords : '';

				//replace Microsoft-encoded quotes with ASCII chars
				$post_title = iconv('UTF-8', 'ASCII//TRANSLIT', $post_title);
				$post_content = iconv('UTF-8', 'ASCII//TRANSLIT', $post_content);

				//date
				$post_date1 = date_create($post_date, new DateTimeZone('America/New_York'));
				$post_date_str = date_format($post_date1, 'Y-m-d H:i:s');

				$post_date1->setTimezone(new DateTimeZone('GMT'));
				$post_date_gmt_str = date_format($post_date1, 'Y-m-d H:i:s');

				//status
				if (strtolower($post_status) == 'draft' || $is_preview) {
					$post_status = "Draft";
				}
				else {
					$post_status = "Publish";
				}

				//author
				$post_author_object = get_user_by('id', $post_author);
				if ($post_author_object) {
					$post_author_id = $post_author_object->ID;
				}
				else {
					$wp_users = get_users();

					if (isset($wp_users) && count($wp_users) > 0) {
						$post_author_id = $wp_users[0]->ID;
					}
				}

				//categories
	            $category_ids = array();
	            if (isset($post_categories) && !empty($post_categories) && count($post_categories) > 0) {
	            	foreach ($post_categories as $category_obj) {
						$category_ids[] = $category_obj->CategoryID;
					}
				}

				if (!isset($category_ids) || count($category_ids) == 0) {
					$category_ids[] = get_option('default_category');
				}

				//content images
				$post_content = conwr_xmlrpc_insert_content_images($post_images, $post_content);

				//add meta tags
				$post_meta_tags = array();
				if (isset($sc_id) && !empty($sc_id)) {
					$post_meta_tags['steady_content_id'] = $sc_id;
				}

				//add meta tags for Yoast SEO plugin
				if (isset($post_meta_title) && !empty($post_meta_title)) {
					$post_meta_tags['_yoast_wpseo_title'] = $post_meta_title;
				}
				if (isset($post_meta_description) && !empty($post_meta_description)) {
					$post_meta_tags['_yoast_wpseo_metadesc'] = $post_meta_description;
				}
				if (isset($post_meta_keywords) && !empty($post_meta_keywords)) {
					$post_meta_tags['_yoast_wpseo_focuskw'] = $post_meta_keywords;
					$post_meta_tags['_conwr_sc_post_keywords'] = $post_meta_keywords;
				}

				//add meta tags for All In One SEO Pack plugin
				if (isset($post_meta_title) && !empty($post_meta_title)) {
					$post_meta_tags['_aioseop_title'] = $post_meta_title;
				}
				if (isset($post_meta_description) && !empty($post_meta_description)) {
					$post_meta_tags['_aioseop_description'] = $post_meta_description;
				}

	            $post_arr = array(
					'post_name'     => $post_slug,
				    'post_title'   	=> $post_title,
				    'post_content' 	=> $post_content,
				    'post_status'  	=> $post_status,
				    'post_type'  	=> $post_type,
				    'post_date'    	=> $post_date_str,
				    'post_date_gmt' => $post_date_gmt_str,
				    'post_author'  	=> $post_author_id,
				    'post_category' => $category_ids,
				    'meta_input'   	=> $post_meta_tags,
				);

	            $result = wp_insert_post($post_arr, true);
	            
	            if (is_wp_error($result)){
	            	conwr_api_write_log("Post was not inserted. Error: " . $result->get_error_message());
				    return json_encode(array('Status' => "Failed", 'Error' => $result->get_error_message()));
				}
				else {
					$post_id = $result;

		            if (isset($post_id) && $post_id > 0) {
		            	//insert featured image
						if (isset($post_feat_image) && !empty($post_feat_image)) {
							$image_name = $post_feat_image->ImageName;
							$image_url = $post_feat_image->ImageUrl;
							$image_alt = isset($post_feat_image->Alt) ? $post_feat_image->Alt : "";
							$image_href = isset($post_feat_image->LinkTo) ? $post_feat_image->LinkTo : "";
							$image_target = isset($post_feat_image->Target) ? $post_feat_image->Target : "";

							update_post_meta($post_id, '_conwr_fi_alt', $image_alt);
							update_post_meta($post_id, '_conwr_fi_href', $image_href);
							update_post_meta($post_id, '_conwr_fi_target', $image_target);

							if (isset($image_name) && !empty($image_name) && isset($image_url) && !empty($image_url)) {		
								conwr_xmlrpc_insert_attachment($image_name, $image_url, $post_id);
							}
						}
		            }

		            conwr_api_write_log("Post response is Successful. Post ID: $post_id. Post URL: " . get_permalink($post_id));

		            return json_encode(array('Status' => "Successful", 'PostID' => $post_id, 'PostURL' => get_permalink($post_id)));
		        }
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Post response is Failed. Error: " . $ex->getMessage());
			return json_encode(array('Status' => "Failed", 'Error' => $ex->getMessage()));
		}

	    return $response;
	}
}

if (!function_exists("conwr_xmlrpc_update_post")) {
	function conwr_xmlrpc_update_post($data, $is_preview = false) {
		$response = "";

		try
		{
			if (!conwr_check_api_key()) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
			}
	
			if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
			}

			global $wp_xmlrpc_server;
			$wp_xmlrpc_server->escape($data);
			$json_request = $data[0];
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data) && !empty($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$sc_id = isset($post_data->SCID) ? $post_data->SCID : '';
				$post_slug = isset($post_data->PostSlug) ? $post_data->PostSlug : '';
				$post_title = isset($post_data->PostTitle) ? $post_data->PostTitle : '';
				$post_content = isset($post_data->PostContent) ? $post_data->PostContent : '';
				$post_type = isset($post_data->PostType) ? $post_data->PostType : 'post';
				$post_images = isset($post_data->PostImages) ? $post_data->PostImages : '';
				$post_status = isset($post_data->PostStatus) ? $post_data->PostStatus : '';
				$post_date = isset($post_data->PostScheduled) ? $post_data->PostScheduled : '';
				$post_feat_image = isset($post_data->FeaturedImage) ? $post_data->FeaturedImage : '';
				$post_categories = isset($post_data->Categories) ? $post_data->Categories : '';
				$post_author = isset($post_data->Author) ? $post_data->Author : '';
				$post_meta_title = isset($post_data->METATitle) ? $post_data->METATitle : '';
				$post_meta_description = isset($post_data->METADescription) ? $post_data->METADescription : '';
				$post_meta_keywords = isset($post_data->METAKeywords) ? $post_data->METAKeywords : '';

				//replace Microsoft-encoded quotes with ASCII chars
				$post_title = iconv('UTF-8', 'ASCII//TRANSLIT', $post_title);
				$post_content = iconv('UTF-8', 'ASCII//TRANSLIT', $post_content);

				//date
				$post_date1 = date_create($post_date, new DateTimeZone('America/New_York'));
				$post_date_str = date_format($post_date1, 'Y-m-d H:i:s');

				$post_date1->setTimezone(new DateTimeZone('GMT'));
				$post_date_gmt_str = date_format($post_date1, 'Y-m-d H:i:s');

				//status
				if (strtolower($post_status) == 'draft' || $is_preview) {
					//if post is already published, don't modify it
					$post = get_post($post_id);

					if ($post !== false && strtolower($post->post_status) != 'publish') {
						$post_status = "Draft";
					}
					else {
						$post_status = "Publish";
					}
				}
				else {
					$post_status = "Publish";
				}

				//author
				$post_author_object = get_user_by('id', $post_author);
				if ($post_author_object) {
					$post_author_id = $post_author_object->ID;
				}
				else {
					$wp_users = get_users();

					if (isset($wp_users) && count($wp_users) > 0) {
						$post_author_id = $wp_users[0]->ID;
					}
				}

				//categories
	            $category_ids = array();
	            if (isset($post_categories) && !empty($post_categories) && is_array($post_categories) && count($post_categories) > 0) {
	            	foreach ($post_categories as $category_obj) {
						$category_ids[] = $category_obj->CategoryID;
					}
				}
				
				if (!isset($category_ids) || count($category_ids) == 0) {
					$category_ids[] = get_option('default_category');
				}

				//content images
				$post_content = conwr_xmlrpc_insert_content_images($post_images, $post_content);

				//meta tags
				$post_meta_tags = array();
				if (isset($sc_id) && !empty($sc_id)) {
					$post_meta_tags['steady_content_id'] = $sc_id;
				}

				//add meta tags for Yoast SEO plugin
				if (isset($post_meta_title) && !empty($post_meta_title)) {
					$post_meta_tags['_yoast_wpseo_title'] = $post_meta_title;
				}
				if (isset($post_meta_description) && !empty($post_meta_description)) {
					$post_meta_tags['_yoast_wpseo_metadesc'] = $post_meta_description;
				}
				if (isset($post_meta_keywords) && !empty($post_meta_keywords)) {
					$post_meta_tags['_yoast_wpseo_focuskw'] = $post_meta_keywords;
				}

				//add meta tags for All In One SEO Pack plugin
				if (isset($post_meta_title) && !empty($post_meta_title)) {
					$post_meta_tags['_aioseop_title'] = $post_meta_title;
				}
				if (isset($post_meta_description) && !empty($post_meta_description)) {
					$post_meta_tags['_aioseop_description'] = $post_meta_description;
				}

				$post_arr = array(
					'ID'   				=> $post_id,
					'post_title'   		=> $post_title,
					'post_content' 		=> $post_content,
					'post_status'  		=> $post_status,
					'post_date'    	    => $post_date_str,
					'post_date_gmt'     => $post_date_gmt_str,
					'post_modified'    	=> $post_date_str,
					'post_modified_gmt' => $post_date_gmt_str,
					'post_author'  		=> $post_author_id,
					'post_category' 	=> $category_ids,
					'meta_input'   		=> $post_meta_tags,
					);

				if (isset($post_id) && $post_id > 0) {
					update_post_meta($post_id, '_conwr_sc_post_keywords', $post_meta_keywords);

		            //insert featured image
					if (isset($post_feat_image) && !empty($post_feat_image)) {
						$image_name = $post_feat_image->ImageName;
						$image_url = $post_feat_image->ImageUrl;
						$image_alt = isset($post_feat_image->Alt) ? $post_feat_image->Alt : "";
						$image_href = isset($post_feat_image->LinkTo) ? $post_feat_image->LinkTo : "";
						$image_target = isset($post_feat_image->Target) ? $post_feat_image->Target : "";

						update_post_meta($post_id, '_conwr_fi_alt', $image_alt);
						update_post_meta($post_id, '_conwr_fi_href', $image_href);
						update_post_meta($post_id, '_conwr_fi_target', $image_target);

						if (isset($image_name) && !empty($image_name) && isset($image_url) && !empty($image_url)) {		
							conwr_xmlrpc_insert_attachment($image_name, $image_url, $post_id);
						}
					}
				}

				$result = wp_update_post($post_arr, true);

				if (is_wp_error($result)){
					return json_encode(array('Status' => "Failed", 'Error' => $result->get_error_message()));
				}
				else {
					$post_id = $result;

					set_post_type($post_id, $post_type);

					return json_encode(array('Status' => "Successful", 'PostID' => $post_id, 'PostURL' => get_permalink($post_id)));
				}
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Post response is Failed. Error: " . $ex->getMessage());
			return json_encode(array('Status' => "Failed", 'Error' => $ex->getMessage()));
		}

		return $response;
	}
}

if (!function_exists("conwr_xmlrpc_get_post")) {
	function conwr_xmlrpc_get_post($data) {
		$post_id = 0;

		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
			$wp_post = get_post($post_id, ARRAY_A);

			if (isset($wp_post)) {
				$response = array('Status' => "Successful");

				return json_encode(array_merge($response, $wp_post));
			}
		}

		return json_encode(array('Status' => "Failed", 'Error' => "Post {$post_id} could not be retrieved."));
	}
}

if (!function_exists("conwr_xmlrpc_get_posts")) {
	function conwr_xmlrpc_get_posts($data) {		
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		$args = array(
			'numberposts' => -1,
			'post_status' => 'any',
			'orderby'     => 'date',
			'order'       => 'DESC'
		);

		$posts = get_posts($args);

		if (isset($posts)) {
			return json_encode(array('Status' => "Successful", 'Posts' => $posts));
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Posts could not be retrieved."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_remove_post")) {
	function conwr_xmlrpc_remove_post($data) {
		$post_id = 0;

		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
			$wp_post = wp_delete_post($post_id);

			if ($wp_post) {
				return json_encode(array('Status' => "Successful", 'PostID' => $post_id));
			}
		}

		return json_encode(array('Status' => "Failed", 'Error' => "Post {$post_id} could not be deleted because it was not found."));
	}
}

if (!function_exists("conwr_xmlrpc_remove_draft_post")) {
	function conwr_xmlrpc_remove_draft_post($data) {
		$post_id = 0;

		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$post_id = isset($post_data->PostID) ? $post_data->PostID : '';

			$wp_post = get_post($post_id);

			if (isset($wp_post) && strtolower($wp_post->post_status) == "draft") {
				$wp_post_delete = wp_delete_post($post_id);

				if ($wp_post_delete) {
					return json_encode(array('Status' => "Successful", 'PostID' => $post_id));
				}
			}
		}

		return json_encode(array('Status' => "Failed", 'Error' => "Post {$post_id} could not be deleted because it was not found."));
	}
}

if (!function_exists("conwr_xmlrpc_create_post_preview")) {
	function conwr_xmlrpc_create_post_preview($data) {
		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
			$post_status = isset($post_data->PostStatus) ? $post_data->PostStatus : '';

			update_post_meta($post_id, '_conwr_is_preview', true);

			if (!empty($post_id) && $post_id > 0) {
					//check if post exists in WP database
				$wp_post = get_post($post_id);

				if (isset($wp_post)) {
					//update the existing WP post
					return conwr_xmlrpc_update_post($data, true);					
				}
				else {
					//create a new draft WP post
					return conwr_xmlrpc_create_post($data, true);
				}
			}
			else {
				//create a new draft WP post
				return conwr_xmlrpc_create_post($data, true);
			}
		}
		else {
			$error = "Post data is empty.";
			conwr_api_write_log("Error: Post data is empty. Function conwr_xmlrpc_create_post_preview; ");
		}

		return json_encode(array('Status' => "Failed", 'Error' => "Couldn't create a post preview. Reason: " . $error));
	}
}

if (!function_exists("conwr_xmlrpc_change_post_status")) {
	function conwr_xmlrpc_change_post_status($data) {
		$response = "";

		try
		{
			if (!conwr_check_api_key()) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
			}
	
			if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
			}

			global $wp_xmlrpc_server;
			$wp_xmlrpc_server->escape($data);
			$json_request = $data[0];
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data) && !empty($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$sc_id = isset($post_data->SCID) ? $post_data->SCID : '';
				$post_title = isset($post_data->PostTitle) ? $post_data->PostTitle : '';
				$post_content = isset($post_data->PostContent) ? $post_data->PostContent : '';
				$post_type = isset($post_data->PostType) ? $post_data->PostType : 'post';
				$post_images = isset($post_data->PostImages) ? $post_data->PostImages : '';
				$post_status = isset($post_data->PostStatus) ? $post_data->PostStatus : '';
				$post_date = isset($post_data->PostScheduled) ? $post_data->PostScheduled : '';
				$post_feat_image = isset($post_data->FeaturedImage) ? $post_data->FeaturedImage : '';
				$post_categories = isset($post_data->Categories) ? $post_data->Categories : '';
				$post_author = isset($post_data->Author) ? $post_data->Author : '';
				$post_meta_title = isset($post_data->METATitle) ? $post_data->METATitle : '';
				$post_meta_description = isset($post_data->METADescription) ? $post_data->METADescription : '';
				$post_meta_keywords = isset($post_data->METAKeywords) ? $post_data->METAKeywords : '';

				//replace Microsoft-encoded quotes with ASCII chars
				$post_title = iconv('UTF-8', 'ASCII//TRANSLIT', $post_title);
				$post_content = iconv('UTF-8', 'ASCII//TRANSLIT', $post_content);

				//date
				$post_date1 = date_create($post_date, new DateTimeZone('America/New_York'));
				$post_date_str = date_format($post_date1, 'Y-m-d H:i:s');

				$post_date1->setTimezone(new DateTimeZone('GMT'));
				$post_date_gmt_str = date_format($post_date1, 'Y-m-d H:i:s');

				//author
				$post_author_object = get_user_by('id', $post_author);
				if ($post_author_object) {
					$post_author_id = $post_author_object->ID;
				}
				else {
					$wp_users = get_users();

					if (isset($wp_users) && count($wp_users) > 0) {
						$post_author_id = $wp_users[0]->ID;
					}
				}

				//categories
	            $category_ids = array();
	            if (isset($post_categories) && !empty($post_categories) && is_array($post_categories) && count($post_categories) > 0) {
	            	foreach ($post_categories as $category_obj) {
						$category_ids[] = $category_obj->CategoryID;
					}
				}
				
				if (!isset($category_ids) || count($category_ids) == 0) {
					$category_ids[] = get_option('default_category');
				}

				//content images
				$post_content = conwr_xmlrpc_insert_content_images($post_images, $post_content);

				//meta tags
				$post_meta_tags = array();
				if (isset($sc_id) && !empty($sc_id)) {
					$post_meta_tags['steady_content_id'] = $sc_id;
				}

				//add meta tags for Yoast SEO plugin
				if (isset($post_meta_title) && !empty($post_meta_title)) {
					$post_meta_tags['_yoast_wpseo_title'] = $post_meta_title;
				}
				if (isset($post_meta_description) && !empty($post_meta_description)) {
					$post_meta_tags['_yoast_wpseo_metadesc'] = $post_meta_description;
				}
				if (isset($post_meta_keywords) && !empty($post_meta_keywords)) {
					$post_meta_tags['_yoast_wpseo_focuskw'] = $post_meta_keywords;
				}

				//add meta tags for All In One SEO Pack plugin
				if (isset($post_meta_title) && !empty($post_meta_title)) {
					$post_meta_tags['_aioseop_title'] = $post_meta_title;
				}
				if (isset($post_meta_description) && !empty($post_meta_description)) {
					$post_meta_tags['_aioseop_description'] = $post_meta_description;
				}

				$post_arr = array(
					'ID'   				=> $post_id,
					'post_title'   		=> $post_title,
					'post_content' 		=> $post_content,
					'post_status'  		=> $post_status,
					'post_date'    	    => $post_date_str,
					'post_date_gmt'     => $post_date_gmt_str,
					'post_modified'    	=> $post_date_str,
					'post_modified_gmt' => $post_date_gmt_str,
					'post_author'  		=> $post_author_id,
					'post_category' 	=> $category_ids,
					'meta_input'   		=> $post_meta_tags,
					);

				if (isset($post_id) && $post_id > 0) {
					update_post_meta($post_id, '_conwr_sc_post_keywords', $post_meta_keywords);

		            //insert featured image
					if (isset($post_feat_image) && !empty($post_feat_image)) {
						$image_name = $post_feat_image->ImageName;
						$image_url = $post_feat_image->ImageUrl;
						$image_alt = isset($post_feat_image->Alt) ? $post_feat_image->Alt : "";
						$image_href = isset($post_feat_image->LinkTo) ? $post_feat_image->LinkTo : "";
						$image_target = isset($post_feat_image->Target) ? $post_feat_image->Target : "";

						update_post_meta($post_id, '_conwr_fi_alt', $image_alt);
						update_post_meta($post_id, '_conwr_fi_href', $image_href);
						update_post_meta($post_id, '_conwr_fi_target', $image_target);

						if (isset($image_name) && !empty($image_name) && isset($image_url) && !empty($image_url)) {		
							conwr_xmlrpc_insert_attachment($image_name, $image_url, $post_id);
						}
					}
				}

				$result = wp_update_post($post_arr, true);

				if (is_wp_error($result)){
					return json_encode(array('Status' => "Failed", 'Error' => $result->get_error_message()));
				}
				else {
					$post_id = $result;

					set_post_type($post_id, $post_type);

					return json_encode(array('Status' => "Successful", 'PostID' => $post_id, 'PostURL' => get_permalink($post_id)));
				}
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Post response is Failed. Error: " . $ex->getMessage());
			return json_encode(array('Status' => "Failed", 'Error' => $ex->getMessage()));
		}

		return $response;
	}
}

if (!function_exists("conwr_xmlrpc_change_post_type")) {
	function conwr_xmlrpc_change_post_type($data) {
		try
		{
			if (!conwr_check_api_key()) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
			}
	
			if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
				return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
			}

			$json_request = $data["jsonrequest"];
	        $post_data = json_decode(urldecode($json_request));

			if (isset($post_data) && !empty($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$new_post_type = isset($post_data->PostType) ? $post_data->PostType : 'post';

				if (set_post_type($post_id, $new_post_type)) {
					$msg = $new_post_type == "post" ? "Page {$post_id} changed to post" : "Post {$post_id} changed to page";

					return json_encode(array('Status' => "Successful", 'Description' => $msg));
				} 
				else {
					return json_encode(array('Status' => "Failed", 'Description' => "Cannot change type for post {$post_id}"));
				}
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Failed while changing post type. Error: " . $ex->getMessage());
			return json_encode(array('Status' => "Failed", 'Error' => $ex->getMessage()));
		}
	}
}

if (!function_exists("conwr_xmlrpc_insert_attachment")) {
	function conwr_xmlrpc_insert_attachment($image_name, $image_url, $post_id) {
		$extension = "png";
		$wp_upload_dir = wp_upload_dir();
		$filename = '';

		$path_parts = pathinfo($image_name);
		$filename = $image_name;
		$extension = $path_parts['extension'];
		$file_path = $wp_upload_dir['path'] . '/' . $filename;

		//read image data from url
        $result = conwr_copy_remote_file($image_url, $file_path);

		if ($result) {
			$filetype = wp_check_filetype(basename($file_path), null);

			if ($post_id > 0) {				
				$attachment_id = get_post_thumbnail_id($post_id); //get feat image ID
			}
			else {
				$attachments_posts = get_posts(array('title' => str_replace("." . $extension, "", $filename), 'post_type' => 'attachment'));

				if (isset($attachments_posts) && is_array($attachments_posts) && count($attachments_posts) > 0) {
					$attachment_id = $attachments_posts[0]->ID;
				}
			}

			conwr_api_write_log("post_id: {$post_id}; attachment_id: {$attachment_id}; filename: {$filename}; ");

			$attachment = array(
				'ID'			 => (isset($attachment_id) && (int)$attachment_id > 0) ? $attachment_id : 0,
				'guid'           => $file_path, 
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_path)),
				'post_content'   => '',
				'post_status'    => 'inherit'
				);

			$attach_id = wp_insert_attachment($attachment, $file_path, $post_id); // Insert the attachment.

			if (is_wp_error($attach_id)){
				conwr_api_write_log("Attachment was not created for {$filename}. Reason: " . $attach_id->get_error_message());
			}

			// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			// Generate the metadata for the attachment, and update the database record.
			$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
			wp_update_attachment_metadata($attach_id, $attach_data);

			set_post_thumbnail($post_id, $attach_id);
		}
		else {
			conwr_api_write_log("Image cannot be loaded from url: {$image_url}");
		}
	}
}

if (!function_exists("conwr_xmlrpc_set_feat_image_html")) {
	function conwr_xmlrpc_set_feat_image_html($html, $post_id) {
		$is_preview = get_post_meta($post_id, "_conwr_is_preview", true);

		if ($is_preview) {
			delete_post_meta($post_id, "_conwr_is_preview");

			$fi_href = get_post_meta($post_id, "_conwr_fi_href", true);
			$fi_alt = get_post_meta($post_id, "_conwr_fi_alt", true);
			$fi_target = get_post_meta($post_id, "_conwr_fi_target", true);

			if (isset($fi_alt)) {
				$html = preg_replace('/(alt=")(.*?)(")/i', '$1' . esc_attr($fi_alt) . '$3', $html); //set alt
			}

			if (isset($fi_href) && !empty($fi_href)) {
				$html = '<a href="' . esc_attr($fi_href) . '" target="' . esc_attr($fi_target) . '">' . $html . '</a>'; //set href and target
			}
			else {
				$html = preg_replace('/<a.*?(<img.*?>)<\/a>/', '$1', $html); //remove link that surrounds image
			}
		}

		return $html;
	}
}
add_filter('post_thumbnail_html', 'conwr_xmlrpc_set_feat_image_html', 10, 2);

if (!function_exists("conwr_xmlrpc_insert_content_images")) {
	function conwr_xmlrpc_insert_content_images($post_images_object, $post_content)  {
		if (isset($post_images_object) && count($post_images_object) > 0) {
			$wp_upload_dir = wp_upload_dir();
			$abs_upload_path = site_url() . substr($wp_upload_dir['path'], strpos($wp_upload_dir['path'], "/wp-content"));

			foreach ($post_images_object as $image) {
				$image_name = $image->ImageName;
				$image_url = $image->ImageUrl;

				if (isset($image_name) && !empty($image_name) && isset($image_url) && !empty($image_url)) {
					//replace image path in the post content
					$post_content = str_replace("#" . $image_name . "#", $abs_upload_path . '/' . $image_name, $post_content);			

					//upload images on server
					conwr_xmlrpc_insert_attachment($image_name, $image_url, 0);
				}
			}

			//add alt and wrap image into link
			$dom = new DOMDocument;
			@$dom->loadHTML($post_content);
			$imgs = $dom->getElementsByTagName('img');

			foreach ($post_images_object as $image) {
				$image_name = $image->ImageName;

				if (isset($image_name) && !empty($image_name)) {
					//add alt and wrap image into link
					if (isset($imgs)) {
						foreach ($imgs as $img) {
							$img_clone = $img->cloneNode();

							$image_src = $img->getAttribute("src");

							if (strpos($image_src, $image_name) !== false) {
							    $img_clone->removeAttribute('alt');
							    $img_clone->setAttribute('alt', $image->Alt);

							    if (isset($image->LinkTo) && !empty($image->LinkTo)) {
							    	$img_link = $dom->createElement('a');
							    	$img_link->setAttribute('href', $image->LinkTo);
									if (isset($image->Target) && $image->Target == "_blank") {
							    		$img_link->setAttribute('target', $image->Target);
							    	}

							    	$img_link->appendChild($img_clone);

							    	$img->parentNode->replaceChild($img_link, $img);
							    }
							}
						}
					}
				}
			}

			$post_content = $dom->saveHTML();
		}

		return $post_content;
	}
}

if (!function_exists("conwr_xmlrpc_get_post_categories")) {
	function conwr_xmlrpc_get_post_categories($data) {
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		$categories = array();
		$cat_args = array('hide_empty' => 0);
		$wp_categories = get_categories($cat_args);

		if (isset($wp_categories)) {
			foreach ($wp_categories as $category) {
				$categories[] = array('Name' => $category->name, 'ID'   => $category->term_id);
			}

			return json_encode(array('Status' => "Successful", 'Categories' => $categories));
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Categories could not be retrieved."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_get_authors")) {
	function conwr_xmlrpc_get_authors($data) {
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		$users = array();
		$wp_users = get_users();

		if (isset($wp_users)) {
			foreach ($wp_users as $user) {
				$users[] = array(
					'Username'   => $user->user_login,
					'Name'   => $user->user_login . " (" . $user->user_firstname . ' ' . $user->user_lastname . ")",
					'ID'   => $user->ID,
					);
			}

			return json_encode(array('Status' => "Successful", 'Authors' => $users));
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Authors could not be retrieved."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_create_category")) {
	function conwr_xmlrpc_create_category($data) {
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			if (!function_exists("wp_create_category")) {
	            require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-admin/includes/taxonomy.php');
	        }

			$category_id = wp_create_category($post_data->CategoryName);

			if (isset($category_id) && $category_id > 0) {
				return json_encode(array('Status' => "Successful", 'ID' => $category_id, 'Name' => $post_data->CategoryName));
			}
		}

		if ($error) {
			return json_encode(array('Status' => "Failed", 'Error' => "Category " . $post_data->CategoryName . " could not be created."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_create_author")) {
	function conwr_xmlrpc_create_author($data) {
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$username = $post_data->FirstName . "_" . $post_data->LastName;
			$password = substr(str_replace('-', '', strtolower(conwr_get_guid())), 0, 10);

			if (!empty($username) && !empty($password) && !empty($post_data->Email)) {
				$userdata = array(
				    'user_login' =>  $username,
				    'user_pass'  =>  $password,
				    'user_email' =>  $post_data->Email,
				    'first_name' =>  $post_data->FirstName,
				    'last_name'  =>  $post_data->LastName,
				);

				$user_id = wp_insert_user($userdata);

				if (is_wp_error($user_id)){
					return json_encode(array('Status' => "Failed", 'Error' => 'User was not created. Reason: ' . $user_id->get_error_message()));
				}
				else {
					return json_encode(array('Status' => "Successful", 'ID' => $user_id, 'Username' => $user->user_login, 
									  'Name' => $user->user_login . " (" . $user->user_firstname . ' ' . $user->user_lastname . ")"));
				}
			}
			else {
				return json_encode(array('Status' => "Failed", 'Error'  => "Input data are not valid."));
			}
		}

		return json_encode(array('Status' => "Failed", 'Error' => "Author " . $post_data->FirstName . " " . $post_data->LastName . " could not be created."));
	}
}

if (!function_exists("conwr_xmlrpc_get_plugins")) {
	function conwr_xmlrpc_get_plugins($data) {
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error' => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error' => "Request token is not valid."));
		}

		if (!function_exists('get_plugin_data')){
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		$plugins = array();
		$wp_plugins = get_plugins();

		if (isset($wp_plugins) && is_array($wp_plugins)) {
			foreach ($wp_plugins as $plugin_key => $plugin_value) {
				$plugins[] = array(
					'Name' => $plugin_value['Name'],
					'Version'   => $plugin_value['Version']
				);
			}

			return json_encode(array('Status' => "Successful", 'Plugins' => $plugins));
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Plugins could not be retrieved."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_get_curent_theme")) {
	function conwr_xmlrpc_get_curent_theme($data) {
		if (!conwr_check_api_key()) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Plugin is not verified."));
		}

		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		$theme = array();
		$wp_theme = wp_get_theme();

		if (isset($wp_theme)) {
			$theme[] = array(
				'Name' => $wp_theme->get('Name'),
				'Version'   => $wp_theme->get('Version'),
				);

			return json_encode(array('Status' => "Successful", 'Theme' => $theme));
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Themes could not be retrieved."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_check_plugin")) {
	function conwr_xmlrpc_check_plugin($data) {
		if (is_plugin_active('content-writer/content-writer.php')) {
			return json_encode(array('Status' => "Successful"));
		}

		return json_encode(array('Status' => "Failed"));
	}
}

if (!function_exists("conwr_xmlrpc_get_plugin_update_status")) {
	function conwr_xmlrpc_get_plugin_update_status($data) {		
		$installed_version = conwr_get_plugin_current_version(ABSPATH . 'wp-content/plugins/content-writer/content-writer.php');
        $latest_version = conwr_get_plugin_latest_version(); //latest version from WordPress repository

        if ($installed_version != 0 && $latest_version != 0) {
            if (version_compare($latest_version, $installed_version, '>')) {
				return json_encode(array('Status' => "Successful", 'NeedsUpdate' => "Yes", 'CurrentVersion' => $installed_version));
            }
            else if (version_compare($latest_version, $installed_version, '<=')) {
				return json_encode(array('Status' => "Successful", 'NeedsUpdate' => "No", 'CurrentVersion' => $installed_version));
            }
		}
		
		return json_encode(array('Status' => "Failed", 'Error' => "Could not get plugin update status."));
	}
}

if (!function_exists("conwr_xmlrpc_is_plugin_verified")) {
	function conwr_xmlrpc_is_plugin_verified($data) {
		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$email = $post_data->Email;
			$api_key = $post_data->APIKey;

			$sc_email = get_option("conwr_email", false);
			$sc_api_key = get_option("conwr_api_key", false);

			if ($email == $sc_email && $api_key == $sc_api_key) {
			    return json_encode(array('Status' => "Successful"));
			}
			else {
				return json_encode(array('Status' => "Failed", 'Error' => "Plugin is not verified."));
			}
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Post data are not valid."));
		}
	}
}

if (!function_exists("conwr_xmlrpc_verify_plugin_from_app")) {
	function conwr_xmlrpc_verify_plugin_from_app($data) {		
		if (!conwr_xmlrpc_rest_validate_token($data, __FUNCTION__)) {
			return json_encode(array('Status' => "Failed", 'Error'  => "Request token is not valid."));
		}

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($data);
		$json_request = $data[0];
		$post_data = json_decode(urldecode($json_request));

		if (isset($post_data) && !empty($post_data)) {
			$email = $post_data->Email;
			$api_key = $post_data->APIKey;

			if (!empty($email) && !empty($api_key)) {
				update_option("conwr_email", $email);
			    update_option("conwr_api_key", $api_key);

			    return json_encode(array('Status' => "Successful"));
			}
			else {
				return json_encode(array('Status' => "Failed", 'Error' => "Input data are not valid."));
			}
		}
		else {
			return json_encode(array('Status' => "Failed", 'Error' => "Post data are not valid."));
		}
	}
}

/*add_filter('posts_results', 'conwr_xmlrpc_preview_scheduled', null, 2);
if (!function_exists("conwr_xmlrpc_preview_scheduled")) {
	function conwr_xmlrpc_preview_scheduled($posts, $query) {
	    if (sizeof($posts) != 1) 
	    	return $posts;

	    if (($posts[0]->post_status == "future" || $posts[0]->post_status == "draft") && isset( $_GET['key']) && $_GET['key'] == 'steadycontent') {
			$query->_my_future_stash = $posts;

			add_filter('the_posts', 'conwr_xmlrpc_inject_scheduled', null, 1);
		}
		else {
			return $posts;
		}
	}
}

if (!function_exists("conwr_xmlrpc_inject_scheduled")) {
	function conwr_xmlrpc_inject_scheduled($posts, $query) {
	    remove_filter('the_posts', 'conwr_xmlrpc_inject_scheduled', null);
	    return $query->_my_future_stash;
	}
}*/
?>