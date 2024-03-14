<?php 
// check keyword [needs workaround]
class directorypress_listing extends directorypress_post{
	public $post;
	public $directorytype;
	public $package;
	public $expiration_date;
	public $order_date;
	public $listing_created = false;
	public $status; // active, expired, unpaid, stopped
	public $categories = array();
	public $locations = array();
	public $fields = array();
	public $map_zoom;
	public $logo_image;
	public $cover_image;
	public $company_logo;
	public $images = array();
	public $videos = array();
	public $map;
	public $contact_email;
	public $is_widget = 0;
	public $listing_view = 'grid';

	public function __construct($package_id = null) {
		if ($package_id) {
			// New listing
			$this->set_package_by_id($package_id);
		}
		
	}
	
	// init existed listing
	public function directorypress_init_lpost_listing($post) {
		if (!$post) {
			return false;
		}
		
		if ($this->setPost($post)) {
			if ($this->post->post_type == DIRECTORYPRESS_POST_TYPE) {
				if ($this->set_package_by_post_id()) {
					$this->set_meta_information();
					$this->set_locations();
					$this->set_directorypress_fields();
					$this->set_map_zoom();
					$this->set_media();
					
					apply_filters('directorypress_listing_initializing', $this);
		
					return true;
				}
			}
		}
	}

	public function set_package_by_id($package_id) {
		global $directorypress_object;

		$packages = $directorypress_object->packages;
		if ($package = $packages->get_package_by_id($package_id)) {
			$this->package = $package;
		}
	}
	
	public function set_meta_information() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (!$this->package->package_no_expiry)
			$this->expiration_date = get_post_meta($this->post->ID, '_expiration_date', true);

		$this->order_date = get_post_meta($this->post->ID, '_order_date', true);

		$this->status = get_post_meta($this->post->ID, '_listing_status', true);

		$this->listing_created = get_post_meta($this->post->ID, '_listing_created', true);
		
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages' && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_custom_contact_email']){
			$this->contact_email = get_post_meta($this->post->ID, '_contact_email', true);
		}
		
		$this->contact_email = apply_filters('directorypress_listing_contact_email', $this->contact_email);
		
		return $this->expiration_date;
	}

	public function set_package_by_post_id($post_id = null) {
		global $directorypress_object, $wpdb;

		if (!$post_id) {
			$post_id = $this->post->ID;
		}
		// needs workaround
		if (($directory_id = get_post_meta($post_id, '_directory_id', true)) && ($directorytype = $directorypress_object->directorytypes->directory_by_id($directory_id))) {
			$this->directorytype = $directorytype;
		} else {
			$this->directorytype = $directorypress_object->directorytypes->directorypress_get_base_directorytype();
		}

		if ($package_id = $wpdb->get_var("SELECT package_id FROM {$wpdb->directorypress_packages_relation} WHERE post_id=" . $post_id)) {
			$this->package = $directorypress_object->packages->get_package_by_id($package_id);
		}
		if (!$this->package) {
			$this->package = $directorypress_object->packages->get_default_package();
		}

		return $this->package;
	}
	public function set_locations() {
		$listing = $this;
		global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_locations_relation} WHERE post_id=".$listing->post->ID, ARRAY_A);
		
		foreach ($results AS $row) {
			if ($row['location_id'] || $row['map_coords_1'] != '0.000000' || $row['map_coords_2'] != '0.000000' || $row['address_line_1'] || $row['zip_or_postal_index']) {
				$location = new directorypress_location($listing->post->ID);
				$location_settings = array(
						'id' => $row['id'],
						'selected_location' => $row['location_id'],
						'address_line_1' => $row['address_line_1'],
						'address_line_2' => $row['address_line_2'],
						'zip_or_postal_index' => $row['zip_or_postal_index'],
						'additional_info' => $row['additional_info'],
				);
				if (directorypress_has_map()){
					$location_settings['manual_coords'] = directorypress_get_input_value($row, 'manual_coords');
					$location_settings['map_coords_1'] = directorypress_get_input_value($row, 'map_coords_1');
					$location_settings['map_coords_2'] = directorypress_get_input_value($row, 'map_coords_2');
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_markers_type'] == 'images') {
						
							$location_settings['map_icon_manually_selected'] = false;
							if ($categories = wp_get_object_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('orderby' => 'name'))) {
								$images = get_option('directorypress_categories_marker_images');
								$image_found = false;
								foreach ($categories AS $category_obj) {
									
									$location_settings['map_icon_file'] = get_listing_category_icon_url_for_map($category_obj->term_id);
									$image_found = true;
									
									if ($image_found)
										break;
									if ($parent_categories = directorypress_get_term_parents_ids($category_obj->term_id, DIRECTORYPRESS_CATEGORIES_TAX)) {
										foreach ($parent_categories AS $parent_category_id) {
											
											$location_settings['map_icon_file'] = get_listing_category_icon_url_for_map($category_obj->term_id);
											$image_found = true;
											
											if ($image_found) {
												break;
												break;
											}
										}
									}
								}
							}
						
					} else {
							$location_settings['map_icon_manually_selected'] = false;
							if ($categories = wp_get_object_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('orderby' => 'name'))) {
								
								$icon_found = false;
								$color_found = false;
								foreach ($categories AS $category_obj) {
									
									$location_settings['map_icon_file'] = get_listing_category_font_marker_icon($category_obj->term_id);
									$icon_found = true;
									
									if (!$color_found && !empty(get_listing_category_color($category_obj->term_id))) {
										$location_settings['map_icon_color'] = get_listing_category_marker_color($category_obj->term_id);
										$color_found = true;
									}
									if ($icon_found && $color_found)
										break;
									if ($parent_categories = directorypress_get_term_parents_ids($category_obj->term_id, DIRECTORYPRESS_CATEGORIES_TAX)) {
										foreach ($parent_categories AS $parent_category_id) {
											
											$location_settings['map_icon_file'] = get_listing_category_font_marker_icon($parent_category_id);
											$icon_found = true;
											
											$location_settings['map_icon_color'] = get_listing_category_marker_color($parent_category_id);
											$color_found = true;
											
											if ($icon_found && $color_found) {
												break;
												break;
											}
										}
									}
									if ($icon_found || $color_found)
										break;
								}
							}
						
					}
				}
				
				$location_settings = apply_filters('directorypress_listing_locations', $location_settings, $listing);
				
				$location->create_location_from_array($location_settings);
				
				$listing->locations[] = $location;
			}
		}
	}
	
	public function set_map_zoom() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (directorypress_has_map()){
			if (!$this->map_zoom = get_post_meta($this->post->ID, '_map_zoom', true))
				$this->map_zoom = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_map_zoom'];
		}
	}

	public function set_directorypress_fields() {
		global $directorypress_object;

		$post_categories_ids = wp_get_post_terms($this->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('fields' => 'ids'));
		$this->fields = $directorypress_object->fields->load_field_values($this->post->ID, $post_categories_ids, $this->package->id);
		
		$this->fields = apply_filters('directorypress_listing_fields', $this->fields, $this);
	}
	
	public function set_media() {
		if ($this->package->images_allowed) {
			if ($images = get_post_meta($this->post->ID, '_attached_image')) {
				foreach ($images AS $image_id) {
					// adapted for WPML
					global $sitepress;
					if (function_exists('wpml_object_id_filter') && $sitepress)
						$image_id = apply_filters('wpml_object_id', $image_id, 'attachment', true);

					$this->images[$image_id] = get_post($image_id, ARRAY_A);
				}

				if (($logo_id = (int)get_post_meta($this->post->ID, '_attached_image_as_logo', true)) && in_array($logo_id, array_keys($this->images))) {
					$this->logo_image = $logo_id;
				} else {
					$images_keys = array_keys($this->images);
					$this->logo_image = array_shift($images_keys);
				}
				
				// Logo image always first
				unset($this->images[$this->logo_image]);
				$this->images = array($this->logo_image => get_post($this->logo_image, ARRAY_A)) + $this->images;
			} else {
				$this->images = array();
			}
		}
		
		$this->images = apply_filters('directorypress_listing_images', $this->images, $this);
		
		if ($this->package->videos_allowed) {
			if ($videos = get_post_meta($this->post->ID, '_attached_video_id')) {
				foreach ($videos AS $key=>$video) {
					$this->videos[] = array('id' => $video);
				}
			}
		}
		
		$this->videos = apply_filters('directorypress_listing_videos', $this->videos, $this);
	}
	
	public function get_directorypress_fields($field_id) {
		if (isset($this->fields[$field_id])) {
			return $this->fields[$field_id];
		}
	}

	public function display_content_field($field_id) {
		if (isset($this->fields[$field_id]) && $this->fields[$field_id]->is_field_not_empty($this)){
			$this->fields[$field_id]->display_output($this);
		}
	}
	public function hours_field_status($field_id, $time_string) {
		if (isset($this->fields[$field_id])){
			$this->fields[$field_id]->status($this, $time_string);
		}
	}
	public function display_price_field_range($field_id) {
		if (isset($this->fields[$field_id])){
			$this->fields[$field_id]->display_outputTooltip($this);
		}
	}
	public function display_summary_field() {
		$summary = new directorypress_field_excerpt();
		$summary->icon_image = false;
		$summary->is_hide_name = true;
		$summary->display_output($this);
	}
	
	public function display($public_handler, $is_single = false, $return = false) {
		$template = 'partials/listing/listing.php';
		
		$template = apply_filters('directorypress_listing_display_template', $template, $is_single, $this);
		
		return directorypress_display_template($template, array('public_handler' => $public_handler, 'listing' => $this, 'is_single' => $is_single), $return);
	}
	
	public function display_content_fields($is_single = true) {
		global $directorypress_object;
		
		$fields = apply_filters('directorypress_listing_fields_pre_output', $this->fields, $this);
		
		$fields_on_single = array();
		foreach ($fields AS $field) {
			if ($field->is_field_not_empty($this) && ((!$is_single && ($field->on_exerpt_page || $field->on_exerpt_page_list)) || ($is_single && $field->on_listing_page))){
				if ($is_single && $field->on_listing_page){
					$fields_on_single[] = $field;
				}else{
					$field->display_output($this);
				}
			}
		}

		if ($is_single && $fields_on_single) {
			$fields_by_groups = $directorypress_object->fields->order_content_fields_by_groups($fields_on_single);
			foreach ($fields_by_groups AS $item) {
				if (is_a($item, 'directorypress_field'))
					$item->display_output($this, $is_single);
			}
		}
	}
	
	public function display_content_fields_ingroup($is_single = true) {
		global $directorypress_object;

		$fields_on_single = array();
		$fields_on_single = array();
		foreach ($this->fields AS $field) {
			if (
				$field->is_field_not_empty($this) &&
				((!$is_single && ($field->on_exerpt_page || $field->on_exerpt_page_list)) || ($is_single && $field->on_listing_page))
			)
				if ($is_single){
					$fields_on_single[] = $field;
				}
		}
		if ($is_single && $fields_on_single) {
			$fields_by_groups = $directorypress_object->fields->order_content_fields_by_groups($fields_on_single);
			foreach ($fields_by_groups AS $item) {
				if ((is_a($item, 'directorypress_fields_group') && !$item->on_tab))
					$item->display_output($this);
			}
		}
	}
	
	public function get_fields_groups_in_tabs() {
		global $directorypress_object;

		$fields_groups = array();
		foreach ($this->fields AS $field) {
			if (
				$field->on_listing_page &&
				$field->group_id &&
				$field->is_field_not_empty($this) &&
				($fields_group = $directorypress_object->fields->get_fields_group_by_id($field->group_id)) &&
				$fields_group->on_tab &&
				!in_array($field->group_id, array_keys($fields_groups))
			) {
				$fields_group->set_directorypress_fields($this->fields);
				if ($fields_group->fields_array)
					$fields_groups[$field->group_id] = $fields_group;
			}
		}
		
		$fields_groups = apply_filters('directorypress_listing_fields_groups_on_tabs', $fields_groups, $this);

		return $fields_groups;
	}

	public function is_map() {
		$is_map = false;

		foreach ($this->locations AS $location) {
			if ($location->map_coords_1 != '0.000000' || $location->map_coords_2 != '0.000000') {
				$is_map = true;
			}
		}
		
		$is_map = apply_filters('directorypress_listing_is_map', $is_map, $this);

		return $is_map;
	}
	
	public function display_map($map_id = null, $show_directions = true, $static_image = false, $enable_radius_circle = false, $enable_clusters = false, $show_summary_button = false, $show_readmore_button = false) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$this->map = new directorypress_maps(array(
				'search_on_map' => 0,
				'search_on_map_open' => 0,
				'geolocation' => 0,
				'start_zoom' => 0,
		));
		$this->map->set_unique_id($map_id);
		$this->map->collect_locations($this);
		$this->map->display($show_directions, $static_image, $enable_radius_circle, $enable_clusters, $show_summary_button, $show_readmore_button, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_map_height'], false, false, directorypress_map_name_selected(), false, false, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_full_screen'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_wheel_zoom'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_dragging_touchscreens'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_center_map_onclick']);
	}

	public function process_bumpup($invoke_hooks = true) {
		if ($this->package->can_be_bumpup) {
			$continue = true;
			$continue_invoke_hooks = true;
			if ($invoke_hooks)
				$continue = apply_filters('directorypress_listing_bumpup', $continue, $this, array(&$continue_invoke_hooks));

			if ($continue) {
				$listings_ids = array($this->post->ID);

				// adapted for WPML
				global $sitepress;
				if (function_exists('wpml_object_id_filter') && $sitepress) {
					$trid = $sitepress->get_element_trid($this->post->ID, 'post_' . DIRECTORYPRESS_POST_TYPE);
					$translations = $sitepress->get_element_translations($trid);
					foreach ($translations AS $lang=>$translation)
						$listings_ids[] = $translation->element_id;
				} else
					$listings_ids[] = $this->post->ID;
				
				$listings_ids = array_unique($listings_ids);

				foreach ($listings_ids AS $listing_id)
					update_post_meta($listing_id, '_order_date', time());

				return true;
			}
		}
	}

	public function process_activation($invoke_hooks = true, $activate_package = true) {
		$continue = true;
		$continue_invoke_hooks = true;
		
		if ($invoke_hooks) {
			$continue = apply_filters('directorypress_listing_renew', $continue, $this, array(&$continue_invoke_hooks));
		}

		if ($continue) {
			$listings = array($this);

			// adapted for WPML
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress) {
				$trid = $sitepress->get_element_trid($this->post->ID, 'post_' . DIRECTORYPRESS_POST_TYPE);
				$translations = $sitepress->get_element_translations($trid, 'post_' . DIRECTORYPRESS_POST_TYPE, false, true);
				foreach ($translations AS $lang=>$translation) {
					$listing = directorypress_get_listing($translation->element_id);
					$listings[] = $listing;
				}
			} else {
				$listings[] = $this;
			}
			
			$listings = array_unique($listings, SORT_REGULAR);

			foreach ($listings AS $listing) {
				if (!$listing->package->package_no_expiry) {
					
					if ($listing->status == 'active') {
						$time = $listing->expiration_date;
					} else { 
						$time = current_time('timestamp');
					}
					$expiration_date = directorypress_expiry_date($time, $listing->package);
					update_post_meta($listing->post->ID, '_expiration_date', $expiration_date);
				}
				update_post_meta($listing->post->ID, '_order_date', time());
				update_post_meta($listing->post->ID, '_listing_status', 'active');
				$post_status = apply_filters('directorypress_post_status_on_activation', 'publish', $listing);
				wp_update_post(array('ID' => $listing->post->ID, 'post_status' => $post_status));

				delete_post_meta($listing->post->ID, '_expiration_notification_sent');
				delete_post_meta($listing->post->ID, '_preexpiration_notification_sent');

				if ($activate_package) {
					do_action('directorypress_listing_package_process_activation', $listing);
				}
					
				do_action('directorypress_listing_process_activation', $listing, $invoke_hooks);
			}
			return true;
		}
	}
	
	public function save_listing_expiry($date_array) {
		$new_tmstmp = $date_array['expiration_date_tmstmp'] + $date_array['expiration_date_hour']*3600 + $date_array['expiration_date_minute']*60;
		
		$listings_ids = array($this->post->ID);
		
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$trid = $sitepress->get_element_trid($this->post->ID, 'post_' . DIRECTORYPRESS_POST_TYPE);
			$translations = $sitepress->get_element_translations($trid);
			foreach ($translations AS $lang=>$translation)
				$listings_ids[] = $translation->element_id;
		} else
			$listings_ids[] = $this->post->ID;
		
		$listings_ids = array_unique($listings_ids);

		$updated = false;
		foreach ($listings_ids AS $listing_id)
			if ($new_tmstmp != get_post_meta($listing_id, '_expiration_date', true)) {
				$new_tmstmp = apply_filters('directorypress_listing_save_expiry', $new_tmstmp, $date_array, $this);
				
				update_post_meta($listing_id, '_expiration_date', $new_tmstmp);
				$updated = true;
			}

		return $updated;
	}
	
	public function change_listing_package($new_package_id, $invoke_hooks = true) {
		global $directorypress_object, $wpdb;
		
		if ((isset($directorypress_object->packages->packages_array[$new_package_id]) && !$this->package->upgrade_meta[$new_package_id]['disabled']) || (current_user_can('editor') || current_user_can('manage_options'))) {
			$listings = array($this);
			
			// adapted for WPML
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress) {
				$trid = $sitepress->get_element_trid($this->post->ID, 'post_' . DIRECTORYPRESS_POST_TYPE);
				$translations = $sitepress->get_element_translations($trid);
				foreach ($translations AS $lang=>$translation) {
					$listing = new directorypress_listing();
					$listing->directorypress_init_lpost_listing($translation->element_id);
					$listings[] = $listing;
				}
			} else{
				$listings[] = $this;
			}
			
			$listings = array_unique($listings, SORT_REGULAR);

			foreach ($listings AS $listing) {
				update_post_meta($listing->post->ID, '_old_package_id', $listing->package->id);
				update_post_meta($listing->post->ID, '_new_package_id', $new_package_id);
			}

			$continue = true;
			$continue_invoke_hooks = true;
			if ($invoke_hooks) {
				$continue = apply_filters('directorypress_listing_upgrade', $continue, $this, array(&$continue_invoke_hooks));
			}
			
			if ($continue) {
				foreach ($listings AS $listing) {
					//check if listing id exist in relations table
					if(!$wpdb->query("SELECT id FROM {$wpdb->directorypress_packages_relation} WHERE post_id=" . $listing->post->ID)){
						
						$wpdb->query("INSERT INTO {$wpdb->directorypress_packages_relation} (`post_id`, `package_id`) VALUES (" . $listing->post->ID . ", " . $new_package_id . ");");
					}
					
					if ($wpdb->query("UPDATE {$wpdb->directorypress_packages_relation} SET package_id=" . $new_package_id . "  WHERE post_id=" . $listing->post->ID)) {
						if ($this->package->upgrade_meta[$new_package_id]['raiseup']) {
							update_post_meta($listing->post->ID, '_order_date', time());
						}
	
						$listing->set_package_by_post_id($listing->post->ID);

						//  If new package has an option of limited active period - expiration date of listing will be recalculated automatically
						if (!$listing->package->package_no_expiry) {
							$expiration_date = directorypress_expiry_date(current_time('timestamp'), $listing->package);
							update_post_meta($listing->post->ID, '_expiration_date', $expiration_date);
						}
						
						if ($listing->status == 'expired' || $listing->status == 'unpaid') {
							update_post_meta($listing->post->ID, '_listing_status', 'active');
							delete_post_meta($listing->post->ID, '_expiration_notification_sent');
							delete_post_meta($listing->post->ID, '_preexpiration_notification_sent');
							
							do_action('directorypress_listing_process_activation_on_package_change', $listing, true);
						}
					}
				}
				return true;
			}
		}
	}

	public function init_listing_on_map($post) {
		$this->post = $post;
	
		if ($this->set_package_by_post_id()) {
			$this->set_locations();
			$this->set_map_zoom();
			$this->set_listing_thumbnail();
			
			apply_filters('directorypress_listing_map_intializing', $this);
		}
		return true;
	}

	public function init_listing_on_map_with_ajax($post) {
		$this->post = $post;
	
		if ($this->set_package_by_post_id())
			$this->set_locations();
			$this->set_listing_thumbnail();
		
		apply_filters('directorypress_listing_map_intializing', $this);

		return true;
	}

	public function set_listing_thumbnail() {
		if ($this->package->images_allowed) {
			if ($logo_id = (int)get_post_meta($this->post->ID, '_attached_image_as_logo', true)){
				$this->logo_image = $logo_id;
			}else {
				$images = get_post_meta($this->post->ID, '_attached_image');
				$this->logo_image = array_shift($images);
			}
		}
		
		$this->logo_image = apply_filters('directorypress_listing_logo_image', $this->logo_image, $this);
	}
	
	public function set_cover_image() {
		
		if ($cover_id = (int)get_post_meta($this->post->ID, '_attached_image_cover_image', true)){
				$this->cover_image = $cover_id;
		}
		$this->cover_image = apply_filters('directorypress_listing_cover_image', $this->cover_image, $this);
	}
	
	public function set_company_logo() {
		
		if ($company_logo_id = (int)get_post_meta($this->post->ID, '_attached_image_clogo', true)){
				$this->company_logo = $company_logo_id;
		}
		$this->company_logo = apply_filters('directorypress_listing_company_logo', $this->company_logo, $this);
	}

	public function set_directorypress_fields_for_map($map_fields, $location) {
		$post_categories_ids = wp_get_post_terms($this->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('fields' => 'ids'));
		$fields_output = array(
			$location->display_info_field_on_map()
		);
		
		foreach($map_fields AS $field_slug=>$field) {
			// is it native content field
			if (is_a($field, 'directorypress_field')) {
				if (
					(!$field->is_categories() || $field->categories === array() || (is_array($field->categories) && !is_wp_error($post_categories_ids) && array_intersect($field->categories, $post_categories_ids))) &&
					($field->is_core_field || !$this->package->fields || in_array($field->id, $this->package->fields))
				) {
					$field->load_field_value($this->post->ID);
					$output = $field->disaply_output_on_map($location, $this);
					$fields_output[] = apply_filters('directorypress_map_field_output', $output, $field, $location, $this);
				} else 
					$fields_output[] = null;
			} else
				$fields_output[] = apply_filters('directorypress_map_info_window_fields_values', $field, $field_slug, $this);
		}

		return apply_filters('directorypress_map_info_window_content_output', $fields_output, $this);
	}
	
	public function display_mapSidebarContentFields($location) {
		$info_output = '';
		$fields_output = '';
		$addresses_output = '';
		
		if ($location->display_info_field_on_map()) {
			$info_output = '<div class="directorypress-map-listing-field">
				<span class="directorypress-map-listing-field-icon fa fa-info-circle"></span> ' . $location->display_info_field_on_map() . '
			</div>';
		}
	
		foreach ($this->fields AS $field) {
			if (
				$field->is_field_not_empty($this) &&
				$field->on_map
			) {
				if ($field->type != 'address') {
					$fields_output .= '<div class="directorypress-map-listing-field directorypress-map-listing-field-' . $field->type . '">
						<span class="directorypress-map-listing-field-icon fa ' . ((is_a($field, 'directorypress_field') && $field->icon_image) ? $field->icon_image : '') . '"></span>
							' . $field->disaply_output_on_map($location, $this) . '
						</div>';
				} else {
					$addresses_output = '<div class="directorypress-map-listing-field directorypress-map-listing-field-' . $field->type . '">
						<span class="directorypress-map-listing-field-icon fa ' . ((is_a($field, 'directorypress_field') && $field->icon_image) ? $field->icon_image : '') . '"></span>';
						$addresses_output .= '<address class="directorypress-location">';
							if ($location->map_coords_1 && $location->map_coords_2) {
								$addresses_output .= '<span class="directorypress-show-on-map" data-location-id="' . $location->id . '">';
							}
							$addresses_output .= $location->get_full_address();
							if ($location->map_coords_1 && $location->map_coords_2) {
								$addresses_output .= '</span>';
							}
						$addresses_output .= '</address>';
					$addresses_output .= '</div>';
				}
			}
		}
		
		echo wp_kses_post($info_output);
		echo wp_kses_post($addresses_output);
		echo wp_kses_post($fields_output);
	}
	
	public function get_listing_pending_status() {
		if ($this->post->post_status == 'pending') {
			if ($this->status == 'unpaid') {
				return __('Pending payment', 'DIRECTORYPRESS');
			}
			
			$is_moderation = get_post_meta($this->post->ID, '_requires_moderation', true);
			$is_approved = get_post_meta($this->post->ID, '_listing_approved', true);
			if ($is_moderation && !$is_approved) {
				return __('Pending approval', 'DIRECTORYPRESS');
			}
			
			return __('Pending', 'DIRECTORYPRESS');
		}
	}
	
	public function bidcount() {
		global $wpdb, $post;
		$query = $wpdb->get_results('SELECT * FROM '.$wpdb->postmeta.' WHERE (meta_key = "_listing_bidpost" AND post_id = "'.$this->post->ID.'")');

		return count($query);
	}
	public function avgbid() {
		global $wpdb, $post;
		$query = $wpdb->get_results('SELECT  meta_value FROM '.$wpdb->postmeta.' WHERE (meta_key = "_listing_bidpost" AND post_id = "'.$this->post->ID.'")');
		if(count($query) >= 1){
			$avgbid = array_sum(array_column($query, 'meta_value')) / count($query);
		}else{
			$avgbid = 0;
		}
	return $avgbid;

		//return array_sum($query);

		
	}
	public function lowestbid() {
		global $wpdb, $post;

		$query = $wpdb->get_var('SELECT MIN(meta_value) FROM '.$wpdb->postmeta.' WHERE (meta_key = "_listing_bidpost" AND post_id = "'.$this->post->ID.'")');

		return $query;
	}
	public function highestbid() {
		global $wpdb, $post;

		$query = $wpdb->get_var('SELECT MAX(meta_value) FROM '.$wpdb->postmeta.' WHERE (meta_key = "_listing_bidpost" AND post_id = "'.$this->post->ID.'")');

		return $query;
	}
}

?>