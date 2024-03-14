<?php
require_once(dirname( __FILE__ ) . '/sc_functions.php');

define('CONWR_PLUGIN_API_URL', 'http://webapi.postrx.com/api/wordpressplugin/GetIsTokenValid');
define('CONWR_PLUGIN_API_URL_WITHOUT_METHOD_NAME', 'http://webapi.postrx.com/api/wordpressplugin');
//define('CONWR_PLUGIN_API_URL', 'http://ec2-54-197-38-71.compute-1.amazonaws.com/steadycontent/plugin/plugin.aspx');
//define('CONWR_PLUGIN_API_URL', 'http://localhost/steadycontent/plugin/plugin.aspx');

if (!function_exists("conwr_api_init")) {
	function conwr_api_init() {
		$sc = isset($_REQUEST['sc']) ? $_REQUEST['sc'] : '';
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

		if (isset($sc) && $sc == 'true') {
			$skip = false;

			if (empty($type)) {
				$type = isset($_REQUEST['Type']) ? $_REQUEST['Type'] : '';
			}

			if (isset($type) && !empty($type)) {
				switch ($type) {
					case 'InsertPost':
						echo stripslashes(json_encode(conwr_restapi_create_post(false), JSON_UNESCAPED_SLASHES));
					break;
					case 'UpdatePost':
						echo stripslashes(json_encode(conwr_restapi_update_post(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetPost':
						echo stripslashes(json_encode(conwr_restapi_get_post(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetPosts':
						echo stripslashes(json_encode(conwr_restapi_get_posts(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetAuthors':
						echo stripslashes(json_encode(conwr_restapi_get_authors(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetCategories':
						echo stripslashes(json_encode(conwr_restapi_get_post_categories(), JSON_UNESCAPED_SLASHES));
					break;
					case 'CreateAuthor':
						echo stripslashes(json_encode(conwr_restapi_create_author(), JSON_UNESCAPED_SLASHES));
					break;
					case 'CreateCategory':
						echo stripslashes(json_encode(conwr_restapi_create_category(), JSON_UNESCAPED_SLASHES));
					break;
					case 'IsMultisite':
						echo stripslashes(json_encode(conwr_restapi_is_multisite(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetMultisiteSites':
						echo stripslashes(json_encode(conwr_restapi_get_multisite_sites(), JSON_UNESCAPED_SLASHES));
					break;
					case 'RemovePost':
						echo stripslashes(json_encode(conwr_restapi_remove_post(), JSON_UNESCAPED_SLASHES));
					break;
					case 'RemoveDraftPost':
						echo stripslashes(json_encode(conwr_restapi_remove_draft_post(), JSON_UNESCAPED_SLASHES));
					break;
					case 'CreatePostPreview':
						echo stripslashes(json_encode(conwr_restapi_create_post_preview(), JSON_UNESCAPED_SLASHES));
					break;
					case 'ChangePostStatus':
						echo stripslashes(json_encode(conwr_restapi_change_post_status(), JSON_UNESCAPED_SLASHES));
					break;
					case 'ChangePostType':
						echo stripslashes(json_encode(conwr_restapi_change_post_type(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetPlugins':
						echo stripslashes(json_encode(conwr_restapi_get_plugins(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetCurrentTheme':
						echo stripslashes(json_encode(conwr_restapi_get_current_theme(), JSON_UNESCAPED_SLASHES));
					break;
					case 'CheckIfPluginActive':
						echo stripslashes(json_encode(conwr_restapi_check_plugin(), JSON_UNESCAPED_SLASHES));
					break;
					case 'GetPluginStatus':
						echo stripslashes(json_encode(conwr_restapi_get_plugin_update_status(), JSON_UNESCAPED_SLASHES));
					break;
					case 'IsPluginVerified':
						echo stripslashes(json_encode(conwr_restapi_is_plugin_verified(), JSON_UNESCAPED_SLASHES));
					break;
					case 'VerifyPlugin':
						echo stripslashes(json_encode(conwr_restapi_verify_plugin_from_app(), JSON_UNESCAPED_SLASHES));
					break;
                    case 'UpdatePlugin':
                        echo stripslashes(json_encode(conwr_restapi_update_plugin_from_app(), JSON_UNESCAPED_SLASHES));
                    break;
					default:
						$skip = true;
					break;
				}

				if (!$skip) {
					exit();
				}
			}
			else {
				conwr_api_write_log("Plugin request failed: 'type' param is missing.");
			}
		}
		else {
			//conwr_api_write_log("Plugin request failed: 'sc' param is missing.");
		}
	}
}

if (!function_exists("conwr_restapi_validate_token")) {
	function conwr_restapi_validate_token($api_method, $function_name = "") {
		try {
			$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

			if (empty($token)) {
				$token = isset($_REQUEST['Token']) ? $_REQUEST['Token'] : '';
			}
			
			if (isset($token) && !empty($token)) {
				//check security token
				$URL = CONWR_PLUGIN_API_URL . "?Type=$api_method&Token=$token";

				conwr_api_write_log("Sending token validation request to: $URL. Function: $function_name.");

				$api_response = conwr_get_request($URL);
				$api_response = stripcslashes($api_response);

				conwr_api_write_log("Received token validation response: $api_response. Function: $function_name.");

				$api_response_obj = json_decode($api_response);

				if (isset($api_response_obj)) {
					if ($api_response_obj->Status == "Successful") {
						conwr_api_write_log("Token ($token) validated SUCCESSFULLY. Function: $function_name.");
						return $api_response_obj;
					}
					else {
						conwr_api_write_log("Invalid token response from API. Function: $function_name.");
					}
				}	
			}
			else {
				conwr_api_write_log("Token validation failed. Reason: Request data is empty. Function: $function_name.");
			}
		}
		catch (Exception $e) {
			conwr_api_write_log("Token validation failed. Reason: " . $e->getMessage() . ". Function: $function_name.");
		}

		return false;
	}
}

if (!function_exists("conwr_restapi_is_multisite")) {
	function conwr_restapi_is_multisite() {
		$api_method = 'IsMultisite';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}
		
		$is_multisite = is_multisite() ? "true" : "false";

		return array('Status' => "Successful", 'IsMultisite' => $is_multisite);
	}
}

if (!function_exists("conwr_restapi_get_multisite_sites")) {
	function conwr_restapi_get_multisite_sites() {		
		$api_method = 'SitesGet';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		$blog_url = home_url();

		if (!is_multisite()) {
			return array('Status' => "Failed", 'Error'  => "Site {$blog_url} is not multisite");
		}

		$sites = get_sites();

		if (isset($sites) && is_array($sites)) {
			$response = array('Status' => "Successful", 'Sites'  => $sites);
		}
		else {
			$response = array('Status' => "Failed", 'Error'  => "Sites could not be retrieved.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_create_post")) {
	function conwr_restapi_create_post($is_preview = false) {
		conwr_api_write_log("Started function: " . __FUNCTION__ . ". -----------------");

		$response = "";

		try
		{			
			$api_method = 'InsertPost';
			$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

			if (!$api_response) {
				return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
			}

			conwr_api_write_log("Token validated. Function: " . __FUNCTION__);

			if (!isset($api_response->Data)) {
				conwr_api_write_log("Parameter Data is empty. Function: " . __FUNCTION__);
				return array('Status' => "Failed", 'Error' => "Parameter Data is empty.");
			}
	
			$post_data = $api_response->Data;

			if (isset($post_data)) {
				conwr_api_write_log("Post data OK. Function: " . __FUNCTION__);

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

				$post_title = urldecode(decode_value_for_plugin($post_title, __FUNCTION__));
				$post_content = urldecode(decode_value_for_plugin($post_content, __FUNCTION__));

				conwr_api_write_log("SCID: $sc_id. Function: " . __FUNCTION__);
				conwr_api_write_log("Post slug: $post_slug. Function: " . __FUNCTION__);
			
				//date
				$post_date1 = date_create($post_date, new DateTimeZone('America/New_York'));
				$post_date_str = date_format($post_date1, 'Y-m-d H:i:s');

				$post_date1->setTimezone(new DateTimeZone('GMT'));
				$post_date_gmt_str = date_format($post_date1, 'Y-m-d H:i:s');

				conwr_api_write_log("Post date GMT: $post_date_gmt_str. Function: " . __FUNCTION__);

				//status
				if (strtolower($post_status) == 'draft' || $is_preview) {
					$post_status = "Draft";
				}
				else {
					$post_status = "Publish";
				}

				conwr_api_write_log("Post status: $post_status. Function: " . __FUNCTION__);

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

				conwr_api_write_log("Post author: $post_author_id. Function: " . __FUNCTION__);
		
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
				$post_content = conwr_restapi_insert_content_images($post_images, $post_content);

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
					$response = array('Status' => "Failed", 'Error' => $result->get_error_message());
				}
				else {
					$post_id = $result;

					conwr_api_write_log("Post created SUCCESSFULLY. Post Id: $post_id. Function: " . __FUNCTION__);

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
								conwr_restapi_insert_attachment($image_name, $image_url, $post_id);
							}
						}
					}

					$post = get_post( $post_id );

					$original_status = $post->post_status;
					$original_date = $post->post_date;
					$original_name = $post->post_name;

					// Hack: get_permalink() would return ugly permalink for drafts, so we will fake that our post is published.
					if ( in_array( $post->post_status, array( 'draft', 'pending', 'future' ) ) ) {
						$post->post_status = 'publish';
						$post->post_name = sanitize_title($post->post_name ? $post->post_name : $post->post_title, $post->ID);
					}

					$url = wp_get_attachment_url( get_post_thumbnail_id($post_id), 'thumbnail' );
	
					conwr_api_write_log("Post URL: " . get_permalink($post_id) . ". Function: " . __FUNCTION__);

					$response = array(
						'Status'  => "Successful",
						'PostID'  => $post_id,
						'PostURL' => get_permalink($post_id),
						'PostFriendlyName' => $post->post_name,
						'ImageUrl' => $url,
					);
				}
			}
			else {
				conwr_api_write_log("Could not create post, post data is empty. Function: " . __FUNCTION__);
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Post response is Failed. Error: " . $ex->getMessage());
			$response = array('Status' => "Failed", 'Error' => $ex->getMessage());
		}

	    return $response;
	}
}

if (!function_exists("conwr_restapi_update_post")) {
	function conwr_restapi_update_post($is_preview = false) {
		conwr_api_write_log("Started function: " . __FUNCTION__ . ". -----------------");

		$response = "";

		try
		{	
			$api_method = 'UpdatePost';
			$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

			if (!$api_response) {
				return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
			}

			conwr_api_write_log("Token validated. Function: " . __FUNCTION__);

			if (!isset($api_response->Data)) {
				conwr_api_write_log("Parameter Data is empty. Function: " . __FUNCTION__);
				return array('Status' => "Failed", 'Error' => "Parameter Data is empty.");
			}
	
			$post_data = $api_response->Data;

			if (isset($post_data)) {
				conwr_api_write_log("Post data OK. Function: " . __FUNCTION__);

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

				$post_title = urldecode(decode_value_for_plugin($post_title, __FUNCTION__));
				$post_content = urldecode(decode_value_for_plugin($post_content, __FUNCTION__));

				conwr_api_write_log("Post id: $post_id. Function: " . __FUNCTION__);
				conwr_api_write_log("SCID: $sc_id. Function: " . __FUNCTION__);
				conwr_api_write_log("Post slug: $post_slug. Function: " . __FUNCTION__);

				//date
				$post_date1 = date_create($post_date, new DateTimeZone('America/New_York'));
				$post_date_str = date_format($post_date1, 'Y-m-d H:i:s');

				$post_date1->setTimezone(new DateTimeZone('GMT'));
				$post_date_gmt_str = date_format($post_date1, 'Y-m-d H:i:s');

				conwr_api_write_log("Post date GMT: $post_date_gmt_str. Function: " . __FUNCTION__);

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

				conwr_api_write_log("Post status: $post_status. Function: " . __FUNCTION__);

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

				conwr_api_write_log("Post author: $post_author_id. Function: " . __FUNCTION__);

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
				$post_content = conwr_restapi_insert_content_images($post_images, $post_content);

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
							conwr_restapi_insert_attachment($image_name, $image_url, $post_id);
						}
					}
				}

				$result = wp_update_post($post_arr, true);

				if (is_wp_error($result)) {
					conwr_api_write_log("Post was not updated. Error: " . $result->get_error_message());

					$response = array('Status' => "Failed", 'Error' => $result->get_error_message());
				}
				else {
					conwr_api_write_log("Post updated SUCCESSFULLY. Post Id: $post_id. Function: " . __FUNCTION__);

					$post_id = $result;
					set_post_type($post_id, $post_type);

					$post = get_post( $post_id );

					$original_status = $post->post_status;
					$original_date = $post->post_date;
					$original_name = $post->post_name;

					// Hack: get_permalink() would return ugly permalink for drafts, so we will fake that our post is published.
					if ( in_array( $post->post_status, array( 'draft', 'pending', 'future' ) ) ) {
						$post->post_status = 'publish';
						$post->post_name = sanitize_title($post->post_name ? $post->post_name : $post->post_title, $post->ID);
					}

					$url = wp_get_attachment_url( get_post_thumbnail_id($post_id), 'thumbnail' );

					conwr_api_write_log("Post URL: " . get_permalink($post_id) . ". Function: " . __FUNCTION__);

					$response = array(
						'Status'   	   => "Successful",
						'PostID'   	   => $post_id,
						'PostURL' 	   => get_permalink($post_id),
						'PostFriendlyName' 	   => $post->post_name,
						'ImageUrl' => $url,
						);
				}
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Post response is Failed. Error: " . $ex->getMessage());
			$response = array('Status' => "Failed", 'Error' => $ex->getMessage());
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_get_post")) {
	function conwr_restapi_get_post() {
		$error = true;
		$post_id = 0;

		$api_method = 'GetPost';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		if (isset($_GET["Data"])) {
			$json_request = stripcslashes($_GET["Data"]);
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$wp_post = get_post($post_id, ARRAY_A);

				$url = wp_get_attachment_url( get_post_thumbnail_id($post_id), 'thumbnail' );

				if (isset($wp_post)) {
					$response = array('Status' => "Successful");
					$response = array_merge($response, array('PostURL' => get_permalink($post_id))); 
					$response = array_merge($response, array('ImageURL' => $url)); 
					$response = array_merge($response, $wp_post);
					$error = false;
				}
			}

			if ($error) {
				$response = array('Status' => "Failed", 'Error' => "Post {$post_id} could not be retrieved.");
			}
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_get_posts")) {
	function conwr_restapi_get_posts() {		
		$api_method = 'GetPosts';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		$args = array(
			'numberposts' => -1,
			'post_status' => 'any',
			'orderby'     => 'date',
			'order'       => 'DESC'
		);

		$posts = get_posts($args);

		if (isset($posts)) {
			$response = array('Status' => "Successful", 'Posts'  => $posts);
		}
		else {
			$response = array('Status' => "Failed", 'Error'  => "Posts could not be retrieved.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_remove_post")) {
	function conwr_restapi_remove_post() {
		$error = true;
		$post_id = 0;

		$api_method = 'RemovePost';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		if (isset($_GET["Data"])) {
			$json_request = stripcslashes($_GET["Data"]);
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$wp_post = wp_delete_post($post_id);

				if ($wp_post) {
					$response = array(
						'Status'   	   => "Successful",
						'PostID' => $post_id,
					);

					$error = false;
				}
			}
		}

		if ($error) {
			$response = array('Status' => "Failed", 'Error' => "Post {$post_id} could not be deleted because it was not found.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_remove_draft_post")) {
	function conwr_restapi_remove_draft_post() {
		$error = true;
		$post_id = 0;

		$api_method = 'RemoveDraftPost';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		if (isset($_GET["Data"])) {
			$json_request = stripcslashes($_GET["Data"]);
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';

				$wp_post = get_post($post_id);

				if (isset($wp_post) && strtolower($wp_post->post_status) == "draft") {
					$wp_post_delete = wp_delete_post($post_id);

					if ($wp_post_delete) {
						$response = array('Status' => "Successful", 'PostID' => $post_id);

						$error = false;
					}
				}
			}

			if ($error) {
				$response = array('Status' => "Failed", 'Error' => "Post {$post_id} could not be deleted because it was not found.");
			}
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_create_post_preview")) {
	function conwr_restapi_create_post_preview() {
		conwr_api_write_log("Started function: " . __FUNCTION__ . ". -----------------");

		$response = "";
		$error = "";
		
		if (isset($_GET["Data"])) {
			$json_request = stripcslashes($_GET["Data"]);
			$post_data = json_decode(urldecode($json_request));
	    
			if (isset($post_data)) {
				conwr_api_write_log("Post data OK. Function: " . __FUNCTION__);

				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$post_status = isset($post_data->PostStatus) ? $post_data->PostStatus : '';
			
				update_post_meta($post_id, '_conwr_is_preview', true);

				conwr_api_write_log("Post meta _conwr_is_preview updated. Value: $post_id. Function" . __FUNCTION__);

				if (!empty($post_id) && $post_id > 0) {
					conwr_api_write_log("Post id exists. Getting post from db... Function" . __FUNCTION__);

					//check if post exists in WP database
					$wp_post = get_post($post_id);

					if (isset($wp_post)) {
						conwr_api_write_log("Post found. Updating post $post_id... Function" . __FUNCTION__);

						//update the existing WP post
						return conwr_restapi_update_post(true);					
					}
					else {
						conwr_api_write_log("Post not found. Creating new post... Function" . __FUNCTION__);

						//create a new draft WP post
						return conwr_restapi_create_post(true);
					}
				}
				else {
					conwr_api_write_log("Post id doesn't exist. Creating new post... Function" . __FUNCTION__);

					//create a new draft WP post
					return conwr_restapi_create_post(true);
				}
			}
			else {
				$error = "Post data is empty.";
				conwr_api_write_log("Error: Post data is empty. Function: " . __FUNCTION__);
			}

			$response = array(
				'Status' => "Failed",
				'Error'  => "Couldn't create a post preview. Reason: " . $error,
			);
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_change_post_status")) {
	function conwr_restapi_change_post_status() {
		conwr_api_write_log("Started function: " . __FUNCTION__ . ". -----------------");

		$response = "";

		try
		{	
			$api_method = 'ChangePostStatus';
			$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

			if (!$api_response) {
				return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
			}

			conwr_api_write_log("Token validated: Function: " . __FUNCTION__);

			if (isset($_GET["Data"])) {
				$json_request = stripcslashes($_GET["Data"]);
				$post_data = json_decode(urldecode($json_request));

				if (isset($post_data)) {
					conwr_api_write_log("Post data OK: Function: " . __FUNCTION__);

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
					//$post_title = iconv('UTF-8', 'ASCII//TRANSLIT', $post_title);
					//$post_content = iconv('UTF-8', 'ASCII//TRANSLIT', $post_content);

					conwr_api_write_log("Post id: $post_id. Function: " . __FUNCTION__);
					conwr_api_write_log("SC id: $sc_id. Function: " . __FUNCTION__);

					//date
					$post_date1 = date_create($post_date, new DateTimeZone('America/New_York'));
					$post_date_str = date_format($post_date1, 'Y-m-d H:i:s');

					$post_date1->setTimezone(new DateTimeZone('GMT'));
					$post_date_gmt_str = date_format($post_date1, 'Y-m-d H:i:s');

					conwr_api_write_log("Post date GMT: $post_date_gmt_str. Function: " . __FUNCTION__);

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

					conwr_api_write_log("Post author: $post_author_id. Function: " . __FUNCTION__);

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
					$post_content = conwr_restapi_insert_content_images($post_images, $post_content);

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
								conwr_restapi_insert_attachment($image_name, $image_url, $post_id);
							}
						}
					}

					$result = wp_update_post($post_arr, true);

					if (is_wp_error($result)){
						conwr_api_write_log("Post was not updated. Error: " . $result->get_error_message());

						$response = array('Status' => "Failed", 'Error' => $result->get_error_message());
					}
					else {
						conwr_api_write_log("Post $post_id updated. Function: " . __FUNCTION__);

						$post_id = $result;

						set_post_type($post_id, $post_type);

						$url = wp_get_attachment_url( get_post_thumbnail_id($post_id), 'thumbnail' );

						conwr_api_write_log("Post url: " . get_permalink($post_id) . ". Function: " . __FUNCTION__);

						$response = array(
							'Status'   	   => "Successful",
							'PostID'   	   => $post_id,
							'PostURL' 	   => get_permalink($post_id),
							'ImageUrl' => $url,
						);
					}
				}
			}
		}
		catch(Exception $ex) {
			conwr_api_write_log("Post response is Failed. Error: " . $ex->getMessage());
			$response = array('Status' => "Failed", 'Error' => $ex->getMessage());
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_change_post_type")) {
	function conwr_restapi_change_post_type() {
		$api_method = 'ChangePostType';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		if (isset($_GET["Data"])) {
			$json_request = stripcslashes($_GET["Data"]);
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data)) {
				$post_id = isset($post_data->PostID) ? $post_data->PostID : '';
				$new_post_type = isset($post_data->PostType) ? $post_data->PostType : 'post';

				if (set_post_type($post_id, $new_post_type)) {
					$msg = $new_post_type == "post" ? "Page {$post_id} changed to post" : "Post {$post_id} changed to page";

					return array('Status' => "Successful", 'Description' => $msg);
				} 
				else {
					return array('Status' => "Failed", 'Description' => "Cannot change type for post {$post_id}");
				}
			}
		}
	}
}

if (!function_exists("conwr_restapi_insert_attachment")) {
	function conwr_restapi_insert_attachment($image_name, $image_url, $post_id) {
		$extension = "png";
		$wp_upload_dir = wp_upload_dir();
		$filename = '';

		$path_parts = pathinfo($image_name);
		$filename = $image_name;
		$extension = $path_parts['extension'];
		$file_path = $wp_upload_dir['path'] . '/' . $filename;

		//read image data from url
        $result = conwr_restapi_copy_post_image($image_url, $file_path);

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

			$attach_id = wp_insert_attachment($attachment, $file_path, $post_id, true); // Insert the attachment.

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
			conwr_api_write_log("Image cannot be copied from url: {$image_url}");
		}
	}
}

if (!function_exists('convert_image_to_base64_curl')) {
	function convert_image_to_base64_curl($image_url) {
		$ch = curl_init($image_url);

		// Configurar opciones de cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Realizar la solicitud cURL
		$image_data = curl_exec($ch);

		// Verificar si hubo errores
		if (curl_errno($ch)) {
			return "Error en la solicitud cURL: " . curl_error($ch);
		}    // Cerrar la sesión cURL
		curl_close($ch);

		// Codificar el contenido de la imagen como Base64
		$base64_image = base64_encode($image_data);

		return $base64_image;
	}
}

if (!function_exists('conwr_restapi_copy_post_image')) {
    //Saves file from source url to destination file.
    function conwr_restapi_copy_post_image($image_url, $dest_file) {
		// Encode the image data in base64 format
		conwr_api_write_log("This is the image path: $image_url.");
		$base64Encoded = convert_image_to_base64_curl($image_url);
        conwr_api_write_log("This is the image base64: $base64Encoded.");

		if($base64Encoded  === false) {
			conwr_api_write_log("Error while trying to copy image from url: $image_url.  Function: " . __FUNCTION__);
			return false;
		}
		else {

			//save response as image in wp-content folder at provided path
			file_put_contents($dest_file, base64_decode($base64Encoded));

			return true;
		}
    }
}

if (!function_exists("conwr_restapi_set_feat_image_html")) {
	function conwr_restapi_set_feat_image_html($html, $post_id) {
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
add_filter('post_thumbnail_html', 'conwr_restapi_set_feat_image_html', 10, 2);

if (!function_exists("conwr_restapi_insert_content_images")) {
	function conwr_restapi_insert_content_images($post_images_object, $post_content)  {
		if (isset($post_images_object) && is_array($post_images_object)) {
			$wp_upload_dir = wp_upload_dir();
			$abs_upload_path = site_url() . substr($wp_upload_dir['path'], strpos($wp_upload_dir['path'], "/wp-content"));

			if (count($post_images_object) > 0) {
				foreach ($post_images_object as $image) {
					$image_name = $image->ImageName;

					if(isset($image->ImageUrl))
						$image_url = $image->ImageUrl;

					if (isset($image_name) && !empty($image_name) && isset($image_url) && !empty($image_url)) {
						//replace image path in the post content
						$post_content = str_replace("#" . $image_name . "#", $abs_upload_path . '/' . $image_name, $post_content);			

						//upload images on server
						conwr_restapi_insert_attachment($image_name, $image_url, 0);
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
		}

		return $post_content;
	}
}

if (!function_exists("conwr_restapi_get_post_categories")) {
	function conwr_restapi_get_post_categories() {
		$api_method = 'GetCategories';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		$categories = array();
		$cat_args = array('hide_empty' => 0);
		$wp_categories = get_categories($cat_args);

		if (isset($wp_categories)) {
			foreach ($wp_categories as $category) {
				$categories[] = array(
					'Name' => $category->name,
					'ID'   => $category->term_id,
					);
			}

			$response = array('Status' => "Successful", 'Categories' => $categories);
		}
		else {
			$response = array('Status' => "Failed", 'Error' => "Categories could not be retrieved.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_get_authors")) {
	function conwr_restapi_get_authors() {
		$api_method = 'GetAuthors';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
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

			$response = array('Status' => "Successful", 'Authors' => $users);
		}
		else {
			$response = array('Status' => "Failed", 'Error' => "Authors could not be retrieved.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_create_category")) {
	function conwr_restapi_create_category() {
		$error = true;

		conwr_api_write_log("Started function: " . __FUNCTION__ . ". -----------------");

		$api_method = 'CreateCategory';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error' => "Request token is not valid.");
		}

		if (!isset($api_response->Data)) {
			conwr_api_write_log("Parameter Data is empty. Function: " . __FUNCTION__);
			return array('Status' => "Failed", 'Error' => "Parameter Data is empty.");
		}

		$post_data = $api_response->Data;

		if (isset($post_data)) {
			if (!function_exists("wp_create_category")) {
				require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-admin/includes/taxonomy.php');
			}

			$category_id = wp_create_category($post_data->CategoryName);

			if (isset($category_id) && $category_id > 0) {
				$response = array(
					'Status' => "Successful",
					'ID'   	 => $category_id,
					'Name'   => $post_data->CategoryName,
					);

				$error = false;
			}
		}

		if ($error) {
			$response = array('Status' => "Failed", 'Error' => "Category " . $post_data->CategoryName . " could not be created.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_create_author")) {
	function conwr_restapi_create_author() {
		$api_method = 'CreateAuthor';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		if (!isset($api_response->Data)) {
			conwr_api_write_log("Parameter Data is empty. Function: " . __FUNCTION__);
			return array('Status' => "Failed", 'Error' => "Parameter Data is empty.");
		}

		$post_data = $api_response->Data;

		if (isset($post_data)) {
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

				if (!is_wp_error($user_id)) {
					$user = get_user_by('id', $user_id);

					return array(
						'Status' => "Successful",
						'ID'   	 => $user_id,
						'Username'   => $user->user_login,
						'Name'   => $user->user_login . " (" . $user->user_firstname . ' ' . $user->user_lastname . ")",
					);
				}
				else {
					return array('Status' => "Failed", 'Error' => 'User ' . $post_data->FirstName . " " . $post_data->LastName . ' was not created. Reason: ' . $user_id->get_error_message());					
				}
			}
			else {
				return array('Status' => "Failed", 'Error'  => "Input data are not valid.");
			}
		}

		return array('Status' => "Failed", 'Error'  => "Request data are not valid.");
	}
}

if (!function_exists("conwr_restapi_get_plugins")) {
	function conwr_restapi_get_plugins() {
		$api_method = 'GetPlugins';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
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
					'Version'   => $plugin_value['Version'],
				);
			}

			$response = array('Status' => "Successful", 'Plugins' => $plugins);
		}
		else {
			$response = array('Status' => "Failed", 'Error' => "Plugins could not be retrieved.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_get_current_theme")) {
	function conwr_restapi_get_current_theme() {
		$api_method = 'GetCurrentTheme';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}

		$theme = array();
		$wp_theme = wp_get_theme();

		if (isset($wp_theme)) {
			$theme[] = array(
				'Name' => $wp_theme->get('Name'),
				'Version'   => $wp_theme->get('Version'),
				);

			$response = array('Status' => "Successful", 'Theme' => $theme);
		}
		else {
			$response = array('Status' => "Failed", 'Error' => "Themes could not be retrieved.");
		}

		return $response;
	}
}

if (!function_exists("conwr_restapi_check_plugin")) {
	function conwr_restapi_check_plugin() {
		if (!function_exists('get_plugin_data')){
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		
		if (is_plugin_active('content-writer/content-writer.php')) {
			return array('Status' => "Successful");
		}

		return array('Status' => "Failed");
	}
}

if (!function_exists("conwr_restapi_get_plugin_update_status")) {
	function conwr_restapi_get_plugin_update_status() {		
		$installed_version = conwr_get_plugin_current_version(ABSPATH . 'wp-content/plugins/content-writer/content-writer.php');
        $latest_version = conwr_get_plugin_latest_version(); //latest version from WordPress repository

        if ($installed_version != 0 && $latest_version != 0) {
            if (version_compare($latest_version, $installed_version, '>')) {
				return array('Status' => "Successful", 'NeedsUpdate' => "Yes", 'CurrentVersion' => $installed_version);
            }
            else if (version_compare($latest_version, $installed_version, '<=')) {
				return array('Status' => "Successful", 'NeedsUpdate' => "No", 'CurrentVersion' => $installed_version);
            }
		}
		
		return array('Status' => "Failed", 'Error' => "Could not get plugin update status.");
	}
}

if (!function_exists("conwr_restapi_is_plugin_verified")) {
	function conwr_restapi_is_plugin_verified() {
		$api_method = 'IsPluginVerified';
		$api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);
		$plugin_verified = get_option('conwr_verified');

		if (!$api_response) {
			return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
		}
        if (isset($plugin_verified) && !empty($plugin_verified)) {
            if ($plugin_verified == true) {
                return array('Status' => "Successful", 'Version' => CONWR_VERSION);
            }
        }
		else if (isset($_GET["Data"])) {
			$json_request = stripcslashes($_GET["Data"]);
			$post_data = json_decode(urldecode($json_request));

			if (isset($post_data)) {
				$email = $post_data->Email;
				$api_key = $post_data->APIKey;

				$sc_email = get_option("conwr_email", false);
				$sc_api_key = get_option("conwr_api_key", false);

				if ($email == $sc_email && $api_key == $sc_api_key) {
					$response = array('Status' => "Successful");
				}
				else {
					conwr_api_write_log("Plugin could not be verified. Stored email ($sc_email) does not match passed email: ($email), or stored API key ($sc_api_key) does not match the passed API key ($api_key)");
					return array('Status' => "Failed", 'Error' => "Plugin is not verified.");
				}
			}
			else {
				conwr_api_write_log("Post data are not valid. Function: " . __FUNCTION__);
				return array('Status' => "Failed", 'Error' => "Post data are not valid.");
			}
		}

	}
}

if (!function_exists("conwr_restapi_verify_plugin_from_app")) {
	function conwr_restapi_verify_plugin_from_app() {
		//OLD CODE

		// if (!isset($api_response->Data)) {
		// 	conwr_api_write_log("Parameter Data is empty. Function: " . __FUNCTION__);
		// 	return array('Status' => "Failed", 'Error' => "Parameter Data is empty.");
		// }

		// $post_data = $api_response->Data;

		// if (isset($post_data)) {
		// 	$email = $post_data->Email;
		// 	$api_key = $post_data->APIKey;

		// 	if (!empty($email) && !empty($api_key)) {
		// 		update_option("conwr_email", $email);
		// 		update_option("conwr_api_key", $api_key);

		// 		$response = array('Status' => "Successful");

		// 		$error = false;
		// 	}
		// 	else {
		// 		return array('Status' => "Failed", 'Error' => "Input data are not valid.");
		// 	}
		// }
		// else {
		// 	return array('Status' => "Failed", 'Error' => "Post data are not valid.");
		// }
        update_option('conwr_verified', true);


		// return $response;
		return array('Status' => "Successful", 'Version' => CONWR_VERSION);
	}
}

if (!function_exists("conwr_restapi_update_plugin_from_app")) {
    function conwr_restapi_update_plugin_from_app() {
        // TODO: Add after method approved
        $api_method = 'UpdatePlugin';
        $api_response = conwr_restapi_validate_token($api_method, __FUNCTION__);

        if (!$api_response) {
            return array('Status' => "Failed", 'Error'  => "Request token is not valid.");
        }

        $plugin = plugin_basename( sanitize_text_field( wp_unslash( 'content-writer/content-writer.php' ) ) );

        $status = array(
            'update'     => 'plugin',
            'slug'       => sanitize_key( wp_unslash( 'content-writer') ),
            'oldVersion' => '',
            'newVersion' => '',
        );

        if ( 0 !== validate_file( $plugin ) ) {
            $status['errorMessage'] = __( 'Sorry, you are not allowed to update plugins for this site.' );
            wp_send_json_error( $status );
        }


        include_once ABSPATH . '/wp-admin/includes/file.php' ;
        include_once ABSPATH . '/wp-admin/includes/plugin.php' ;

        $plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
        $status['plugin']     = $plugin;
        $status['pluginName'] = $plugin_data['Name'];

        if ( $plugin_data['Version'] ) {
            /* translators: %s: Plugin version. */
            $status['oldVersion'] = sprintf( __( '%s' ), $plugin_data['Version'] );
        }

        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        wp_update_plugins();

        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $result   = $upgrader->bulk_upgrade( array( $plugin ) );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $status['debug'] = $skin->get_upgrade_messages();
        }

        if ( is_wp_error( $skin->result ) ) {
            $status['errorCode']    = $skin->result->get_error_code();
            $status['errorMessage'] = $skin->result->get_error_message();
            wp_send_json_error( $status );
        } elseif ( $skin->get_errors()->has_errors() ) {
            $status['errorMessage'] = $skin->get_error_messages();
            wp_send_json_error( $status );
        } elseif ( is_array( $result ) && ! empty( $result[ $plugin ] ) ) {

            /*
             * Plugin is already at the latest version.
             *
             * This may also be the return value if the `update_plugins` site transient is empty,
             * e.g. when you update two plugins in quick succession before the transient repopulates.
             *
             * Preferably something can be done to ensure `update_plugins` isn't empty.
             * For now, surface some sort of error here.
             */
            if ( true === $result[ $plugin ] ) {
                $status['errorMessage'] = $upgrader->strings['up_to_date'];
                wp_send_json_error( $status );
            }

            $plugin_data = get_plugins( '/' . $result[ $plugin ]['destination_name'] );
            $plugin_data = reset( $plugin_data );

            if ( $plugin_data['Version'] ) {
                /* translators: %s: Plugin version. */
                $status['newVersion'] = sprintf( __( '%s' ), $plugin_data['Version'] );
            }

            wp_send_json_success( $status );
        } elseif ( false === $result ) {
            global $wp_filesystem;

            $status['errorCode']    = 'unable_to_connect_to_filesystem';
            $status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

            // Pass through the error from WP_Filesystem if one was raised.
            if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
                $status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
            }

            wp_send_json_error( $status );
        }

        // An unhandled error occurred.
        $status['errorMessage'] = __( 'Plugin update failed.' );
        wp_send_json_error( $status );

        // return $response;
        return array('Status' => "Successful", 'Version' => CONWR_VERSION);
    }
}

add_filter('posts_results', 'conwr_restapi_preview_scheduled', null, 2);
if (!function_exists("conwr_restapi_preview_scheduled")) {

	if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
		function conwr_restapi_preview_scheduled($posts, $query) {
			if (sizeof($posts) != 1) 
				return $posts;

			if (($posts[0]->post_status == "future" || $posts[0]->post_status == "draft") && isset( $_GET['key']) && $_GET['key'] == 'steadycontent') {
				$query->_my_future_stash = $posts;

				add_filter('the_posts', 'conwr_restapi_inject_scheduled', null, 2);
			}
			else {
				return $posts;
			}
		}
	}
	else {
		function conwr_restapi_preview_scheduled($posts, &$query) {
			if (sizeof($posts) != 1) 
				return $posts;

			if (($posts[0]->post_status == "future" || $posts[0]->post_status == "draft") && isset( $_GET['key']) && $_GET['key'] == 'steadycontent') {
				$query->_my_future_stash = $posts;

				add_filter('the_posts', 'conwr_restapi_inject_scheduled', null, 2);
			}
			else {
				return $posts;
			}
		}
	}
	
}

if (!function_exists("conwr_restapi_inject_scheduled")) {
	if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
		function conwr_restapi_inject_scheduled($posts, $query) {
		    remove_filter('the_posts', 'conwr_restapi_inject_scheduled', null, 2);
		    return $query->_my_future_stash;
		}
	}
	else {
		function conwr_restapi_inject_scheduled($posts, &$query) {
		    remove_filter('the_posts', 'conwr_restapi_inject_scheduled', null, 2);
		    return $query->_my_future_stash;
		}
	}
}
?>