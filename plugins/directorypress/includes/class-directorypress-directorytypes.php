<?php 

class directorypress_directorytypes {
	public $directorypress_array_of_directorytypes = array();

	public function __construct() {
		$this->directorypress_pull_directorytypes();

		add_action('init', array($this, 'setAdvanceDirectorytypesURLs'));
		add_action('directorypress_load_pages_directorytypes', array($this, 'setDirectorytypesURLs'));
	}

	public function directorypress_pull_directorytypes() {
		global $wpdb;
		$this->directorypress_array_of_directorytypes = array();

		$array = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_directorytypes}", ARRAY_A);
		foreach ($array AS $row) {
			$directorytype = new directorypress_directorytype;
			$directorytype->directorypress_create_directorytype_from_array_values($row);
			$this->directorypress_array_of_directorytypes[$row['id']] = $directorytype;
		}
		
		if (!$this->directorypress_array_of_directorytypes) {
			$directorytype = new directorypress_directorytype;
			$directorytype->directorypress_create_directorytype_from_array_values(array(
					'name' => __('Listings', 'DIRECTORYPRESS'),
					'single' => __('listing', 'DIRECTORYPRESS'),
					'plural' => __('listings', 'DIRECTORYPRESS')
			));
			$this->directorypress_array_of_directorytypes[1] = $directorytype;
		}
	}
	
	public function setAdvanceDirectorytypesURLs() {
		foreach ($this->directorypress_array_of_directorytypes AS &$directorytype) {
			$directorytype->directorypress_preset_url();
		}
	}

	public function setDirectorytypesURLs() {
		foreach ($this->directorypress_array_of_directorytypes AS &$directorytype) {
			$directorytype->directorypress_build_directorytype_url();
		}
	}
	
	public function isMultiDirectory() {
		return (count($this->directorypress_array_of_directorytypes) > 1) ? true : false;
	}

	public function directory_by_id($directory_id) {
		if (isset($this->directorypress_array_of_directorytypes[$directory_id]))
			return $this->directorypress_array_of_directorytypes[$directory_id];
	}
	
	public function directorypress_get_base_directorytype() {
		$array_keys = array_keys($this->directorypress_array_of_directorytypes);
		$first_id = array_shift($array_keys);
		return $this->directory_by_id($first_id);
	}

	public function createDirectoryFromArray($array) {
		global $wpdb, $directorypress_object;
		
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'single' => directorypress_get_input_value($array, 'single'),
				'plural' => directorypress_get_input_value($array, 'plural'),
				'listing_slug' => directorypress_get_input_value($array, 'listing_slug'),
				'category_slug' => directorypress_get_input_value($array, 'category_slug'),
				'location_slug' => directorypress_get_input_value($array, 'location_slug'),
				'tag_slug' => directorypress_get_input_value($array, 'tag_slug'),
				'categories' => serialize(directorypress_get_input_value($array, 'categories', array())),
				'locations' => serialize(directorypress_get_input_value($array, 'locations', array())),
				'packages' => serialize(directorypress_get_input_value($array, 'packages', array())),
		);
		$insert_update_args = apply_filters('directorypress_directory_create_edit_args', $insert_update_args, $array);

		if ($wpdb->insert($wpdb->directorypress_directorytypes, $insert_update_args)) {
			$new_directory_id = $wpdb->insert_id;
			
			do_action('directorypress_update_directory', $new_directory_id, $array);
			
			$this->directorypress_pull_directorytypes();
			return true;
		}
	}
	
	public function saveDirectoryFromArray($directory_id, $array) {
		global $wpdb;

		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'single' => directorypress_get_input_value($array, 'single'),
				'plural' => directorypress_get_input_value($array, 'plural'),
				'listing_slug' => directorypress_get_input_value($array, 'listing_slug'),
				'category_slug' => directorypress_get_input_value($array, 'category_slug'),
				'location_slug' => directorypress_get_input_value($array, 'location_slug'),
				'tag_slug' => directorypress_get_input_value($array, 'tag_slug'),
				'categories' => serialize(directorypress_get_input_value($array, 'categories', array())),
				'locations' => serialize(directorypress_get_input_value($array, 'locations', array())),
				'packages' => serialize(directorypress_get_input_value($array, 'packages', array())),
		);
		$insert_update_args = apply_filters('directorypress_directory_create_edit_args', $insert_update_args, $array);
	
		if ($wpdb->update($wpdb->directorypress_directorytypes, $insert_update_args, array('id' => $directory_id), null, array('%d')) !== false) {
			do_action('directorypress_update_directory', $directory_id, $array);
			
			$this->directorypress_pull_directorytypes();
			return true;
		}
	}
	
	public function deleteDirectory($directory_id, $new_directory_id) {
		global $directorypress_object, $wpdb;

		// We can not delete default directorytype
		if ($directory_id != $this->directorypress_get_base_directorytype()->id) {
			$wpdb->delete($wpdb->directorypress_directorytypes, array('id' => $directory_id));
			
			if (!$directorypress_object->directorytypes->directory_by_id($new_directory_id)) {
				$new_directory_id = $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id;
			}
			$wpdb->update($wpdb->postmeta, array('meta_value' => $new_directory_id), array('meta_key' => '_directory_id', 'meta_value' => $directory_id));
	
			$this->directorypress_pull_directorytypes();
			return true;
		}
	}
	
	public function get_current_page_directory($page_id = 0) {
		$current_directorytype = null;

		$pattern = get_shortcode_regex(array('directorypress-main'));
		if ($page_id && ($page = get_post($page_id))) {
			if (preg_match_all('/'.$pattern.'/s', $page->post_content, $matches) && array_key_exists(2, $matches)) {
				foreach ($matches[2] AS $key=>$shortcode) {
					if ($shortcode == 'directorypress-main') {
						if (($attrs = shortcode_parse_atts($matches[3][$key]))) {
							if (isset($attrs['id']) && is_numeric($attrs['id']) && ($directorytype = $this->directory_by_id($attrs['id']))) {
								$current_directorytype = $directorytype;
								break;
							} elseif (!isset($attrs['id'])) {
								$current_directorytype = $this->directorypress_get_base_directorytype();
								break;
							}
						} else {
							$current_directorytype = $this->directorypress_get_base_directorytype();
							break;
						}
					}
				}
			}
		} else {
			$current_directorytype = $this->directorypress_get_base_directorytype();
		}
		
		return $current_directorytype;
	}
}

class directorypress_directorytype {
	public $id;
	public $url;
	public $name;
	public $single;
	public $plural;
	public $listing_slug;
	public $category_slug;
	public $location_slug;
	public $tag_slug;
	public $categories = array();
	public $locations = array();
	public $packages = array();
	
	public function __construct() {
		//$this->listing_slug = get_option('directorypress_listing_slug');
		//$this->category_slug = get_option('directorypress_category_slug');
		//$this->location_slug = get_option('directorypress_location_slug');
		//$this->tag_slug = get_option('directorypress_tag_slug');
	}

	public function directorypress_create_directorytype_from_array_values($array) {
		$this->id = directorypress_get_input_value($array, 'id');
		$this->name = directorypress_get_input_value($array, 'name');
		$this->single = directorypress_get_input_value($array, 'single');
		$this->plural = directorypress_get_input_value($array, 'plural');
		$this->listing_slug = directorypress_get_input_value($array, 'listing_slug');
		$this->category_slug = directorypress_get_input_value($array, 'category_slug');
		$this->location_slug = directorypress_get_input_value($array, 'location_slug');
		$this->tag_slug = directorypress_get_input_value($array, 'tag_slug');
		$this->categories = directorypress_get_input_value($array, 'categories');
		$this->locations = directorypress_get_input_value($array, 'locations');
		$this->packages = directorypress_get_input_value($array, 'packages');
		
		$this->directorypress_process_categories();
		$this->directorypress_process_locations();
		$this->directorypress_process_packages();
		
		apply_filters('directorypress_directorytypes_loading', $this, $array);
	}
	
	public function directorypress_process_categories() {
		if ($this->categories) {
			$unserialized_categories = maybe_unserialize($this->categories);
			if (count($unserialized_categories) > 1 || $unserialized_categories != array(''))
				$this->categories = $unserialized_categories;
			else
				$this->categories = array();
		} else
			$this->categories = array();
		return $this->categories;
	}

	public function directorypress_process_locations() {
		if ($this->locations) {
			$unserialized_locations = maybe_unserialize($this->locations);
			if (count($unserialized_locations) > 1 || $unserialized_locations != array(''))
				$this->locations = $unserialized_locations;
			else
				$this->locations = array();
		} else
			$this->locations = array();
		return $this->locations;
	}

	public function directorypress_process_packages() {
		if ($this->packages) {
			$unserialized_packages = maybe_unserialize($this->packages);
			if (count($unserialized_packages) > 1 || $unserialized_packages != array(''))
				$this->packages = $unserialized_packages;
			else
				$this->packages = array();
		} else
			$this->packages = array();
		return $this->packages;
	}
	
	/**
	 * this is required to have an URL in advance in 'init' hook
	 * 
	 * @return string
	 */
	public function directorypress_preset_url() {
		global $directorypress_object;
		
		$pattern = get_shortcode_regex(array('directorypress-main'));
		
		foreach ($directorypress_object->directorypress_all_archive_pages AS $index_page) {
			
			$page_obj = get_post($index_page['id']);
			if (preg_match_all('/'.$pattern.'/s', $page_obj->post_content, $matches) && array_key_exists(2, $matches)) {
				foreach ($matches[2] AS $key=>$shortcode) {
					if ($shortcode == 'directorypress-main') {
						if ($attrs = shortcode_parse_atts($matches[3][$key])) {
							if (isset($attrs['id']) && is_numeric($attrs['id']) && $this->id == $attrs['id']) {
								$this->url = get_permalink($page_obj);
							} elseif (!isset($attrs['id']) && $this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id) {
								$this->url = get_permalink($page_obj);
							}
						} elseif ($this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id) {
							$this->url = get_permalink($page_obj);
						}
					}
				}
			}else{
				if($page_obj){
					
					if(directorypress_is_elementor_active()){
						
						if(isset($_GET['action']) && ($_GET['action'] == 'elementor')){
							$this->url = get_permalink($page_obj);
						}else{	
							
							$get_settings	= new \DirectoryPress_Elementor_Widget_Settings($page_obj->ID,'directorypress-main'); 
							if(!is_null($get_settings->widget)){
								
								
								$settings	= $get_settings->get_settings();
								if($settings['directorytype'] != $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id && $settings['directorytype'] == $this->id){
									
									$this->url = get_permalink($page_obj);
									
								}elseif($settings['directorytype'] == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id && $this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id){
									
									$this->url = get_permalink($page_obj);
									
								}elseif($this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id){
								
									$this->url = get_permalink($page_obj);
									
								}
							}
						}
					}else{
						if($this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id){
							
							$this->url = get_permalink($page_obj);
							
						}
					}
				}
			}
		}
		return $this->url;
	}

	public function directorypress_build_directorytype_url() {
		global $directorypress_object;

		$possible_url = '';
		$current_page_url = '';
	
		$pattern = get_shortcode_regex(array('directorypress-main'));
	
		foreach ($directorypress_object->directorypress_all_archive_pages AS $index_page) {
			$page_obj = get_post($index_page['id']);
			if (preg_match_all('/'.$pattern.'/s', $page_obj->post_content, $matches) && array_key_exists(2, $matches)) {
				foreach ($matches[2] AS $key=>$shortcode) {
					if ($shortcode == 'directorypress-main') {
						if (
							$directorypress_object->current_directorytype &&
							$this->id == $directorypress_object->current_directorytype->id &&
							$page_obj->ID == $directorypress_object->directorypress_archive_page_id &&
							($directory_of_page = $directorypress_object->directorytypes->get_current_page_directory($page_obj->ID)) && 
							$this->id == $directory_of_page->id
						) {
							$current_page_url = get_permalink($page_obj);
							break;
							break;
						}
						if ($attrs = shortcode_parse_atts($matches[3][$key])) {
							if (isset($attrs['id']) && is_numeric($attrs['id']) && $this->id == $attrs['id']) {
								$possible_url = get_permalink($page_obj);
							} elseif (!isset($attrs['id']) && $this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id) {
								$possible_url = get_permalink($page_obj);
							}
						} elseif ($this->id == $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id) {
							$possible_url = get_permalink($page_obj);
						}
					}
				}
			}
		}

		if ($current_page_url) {
			$this->url = $current_page_url;
		} elseif ($possible_url) {
			$this->url = $possible_url;
		}

		return $this->url;
	}
}

// adapted for WPML
add_action('init', 'directorypress_directorytypes_names_into_strings');
function directorypress_directorytypes_names_into_strings() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS &$directorytype) {
			$directorytype->single = apply_filters('wpml_translate_single_string', $directorytype->single, 'DirectoryPress', 'Single item of directorytype #' . $directorytype->id);
			$directorytype->plural = apply_filters('wpml_translate_single_string', $directorytype->plural, 'DirectoryPress', 'Plural item of directorytype #' . $directorytype->id);
		}
	}
}

add_filter('directorypress_package_create_edit_args', 'directorypress_filter_directory_categories_locations_types', 10, 2);
function directorypress_filter_directory_categories_locations_types($insert_update_args, $array) {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['directory_id'])) {
				$directory_id = sanitize_text_field($_GET['directory_id']);
				if ($single_string_id = icl_st_is_registered_string('DirectoryPress', 'Single item of directorytype #' . esc_attr($directory_id)))
					icl_add_string_translation($single_string_id, ICL_LANGUAGE_CODE, $insert_update_args['single'], ICL_TM_COMPLETE);
				if ($plural_string_id = icl_st_is_registered_string('DirectoryPress', 'Plural item of directorytype #' . esc_attr($directory_id)))
					icl_add_string_translation($plural_string_id, ICL_LANGUAGE_CODE, $insert_update_args['plural'], ICL_TM_COMPLETE);
				unset($insert_update_args['single']);
				unset($insert_update_args['plural']);
				
				unset($insert_update_args['categories']);
				unset($insert_update_args['locations']);
			} else { 
				$insert_update_args['categories'] = '';
				$insert_update_args['locations'] = '';
			}
		}
	}
	return $insert_update_args;
}

add_action('directorypress_update_directory', 'directorypress_save_directory_categories_locations_types', 10, 2);
function directorypress_save_directory_categories_locations_types($directory_id, $array) {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			update_option('directorypress_wpml_directory_categories_'.$directory_id.'_'.ICL_LANGUAGE_CODE, directorypress_get_input_value($array, 'categories'));
			update_option('directorypress_wpml_directory_locations_'.$directory_id.'_'.ICL_LANGUAGE_CODE, directorypress_get_input_value($array, 'locations'));
		}
		
		if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) {
			do_action('wpml_register_single_string', 'DirectoryPress', 'Single item of directorytype #' . $directory_id, directorypress_get_input_value($array, 'single'));
			do_action('wpml_register_single_string', 'DirectoryPress', 'Plural item of directorytype #' . $directory_id, directorypress_get_input_value($array, 'plural'));
		}
	}
}
	
add_action('init', 'directorypress_load_directory_categories_locations_types');
function directorypress_load_directory_categories_locations_types() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS &$directorytype) {
				$_categories = get_option('directorypress_wpml_directory_categories_'.$directorytype->id.'_'.ICL_LANGUAGE_CODE);
				$_locations = get_option('directorypress_wpml_directory_locations_'.$directorytype->id.'_'.ICL_LANGUAGE_CODE);
				if ($_categories && (count($_categories) > 1 || $_categories != array(''))){
					$directorytype->categories = $_categories;
				}else{
					$directorytype->categories = array();
				}
				if ($_locations && (count($_locations) > 1 || $_locations != array('')))
					$directorytype->locations = $_locations;
				else
					$directorytype->locations = array();
			}
		}
	}
}

?>