<?php 

class directorypress_locations_manager {
	
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'remove_locations_metabox'));
		add_action('add_meta_boxes', array($this, 'add_locations_metabox'), 300);
		
		add_action('wp_ajax_directorypress_tax_dropdowns_hook', 'directorypress_tax_dropdowns_updateterms');
		add_action('wp_ajax_nopriv_directorypress_tax_dropdowns_hook', 'directorypress_tax_dropdowns_updateterms');

		add_action('wp_ajax_directorypress_add_location_in_metabox', array($this, 'add_location_in_metabox'));
		add_action('wp_ajax_nopriv_directorypress_add_location_in_metabox', array($this, 'add_location_in_metabox'));


		if (directorypress_is_listing_admin_edit_page()) {
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_styles'));
		}

		add_filter('manage_' . DIRECTORYPRESS_LOCATIONS_TAX . '_custom_column', array($this, 'taxonomy_rows'), 15, 3);
		add_filter('manage_edit-' . DIRECTORYPRESS_LOCATIONS_TAX . '_columns',  array($this, 'taxonomy_columns'));

	}
	
	// remove native locations taxonomy metabox from sidebar
	public function remove_locations_metabox() {
		remove_meta_box(DIRECTORYPRESS_LOCATIONS_TAX . 'div', DIRECTORYPRESS_POST_TYPE, 'side');
	}
	
	public function add_locations_metabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE && ($package = directorypress_pull_current_listing_admin()->package) && $package->location_number_allowed > 0) {
			add_meta_box('directorypress_locations',
					__('Listing locations', 'DIRECTORYPRESS'),
					array($this, 'listing_locations_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high');
		}
	}

	public function listing_locations_metabox($post) {
		global $directorypress_object;
			
		$listing = directorypress_pull_current_listing_admin();
		$locations_depths = $directorypress_object->locations_depths;
		directorypress_display_template('partials/locations/metabox.php', array('listing' => $listing, 'locations_depths' => $locations_depths));
	}
	
	public function add_location_in_metabox() {
		global $directorypress_object;
			
		if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
			$listing = new directorypress_listing();
			$listing->directorypress_init_lpost_listing($_POST['post_id']);
	
			$locations_depths = $directorypress_object->locations_depths;
			directorypress_display_template('partials/locations/child.php', array('listing' => $listing, 'location' => new directorypress_location, 'locations_depths' => $locations_depths, 'delete_location_link' => true));
		}
		die();
	}

	public function validate_locations($package, &$errors) {
		global $directorypress_object;

		$validation = new directorypress_form_validation();
		$validation->set_rules('directorypress_location[]', __('Ad Location', 'DIRECTORYPRESS'), 'is_natural');
		$validation->set_rules('selected_tax[]', __('Selected Location', 'DIRECTORYPRESS'), 'is_natural');
		$validation->set_rules('address_line_1[]', __('Address line 1', 'DIRECTORYPRESS'));
		$validation->set_rules('address_line_2[]', __('Address line 2', 'DIRECTORYPRESS'));
		$validation->set_rules('zip_or_postal_index[]', __('Zip code', 'DIRECTORYPRESS'));
		$validation->set_rules('additional_info[]', __('Additional info', 'DIRECTORYPRESS'));
		$validation->set_rules('manual_coords[]', __('Use manual coordinates', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('map_coords_1[]', __('Latitude', 'DIRECTORYPRESS'), 'numeric');
		$validation->set_rules('map_coords_2[]', __('Longitude', 'DIRECTORYPRESS'), 'numeric');
		$validation->set_rules('map_zoom', __('Map zoom', 'DIRECTORYPRESS'), 'is_natural');
	
		if (!$validation->run()) {
			$errors[] = $validation->error_array();
			//return false;
		} else {
			$passed = true;
			if ($directorypress_object->fields->get_field_by_slug('address')->is_required) {
				$address_passed = true;
				if ($validation_results = $validation->result_array()) {
					foreach ($validation_results['directorypress_location[]'] AS $key=>$value) {
						if (!$validation_results['selected_tax[]'][$key] && !$validation_results['address_line_1[]'][$key] && !$validation_results['zip_or_postal_index[]'][$key])
							$address_passed = false;
					}
				}
				if (!$address_passed) {
					$errors[] = __('Location, address or zip is required!', 'DIRECTORYPRESS');
					$passed = false;
				}
			}
				
			if (get_option('directorypress_map_markers_required')) {
				$map_passed = false;
				if ($validation_results = $validation->result_array()) {
					foreach ($validation_results['directorypress_location[]'] AS $key=>$value) {
						if ($validation_results['map_coords_1[]'][$key] || $validation_results['map_coords_2[]'][$key])
							$map_passed = true;
					}
				}
				if (!$map_passed) {
					$errors[] = __('Listing must contain at least one map marker!', 'DIRECTORYPRESS');
					$passed = false;
				}
			}

			//if ($passed)
				return $validation->result_array();
			//else 
				//return false;
		}
	}
	
	public function save_locations($package, $post_id, $validation_results) {
		global $wpdb;
	
		$this->delete_locations($post_id);
	
		if (isset($validation_results['directorypress_location[]'])) {
			// remove unauthorized locations
			$validation_results['directorypress_location[]'] = array_slice($validation_results['directorypress_location[]'], 0, $package->location_number_allowed, true);
	
			foreach ($validation_results['directorypress_location[]'] AS $key=>$value) {
				if (
					$validation_results['selected_tax[]'][$key] ||
					$validation_results['address_line_1[]'][$key] ||
					$validation_results['address_line_2[]'][$key] ||
					$validation_results['zip_or_postal_index[]'][$key] ||
					($validation_results['map_coords_1[]'][$key] || $validation_results['map_coords_2[]'][$key])
				) {
					$insert_values = array(
							'post_id' => $post_id,
							'location_id' => esc_sql($validation_results['selected_tax[]'][$key]),
							'address_line_1' => esc_sql($validation_results['address_line_1[]'][$key]),
							'address_line_2' => esc_sql($validation_results['address_line_2[]'][$key]),
							'zip_or_postal_index' => esc_sql($validation_results['zip_or_postal_index[]'][$key]),
							'additional_info' => (isset($validation_results['additional_info[]'][$key]) ? esc_sql($validation_results['additional_info[]'][$key]) : ''),
					);
					
						if (is_array($validation_results['manual_coords[]'])) {
							if (in_array($key, array_keys($validation_results['manual_coords[]'])) && $validation_results['manual_coords[]'][$key])
								$insert_values['manual_coords'] = 1;
							else
								$insert_values['manual_coords'] = 0;
						} else
							$insert_values['manual_coords'] = 0;
						$insert_values['map_coords_1'] = $validation_results['map_coords_1[]'][$key];
						$insert_values['map_coords_2'] = $validation_results['map_coords_2[]'][$key];
					
					$keys = array_keys($insert_values);

					foreach ($keys AS $key) {
						if ($key != 'post_id') {
							add_post_meta($post_id, '_'.$key, $insert_values[$key]);
						}
					}
					
					array_walk($keys, 'directorypress_wrapKeys');
					array_walk($insert_values, 'directorypress_wrapValues');
					$wpdb->query("INSERT INTO {$wpdb->directorypress_locations_relation} (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $insert_values) . ")");
				}
			}

			if ($validation_results['selected_tax[]']) {
				array_walk($validation_results['selected_tax[]'], 'directorypress_wrapIntVal');
				wp_set_object_terms($post_id, $validation_results['selected_tax[]'], DIRECTORYPRESS_LOCATIONS_TAX);
			}
	
			add_post_meta($post_id, '_map_zoom', $validation_results['map_zoom']);
		}
	}

	public function delete_locations($post_id) {
		global $wpdb;

		$wpdb->delete($wpdb->directorypress_locations_relation, array('post_id' => $post_id));
		wp_delete_object_term_relationships($post_id, DIRECTORYPRESS_LOCATIONS_TAX);
		delete_post_meta($post_id, '_location_id');
		delete_post_meta($post_id, '_address_line_1');
		delete_post_meta($post_id, '_address_line_2');
		delete_post_meta($post_id, '_zip_or_postal_index');
		delete_post_meta($post_id, '_additional_info');
		delete_post_meta($post_id, '_manual_coords');
		delete_post_meta($post_id, '_map_coords_1');
		delete_post_meta($post_id, '_map_coords_2');
		delete_post_meta($post_id, '_map_icon_file');
		delete_post_meta($post_id, '_map_zoom');
	}
	
	public function taxonomy_columns($original_columns) {
		$new_columns = $original_columns;
		array_splice($new_columns, 1);
		$new_columns['directorypress_location_id'] = __('Location ID', 'DIRECTORYPRESS');
		if (isset($original_columns['description']))
			unset($original_columns['description']);
		return array_merge($new_columns, $original_columns);
	}
	
	public function taxonomy_rows($row, $column_name, $term_id) {
		if ($column_name == 'directorypress_location_id') {
			return $row . $term_id;
		}
		return $row;
	}
	
	public function admin_enqueue_scripts_styles() {
		wp_localize_script(
				'directorypress-public',
				'directorypress_maps_function_call',
				array(
						'callback' => 'directorypress_init_backend_map_api'
				)
		);
	}
}

?>